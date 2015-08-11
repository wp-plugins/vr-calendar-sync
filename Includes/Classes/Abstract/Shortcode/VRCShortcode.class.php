<?php
abstract class VRCShortcode extends VRCSingleton {

    protected $slug;
    protected $atts;

    // Call function to enqueue scripts and styles in the frontend?
    protected $enqueue_scripts_frontend = true;
    // Call function to enqueue scripts and styles in the admin pages?
    protected $enqueue_scripts_admin = false;

    protected function __construct()
    {
        add_shortcode($this->slug, array($this,'shortcode_handler'));

        if($this->enqueue_scripts_frontend) {
            add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts') );
        }

        if($this->enqueue_scripts_admin) {
            add_action( 'admin_enqueue_scripts', array($this,'admin_enqueue_scripts') );
        }
    }

    /*
     * Enqueue scripts for the frontend.
     */
    function enqueue_scripts() {
        /* this method will be overridden in child classes */
    }

    /*
     * Enqueue scripts in admin pages.
     * By default, runs the same code as is run in the frontend (by calling enqueue_scripts).
     * You can override it to load specific scripts.
     */
    function admin_enqueue_scripts() {
        $this->enqueue_scripts();
    }

    function get_javascript() {

    }

    protected function renderView($view, $data) {
        ob_start();
        include(VRCALENDAR_PLUGIN_DIR . "/Public/Views/Shortcodes/{$view}.view.php");
        return ob_get_clean();
    }

    abstract function shortcode_handler( $atts , $content="" );
}