<?php
/**
 * Enqueue scripts and styles
 */

class WMedi_Enqueue {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_ajax_nonce'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue styles
        wp_enqueue_style(
            'wmedi-style',
            WMEDI_PLUGIN_URL . 'assets/css/wmedi-style.css',
            array(),
            WMEDI_PLUGIN_VERSION
        );

        // Enqueue scripts
        wp_enqueue_script(
            'wmedi-script',
            WMEDI_PLUGIN_URL . 'assets/js/wmedi-script.js',
            array('jquery'),
            WMEDI_PLUGIN_VERSION,
            true
        );
    }

    /**
     * Add AJAX nonce to page
     */
    public function add_ajax_nonce() {
        ?>
        <script>
            console.log('Nonce generated: <?php echo wp_create_nonce("wmedi_nonce"); ?>');
            var wmediNonce = '<?php echo wp_create_nonce("wmedi_nonce"); ?>';
        </script>
        <?php
    }
}
