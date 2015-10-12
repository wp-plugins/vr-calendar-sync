<?php
$VRCalendarSettings = VRCalendarSettings::getInstance();
$auto_sync = array(
    'none'=>'Disable',
    'hourly'=>'Hourly',
    'twicedaily'=>'Twice Daily',
    'daily'=>'Daily'
);
?>
<div class="wrap">
    <h2>Settings</h2>
    <div class="tabs-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class='nav-tab nav-tab-active' href='#general-options'>General</a>
        </h2>
        <div class="tabs-content-wrapper">
            <form method="post" action="" >
                <div id="general-options" class="tab-content tab-content-active">
                    <?php require(VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/Settings/General.php'); ?>
                </div>
                <div>
                    <input type="hidden" name="vrc_cmd" id="vrc_cmd" value="VRCalendarAdmin:saveSettings" />
                    <input type="submit" value="Save" class="button button-primary">
                </div>
            </form>
        </div>
    </div>
</div>