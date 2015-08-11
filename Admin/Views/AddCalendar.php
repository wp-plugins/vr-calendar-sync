<?php
$VRCalendarEntity = VRCalendarEntity::getInstance();
if(isset($_GET['cal_id'])) {
    /* Fetch Calendar Details */
    $cdata = $VRCalendarEntity->getCalendar($_GET['cal_id']);
}
else {
    $cdata = $VRCalendarEntity->getEmptyCalendar();
}
$layout_option_size = array(
    'small'=>'Small',
    'medium'=>'Medium',
    'large'=>'Large'
);
$enable_booking = array(
    'yes'=>'Yes',
    'no'=>'No'
);
$tax_type = array(
    'flat'=>'$',
    'percentage'=>'%'
);
$payment_method = array(
    'paypal'=>'PayPal',
    'stripe'=>'Stripe',
    'both'=>'Both',
    'none'=>'None'
);
$alert_double_booking = array(
    'yes'=>'Yes',
    'no'=>'No'
);
$requires_admin_approval = array(
    'yes'=>'Yes',
    'no'=>'No'
);
?>
<div class="wrap">
    <h2>Add Calendar</h2>
    <div class="tabs-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class='nav-tab nav-tab-active' href='#general-options'>General</a>
            <a class='nav-tab' href='#booking-options'>Booking Options</a>
            <a class='nav-tab' href='#color-options'>Color Options</a>
        </h2>
        <div class="tabs-content-wrapper">
            <form method="post" action="" >
                <div id="general-options" class="tab-content tab-content-active">
                    <?php require(VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/AddCalendar/General.php'); ?>
                </div>
                <div id="booking-options" class="tab-content">
                    <div class="update-nag">
                        Booking is not enabled with the free version, please upgrade to PRO to take bookings via PayPal or Stripe
                    </div>
                </div>
                <div id="color-options" class="tab-content">
                    <?php require(VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/AddCalendar/Color.php'); ?>
                </div>
                <div>
                    <input type="hidden" name="vrc_cmd" id="vrc_cmd" value="VRCalendarAdmin:saveCalendar" />
                    <input type="hidden" name="calendar_id" id="calendar_id" value="<?php echo $cdata->calendar_id; ?>" />
                    <input type="submit" value="Save" class="button button-primary">
                </div>
            </form>
        </div>
    </div>
</div>