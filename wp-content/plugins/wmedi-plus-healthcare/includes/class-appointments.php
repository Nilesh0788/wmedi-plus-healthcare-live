<?php
/**
 * Appointments management class
 */

class WMedi_Appointments {
    public function __construct() {
        // Appointment AJAX handlers are now in class-ajax-handlers.php
    }

    /**
     * Get user's appointments
     */
    public static function get_user_appointments($user_id, $user_type) {
        global $wpdb;

        if ($user_type === 'patient') {
            $appointments = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT a.*, d.specialization, u.display_name as doctor_name
                    FROM {$wpdb->prefix}wmedi_appointments a
                    INNER JOIN {$wpdb->prefix}wmedi_doctors d ON a.doctor_id = d.user_id
                    INNER JOIN {$wpdb->users} u ON d.user_id = u.ID
                    WHERE a.patient_id = %d
                    ORDER BY a.appointment_date DESC",
                    $user_id
                )
            );
        } else {
            $appointments = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT a.*, u.display_name as patient_name, uu.phone as patient_phone
                    FROM {$wpdb->prefix}wmedi_appointments a
                    INNER JOIN {$wpdb->users} u ON a.patient_id = u.ID
                    INNER JOIN {$wpdb->prefix}wmedi_users uu ON u.ID = uu.user_id
                    WHERE a.doctor_id = %d
                    ORDER BY a.appointment_date DESC",
                    $user_id
                )
            );
        }

        return $appointments;
    }

    /**
     * Send confirmation emails
     */
    public static function send_confirmation_emails($patient_id, $doctor_id, $appointment_date, $appointment_time) {
        $patient = get_userdata($patient_id);
        $doctor = get_userdata($doctor_id);

        $subject = 'Appointment Confirmation - WMedi Plus';
        $patient_message = "Your appointment with Dr. {$doctor->display_name} has been confirmed.\n\n";
        $patient_message .= "Date: {$appointment_date}\n";
        $patient_message .= "Time: {$appointment_time}\n\n";
        $patient_message .= "Thank you for using WMedi Plus!";

        $doctor_message = "New appointment scheduled with {$patient->display_name}\n\n";
        $doctor_message .= "Date: {$appointment_date}\n";
        $doctor_message .= "Time: {$appointment_time}\n\n";
        $doctor_message .= "Please be available at the scheduled time.";

        wp_mail($patient->user_email, $subject, $patient_message);
        wp_mail($doctor->user_email, $subject, $doctor_message);
    }
}
