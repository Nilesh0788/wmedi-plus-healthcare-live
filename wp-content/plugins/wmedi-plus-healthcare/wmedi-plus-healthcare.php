<?php
/**
 * Plugin Name: WMedi Plus Healthcare Platform
 * Plugin URI: https://wmediplus.com
 * Description: A comprehensive healthcare platform connecting patients with doctors
 * Version: 1.0.0
 * Author: WMedi Plus
 * Author URI: https://wmediplus.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wmedi-plus
 * Domain Path: /languages
 * 
 * @package WMedi_Plus_Healthcare
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WMEDI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WMEDI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WMEDI_PLUGIN_VERSION', '1.0.0');

// Include required files
require_once WMEDI_PLUGIN_DIR . 'includes/class-database.php';
require_once WMEDI_PLUGIN_DIR . 'includes/class-authentication.php';
require_once WMEDI_PLUGIN_DIR . 'includes/class-pages.php';
require_once WMEDI_PLUGIN_DIR . 'includes/class-doctor-matching.php';
require_once WMEDI_PLUGIN_DIR . 'includes/class-appointments.php';
require_once WMEDI_PLUGIN_DIR . 'includes/class-ajax-handlers.php';
require_once WMEDI_PLUGIN_DIR . 'includes/class-enqueue.php';

// Activation hook
register_activation_hook(__FILE__, array('WMedi_Database', 'create_tables'));

// Initialize plugin
class WMedi_Plus_Healthcare {
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        // Initialize all components
        new WMedi_Enqueue();
        new WMedi_Pages();
        new WMedi_Authentication();
        new WMedi_AJAX_Handlers();
        new WMedi_Doctor_Matching();
        new WMedi_Appointments();
    }
}

// Run the plugin
new WMedi_Plus_Healthcare();

// Plugin activation and deactivation
function wmedi_plus_activate() {
    WMedi_Database::create_tables();
    flush_rewrite_rules();
}

function wmedi_plus_deactivate() {
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'wmedi_plus_activate');
register_deactivation_hook(__FILE__, 'wmedi_plus_deactivate');
