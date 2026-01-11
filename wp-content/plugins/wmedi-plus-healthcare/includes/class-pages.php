<?php
/**
 * Pages management class for WMedi Plus
 */

class WMedi_Pages {
    public function __construct() {
        add_action('wp_loaded', array($this, 'create_pages'));
        add_filter('template_include', array($this, 'load_page_templates'));
        
        // Register shortcodes for all pages
        add_shortcode('wmedi_landing_page', array($this, 'render_landing_page'));
        add_shortcode('wmedi_auth', array($this, 'render_auth'));
        add_shortcode('wmedi_get_started', array($this, 'render_onboarding'));
        add_shortcode('wmedi_medical_query', array($this, 'render_medical_query'));
        add_shortcode('wmedi_choose_doctor', array($this, 'render_doctor_selection'));
        add_shortcode('wmedi_book_appointment', array($this, 'render_appointment_booking'));
        add_shortcode('wmedi_patient_dashboard', array($this, 'render_patient_dashboard'));
        add_shortcode('wmedi_doctor_dashboard', array($this, 'render_doctor_dashboard'));
    }

    /**
     * Create required pages
     */
    public function create_pages() {
        // Check if pages already exist
        if (get_option('wmedi_pages_created')) {
            return;
        }

        $pages = array(
            array(
                'post_title' => 'Welcome to WMedi Plus',
                'post_name' => 'welcome',
                'post_type' => 'page',
                'post_content' => '[wmedi_landing_page]'
            ),
            array(
                'post_title' => 'Secure Authentication',
                'post_name' => 'auth',
                'post_type' => 'page',
                'post_content' => '[wmedi_auth]'
            ),
            array(
                'post_title' => 'Get Started with WMedi Plus',
                'post_name' => 'get-started',
                'post_type' => 'page',
                'post_content' => '[wmedi_get_started]'
            ),
            array(
                'post_title' => 'Tell Us About Your Health',
                'post_name' => 'medical-query',
                'post_type' => 'page',
                'post_content' => '[wmedi_medical_query]'
            ),
            array(
                'post_title' => 'Choose Your Doctor',
                'post_name' => 'choose-doctor',
                'post_type' => 'page',
                'post_content' => '[wmedi_choose_doctor]'
            ),
            array(
                'post_title' => 'Book Appointment',
                'post_name' => 'book-appointment',
                'post_type' => 'page',
                'post_content' => '[wmedi_book_appointment]'
            ),
            array(
                'post_title' => 'Patient Dashboard',
                'post_name' => 'dashboard',
                'post_type' => 'page',
                'post_content' => '[wmedi_patient_dashboard]'
            ),
            array(
                'post_title' => 'Doctor Dashboard',
                'post_name' => 'doctor-dashboard',
                'post_type' => 'page',
                'post_content' => '[wmedi_doctor_dashboard]'
            )
        );

        foreach ($pages as $page) {
            if (!get_page_by_path($page['post_name'], OBJECT, 'page')) {
                wp_insert_post($page);
            }
        }

        update_option('wmedi_pages_created', true);
    }

    /**
     * Load page templates
     */
    public function load_page_templates($template) {
        if (is_page()) {
            $page = get_queried_object();
            $page_slug = $page->post_name;

            $templates = array(
                'welcome' => 'landing-page.php',
                'auth' => 'authentication.php',
                'get-started' => 'onboarding.php',
                'medical-query' => 'medical-query.php',
                'choose-doctor' => 'doctor-selection.php',
                'book-appointment' => 'appointment-booking.php',
                'dashboard' => 'patient-dashboard.php',
                'doctor-dashboard' => 'doctor-dashboard.php'
            );

            if (isset($templates[$page_slug])) {
                $template = WMEDI_PLUGIN_DIR . 'templates/' . $templates[$page_slug];
            }
        }

        return $template;
    }

    /**
     * Render shortcodes - fallback method if template doesn't load
     */
    public function render_landing_page() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/landing-page.php';
        return ob_get_clean();
    }

    public function render_auth() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/authentication.php';
        return ob_get_clean();
    }

    public function render_onboarding() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/onboarding.php';
        return ob_get_clean();
    }

    public function render_medical_query() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/medical-query.php';
        return ob_get_clean();
    }

    public function render_doctor_selection() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/doctor-selection.php';
        return ob_get_clean();
    }

    public function render_appointment_booking() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/appointment-booking.php';
        return ob_get_clean();
    }

    public function render_patient_dashboard() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/patient-dashboard.php';
        return ob_get_clean();
    }

    public function render_doctor_dashboard() {
        ob_start();
        include WMEDI_PLUGIN_DIR . 'templates/doctor-dashboard.php';
        return ob_get_clean();
    }
}
