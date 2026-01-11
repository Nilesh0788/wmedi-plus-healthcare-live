<?php
/**
 * Database management class for WMedi Plus
 */

class WMedi_Database {
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Users extended table (patient/doctor specific info)
        $users_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wmedi_users (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            user_type VARCHAR(20) NOT NULL,
            phone VARCHAR(20),
            gender VARCHAR(20),
            date_of_birth DATE,
            profile_photo LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY user_id (user_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
        ) $charset_collate;";

        // Doctor specific table
        $doctors_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wmedi_doctors (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            specialization VARCHAR(255) NOT NULL,
            years_of_experience INT(3),
            availability_timings LONGTEXT,
            consultation_fee DECIMAL(10, 2),
            bio LONGTEXT,
            qualifications LONGTEXT,
            verified BOOLEAN DEFAULT FALSE,
            rating DECIMAL(3, 2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY user_id (user_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
        ) $charset_collate;";

        // Medical queries table
        $queries_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wmedi_medical_queries (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            patient_id BIGINT(20) UNSIGNED NOT NULL,
            primary_symptoms VARCHAR(255),
            duration_of_illness VARCHAR(50),
            severity_level VARCHAR(20),
            fever BOOLEAN,
            pain BOOLEAN,
            chronic_issue BOOLEAN,
            detailed_description LONGTEXT,
            status VARCHAR(20) DEFAULT 'open',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            INDEX idx_patient (patient_id),
            INDEX idx_status (status)
        ) $charset_collate;";

        // Appointments table
        $appointments_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wmedi_appointments (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            patient_id BIGINT(20) UNSIGNED NOT NULL,
            doctor_id BIGINT(20) UNSIGNED NOT NULL,
            query_id BIGINT(20) UNSIGNED,
            appointment_date DATE NOT NULL,
            appointment_time TIME NOT NULL,
            status VARCHAR(20) DEFAULT 'scheduled',
            notes LONGTEXT,
            prescription LONGTEXT,
            diagnosis LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (patient_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (doctor_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (query_id) REFERENCES {$wpdb->prefix}wmedi_medical_queries(id) ON DELETE SET NULL,
            INDEX idx_patient (patient_id),
            INDEX idx_doctor (doctor_id),
            INDEX idx_date (appointment_date),
            INDEX idx_status (status)
        ) $charset_collate;";

        // Doctor availability slots table
        $availability_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wmedi_availability_slots (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            doctor_id BIGINT(20) UNSIGNED NOT NULL,
            day_of_week VARCHAR(20),
            start_time TIME,
            end_time TIME,
            is_available BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (doctor_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            INDEX idx_doctor (doctor_id)
        ) $charset_collate;";

        // Doctor matching log
        $matching_table = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wmedi_doctor_matches (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            query_id BIGINT(20) UNSIGNED NOT NULL,
            doctor_id BIGINT(20) UNSIGNED NOT NULL,
            match_score DECIMAL(5, 2),
            reason LONGTEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (query_id) REFERENCES {$wpdb->prefix}wmedi_medical_queries(id) ON DELETE CASCADE,
            FOREIGN KEY (doctor_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            INDEX idx_query (query_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($users_table);
        dbDelta($doctors_table);
        dbDelta($queries_table);
        dbDelta($appointments_table);
        dbDelta($availability_table);
        dbDelta($matching_table);

        do_action('wmedi_tables_created');
    }
}
