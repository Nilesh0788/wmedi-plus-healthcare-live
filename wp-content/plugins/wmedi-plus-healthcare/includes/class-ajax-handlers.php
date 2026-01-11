<?php
/**
 * AJAX handlers for WMedi Plus
 */

class WMedi_AJAX_Handlers {
    public function __construct() {
        add_action('wp_ajax_wmedi_save_medical_query', array($this, 'save_medical_query'));
        add_action('wp_ajax_nopriv_wmedi_save_medical_query', array($this, 'save_medical_query'));
        
        add_action('wp_ajax_wmedi_get_doctor_slots', array($this, 'get_doctor_slots'));
        add_action('wp_ajax_nopriv_wmedi_get_doctor_slots', array($this, 'get_doctor_slots'));
        
        add_action('wp_ajax_wmedi_book_appointment', array($this, 'book_appointment'));
        add_action('wp_ajax_nopriv_wmedi_book_appointment', array($this, 'book_appointment'));
        
        add_action('wp_ajax_wmedi_get_matched_doctors', array($this, 'get_matched_doctors'));
        add_action('wp_ajax_nopriv_wmedi_get_matched_doctors', array($this, 'get_matched_doctors'));
        
        add_action('wp_ajax_wmedi_get_appointments', array($this, 'get_appointments'));
        add_action('wp_ajax_nopriv_wmedi_get_appointments', array($this, 'get_appointments'));
    }

    /**
     * Save medical query
     */
    public function save_medical_query() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
            return;
        }

        $patient_id = get_current_user_id();
        $primary_symptoms = sanitize_text_field($_POST['primary_symptoms']);
        $duration = sanitize_text_field($_POST['duration']);
        $severity = sanitize_text_field($_POST['severity']);
        $fever = isset($_POST['fever']) ? 1 : 0;
        $pain = isset($_POST['pain']) ? 1 : 0;
        $chronic = isset($_POST['chronic']) ? 1 : 0;
        $description = wp_kses_post($_POST['description']);

        if (empty($primary_symptoms) || empty($duration) || empty($severity)) {
            wp_send_json_error(array('message' => 'Please fill in all required fields'));
            return;
        }

        global $wpdb;

        $result = $wpdb->insert(
            $wpdb->prefix . 'wmedi_medical_queries',
            array(
                'patient_id' => $patient_id,
                'primary_symptoms' => $primary_symptoms,
                'duration_of_illness' => $duration,
                'severity_level' => $severity,
                'fever' => $fever,
                'pain' => $pain,
                'chronic_issue' => $chronic,
                'detailed_description' => $description
            ),
            array('%d', '%s', '%s', '%s', '%d', '%d', '%d', '%s')
        );

        if (!$result) {
            wp_send_json_error(array('message' => 'Failed to save query'));
            return;
        }

        $query_id = $wpdb->insert_id;

        wp_send_json_success(array(
            'message' => 'Query saved successfully',
            'query_id' => $query_id,
            'redirect' => home_url('/choose-doctor?query_id=' . $query_id)
        ));
    }

    /**
     * Get available doctor slots
     */
    public function get_doctor_slots() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        $doctor_id = intval($_POST['doctor_id']);
        $date = sanitize_text_field($_POST['date']);

        if (empty($doctor_id) || empty($date)) {
            wp_send_json_error(array('message' => 'Invalid parameters'));
            return;
        }

        global $wpdb;

        // Default time slots (9 AM to 5 PM, 1-hour slots)
        $slots = array();
        for ($hour = 9; $hour < 17; $hour++) {
            $slots[] = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
        }

        // Get booked slots for this date
        $booked = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT appointment_time FROM {$wpdb->prefix}wmedi_appointments 
                WHERE doctor_id = %d AND appointment_date = %s AND status IN ('scheduled', 'confirmed')",
                $doctor_id,
                $date
            )
        );

        // Remove booked slots
        $available_slots = array_diff($slots, $booked);

        wp_send_json_success(array(
            'slots' => array_values($available_slots)
        ));
    }

    /**
     * Book appointment
     */
    public function book_appointment() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
            return;
        }

        $patient_id = get_current_user_id();
        $doctor_id = intval($_POST['doctor_id']);
        $query_id = intval($_POST['query_id']);
        $appointment_date = sanitize_text_field($_POST['appointment_date']);
        $appointment_time = sanitize_text_field($_POST['appointment_time']);
        $notes = isset($_POST['notes']) ? wp_kses_post($_POST['notes']) : '';

        if (empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
            wp_send_json_error(array('message' => 'Missing required fields'));
            return;
        }

        global $wpdb;

        // Check if slot is available
        $existing = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}wmedi_appointments 
                WHERE doctor_id = %d AND appointment_date = %s AND appointment_time = %s AND status IN ('scheduled', 'confirmed')",
                $doctor_id,
                $appointment_date,
                $appointment_time
            )
        );

        if ($existing) {
            wp_send_json_error(array('message' => 'Time slot is not available'));
            return;
        }

        // Create appointment
        $result = $wpdb->insert(
            $wpdb->prefix . 'wmedi_appointments',
            array(
                'patient_id' => $patient_id,
                'doctor_id' => $doctor_id,
                'query_id' => $query_id,
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time,
                'notes' => $notes,
                'status' => 'scheduled'
            ),
            array('%d', '%d', '%d', '%s', '%s', '%s', '%s')
        );

        if (!$result) {
            wp_send_json_error(array('message' => 'Failed to book appointment'));
            return;
        }

        wp_send_json_success(array(
            'message' => 'Appointment booked successfully',
            'redirect' => home_url('/dashboard')
        ));
    }

    /**
     * Get matched doctors
     */
    public function get_matched_doctors() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
            return;
        }

        $query_id = intval($_POST['query_id']);
        $patient_id = get_current_user_id();

        global $wpdb;

        // Get patient's query details
        $query = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wmedi_medical_queries WHERE id = %d AND patient_id = %d",
                $query_id,
                $patient_id
            )
        );

        if (!$query) {
            wp_send_json_error(array('message' => 'Query not found'));
            return;
        }

        // Get matched doctors
        $doctors = $this->match_doctors($query);

        wp_send_json_success(array(
            'doctors' => $doctors,
            'total' => count($doctors)
        ));
    }

    /**
     * Match doctors based on symptoms
     */
    private function match_doctors($query) {
        global $wpdb;

        // Get all doctors
        $doctors = $wpdb->get_results(
            "SELECT u.ID as user_id, u.display_name, d.specialization, d.years_of_experience, d.rating
            FROM {$wpdb->users} u
            INNER JOIN {$wpdb->prefix}wmedi_doctors d ON u.ID = d.user_id
            INNER JOIN {$wpdb->prefix}wmedi_users ud ON u.ID = ud.user_id
            WHERE ud.user_type = 'doctor'
            ORDER BY d.rating DESC LIMIT 10"
        );

        $matched_doctors = array();
        $symptom_keywords = array_map('trim', explode(',', $query->primary_symptoms));

        foreach ($doctors as $doctor) {
            $score = 50; // Base score

            // Match specialization with symptoms
            foreach ($symptom_keywords as $symptom) {
                if (!empty($symptom) && stripos($doctor->specialization, $symptom) !== false) {
                    $score += 20;
                }
            }

            // Add bonus for experience
            if ($doctor->years_of_experience > 0) {
                $score += min($doctor->years_of_experience * 1.5, 20);
            }

            // Add rating bonus
            if ($doctor->rating > 0) {
                $score += min($doctor->rating * 5, 15);
            }

            $matched_doctors[] = array(
                'user_id' => $doctor->user_id,
                'name' => $doctor->display_name,
                'specialization' => $doctor->specialization,
                'experience' => $doctor->years_of_experience,
                'rating' => $doctor->rating,
                'score' => min($score, 100)
            );
        }

        // Sort by score
        usort($matched_doctors, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return array_slice($matched_doctors, 0, 5); // Return top 5 doctors
    }

    /**
     * Get user's appointments
     */
    public function get_appointments() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
            return;
        }

        $user_id = get_current_user_id();
        $appointments = WMedi_Appointments::get_user_appointments($user_id, WMedi_Authentication::get_user_type($user_id));

        wp_send_json_success(array(
            'appointments' => $appointments
        ));
    }
}

