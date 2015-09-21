<?php
class VRCalendarSettings extends VRCSingleton {
    private $optionKey;

    public function __construct() {
        $this->optionKey = VRCALENDAR_PLUGIN_SLUG.'_options';
    }
    public function getSettings($option, $default='') {
        $options = get_option( $this->optionKey );

        if(is_array($options)) {
            if(isset($options[$option]))
                return stripcslashes($options[$option]);
        }

        return $default;
    }
    public function setSettings($option, $value='') {
        $options = get_option( $this->optionKey );

        if(!is_array($options)) {
            $options = array();
        }

        $options[$option] = $value;
        update_option( $this->optionKey, $options );
        return true;
    }
}