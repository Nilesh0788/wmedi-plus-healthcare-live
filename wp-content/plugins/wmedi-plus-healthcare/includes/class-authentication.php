<?php
/**
 * Authentication class for WMedi Plus
 */

class WMedi_Authentication {
    public function __construct() {
        add_action('wp_ajax_nopriv_wmedi_signup', array($this, 'handle_signup'));
        add_action('wp_ajax_wmedi_signup', array($this, 'handle_signup'));
        add_action('wp_ajax_nopriv_wmedi_login', array($this, 'handle_login'));
        add_action('wp_ajax_wmedi_login', array($this, 'handle_login'));
        add_action('wp_ajax_wmedi_logout', array($this, 'handle_logout'));
        add_action('wp_ajax_wmedi_get_user_info', array($this, 'get_user_info'));
    }

    /**
     * Handle user signup
     */
    public function handle_signup() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        $user_type = sanitize_text_field($_POST['user_type']);
        $full_name = sanitize_text_field($_POST['full_name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $password = $_POST['password'];
        $gender = sanitize_text_field($_POST['gender']);
        $dob = sanitize_text_field($_POST['date_of_birth']);

        // Validate input
        if (empty($full_name) || empty($email) || empty($password) || empty($phone)) {
            wp_send_json_error(array('message' => 'All fields are required'));
            return;
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Invalid email address'));
            return;
        }

        if (email_exists($email)) {
            wp_send_json_error(array('message' => 'Email already registered'));
            return;
        }

        // Create WordPress user
        $username = sanitize_user(explode('@', $email)[0] . time());
        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
            return;
        }

        // Update user display name
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $full_name
        ));

        // Store extended user information
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'wmedi_users',
            array(
                'user_id' => $user_id,
                'user_type' => $user_type,
                'phone' => $phone,
                'gender' => $gender,
                'date_of_birth' => $dob
            ),
            array('%d', '%s', '%s', '%s', '%s')
        );

        // If doctor, store additional information
        if ($user_type === 'doctor') {
            $specialization = sanitize_text_field($_POST['specialization']);
            $experience = intval($_POST['years_of_experience']);
            
            $wpdb->insert(
                $wpdb->prefix . 'wmedi_doctors',
                array(
                    'user_id' => $user_id,
                    'specialization' => $specialization,
                    'years_of_experience' => $experience
                ),
                array('%d', '%s', '%d')
            );
        }

        // Log user in
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        wp_send_json_success(array(
            'message' => 'Signup successful',
            'user_id' => $user_id,
            'user_type' => $user_type,
            'redirect' => $user_type === 'patient' ? home_url('/get-started') : home_url('/doctor-dashboard')
        ));
    }

    /**
     * Handle user login
     */
    public function handle_login() {
        check_ajax_referer('wmedi_nonce', 'nonce');

        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            wp_send_json_error(array('message' => 'Email and password are required'));
            return;
        }

        // Get user by email
        $user = get_user_by('email', $email);
        if (!$user) {
            wp_send_json_error(array('message' => 'Invalid email or password'));
            return;
        }

        // Verify password
        if (!wp_check_password($password, $user->user_pass, $user->ID)) {
            wp_send_json_error(array('message' => 'Invalid email or password'));
            return;
        }

        // Log user in
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        // Get user type
        global $wpdb;
        $user_data = $wpdb->get_row(
            $wpdb->prepare("SELECT user_type FROM {$wpdb->prefix}wmedi_users WHERE user_id = %d", $user->ID)
        );

        $user_type = $user_data->user_type ?? 'patient';
        $redirect = $user_type === 'patient' ? home_url('/dashboard') : home_url('/doctor-dashboard');

        wp_send_json_success(array(
            'message' => 'Login successful',
            'user_id' => $user->ID,
            'user_type' => $user_type,
            'redirect' => $redirect
        ));
    }

    /**
     * Handle user logout
     */
    public function handle_logout() {
        wp_logout();
        wp_send_json_success(array(
            'message' => 'Logged out successfully',
            'redirect' => home_url('/login')
        ));
    }

    /**
     * Get current user info
     */
    public function get_user_info() {
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Not logged in'));
            return;
        }

        $user = wp_get_current_user();
        global $wpdb;
        
        $user_data = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wmedi_users WHERE user_id = %d", $user->ID)
        );

        wp_send_json_success(array(
            'user_id' => $user->ID,
            'email' => $user->user_email,
            'display_name' => $user->display_name,
            'user_type' => $user_data->user_type,
            'phone' => $user_data->phone,
            'gender' => $user_data->gender,
            'date_of_birth' => $user_data->date_of_birth
        ));
    }

    /**
     * Check if user is authenticated
     */
    public static function is_user_logged_in() {
        return is_user_logged_in();
    }

    /**
     * Get user type (patient or doctor)
     */
    public static function get_user_type($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        global $wpdb;
        $user_type = $wpdb->get_var(
            $wpdb->prepare("SELECT user_type FROM {$wpdb->prefix}wmedi_users WHERE user_id = %d", $user_id)
        );

        return $user_type ?? 'patient';
    }
}
