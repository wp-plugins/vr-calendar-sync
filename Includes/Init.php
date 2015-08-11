<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die();
}
/* Load required files */
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Abstract/Singleton/VRCSingleton.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Abstract/Shortcode/VRCShortcode.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Settings/VRCalendarSettings.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Email/VRCEmail.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Entity/VRCalendarEntity.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Entity/Booking/VRCalendarBooking.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/ICal/VRCICal.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRCalendarShortcode.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/VRCalendar.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Admin/Classes/VRCalendarAdmin.class.php' );

$load_admin = false;
if( is_admin() ) {
    $load_admin = true;
}
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( VRCALENDAR_PLUGIN_FILE, array( 'VRCalendar', 'activate' ) );
register_deactivation_hook( VRCALENDAR_PLUGIN_FILE, array( 'VRCalendar', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'VRCalendar', 'getInstance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( $load_admin ) {
    add_action( 'plugins_loaded', array( 'VRCalendarAdmin', 'getInstance' ) );
}
