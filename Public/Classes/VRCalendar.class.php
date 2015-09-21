<?php
class VRCalendar extends VRCSingleton {
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    const VERSION = '1.0.0';


    protected function __construct(){
        // Load plugin text domain
        add_action( 'init', array( $this, 'loadPluginTextdomain' ) );
        add_action( 'init', array( $this, 'initShortcodes' ) );
        add_action('init', array($this, 'handleCommands'));

        // Load public-facing style sheet and JavaScript.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        add_action( 'vrc_cal_sync_hook', array($this, 'syncAllCalendars') );

    }

    function syncAllCalendars() {
        $VRCalendarAdmin = VRCalendarAdmin::getInstance();
        $VRCalendarAdmin->syncAllCalendars();
    }

    function handleCommands() {
        if(isset($_POST['vrc_pcmd'])) {
            switch($_POST['vrc_pcmd']) {
                case 'saveBooking':
                    $this->saveBooking();
                    break;
                case 'paypalPayment':
                    $this->paypalPayment();
                    break;
            }
        }
    }

    function initShortcodes() {
        VRCalendarShortcode::getInstance();
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    function loadPluginTextdomain() {
        $domain = VRCALENDAR_PLUGIN_SLUG;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/Languages/' );
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-bootstrap-styles', VRCALENDAR_PLUGIN_URL.'assets/css/bootstrap.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-main', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.carousel.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-theme', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.theme.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-transitions', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.transitions.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-calendar-styles', VRCALENDAR_PLUGIN_URL.'assets/css/calendar.css', array(), self::VERSION );
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-plugin-styles', VRCALENDAR_PLUGIN_URL.'assets/css/public.css', array(), self::VERSION );
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-bootstrap-script', VRCALENDAR_PLUGIN_URL.'assets/js/bootstrap.js', array( 'jquery' ), self::VERSION );
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-script', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.carousel.js', array( 'jquery' ), self::VERSION );
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-plugin-script', VRCALENDAR_PLUGIN_URL.'assets/js/public.js', array( 'jquery', 'jquery-ui-datepicker' ), self::VERSION );

    }

    static function activate() {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $VRCalendarSettings = VRCalendarSettings::getInstance();

        $VRCalendarEntity->createTable();
        $VRCalendarBooking->createTable();

        $VRCalendarSettings = VRCalendarSettings::getInstance();
        /* Setup Cal Sync Task */
        wp_schedule_event( time(), $VRCalendarSettings->getSettings('auto_sync', 'daily'), 'vrc_cal_sync_hook' );
    }

    static function deactivate() {
        wp_clear_scheduled_hook( 'vrc_cal_sync_hook' );
    }

}