<?php
/**
 * Doctor matching class for WMedi Plus
 */

class WMedi_Doctor_Matching {
    public function __construct() {
        // Doctor matching is now handled in AJAX handlers
    }

    /**
     * Match doctors based on symptoms
     */
    public static function match_doctors($query) {
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
}
