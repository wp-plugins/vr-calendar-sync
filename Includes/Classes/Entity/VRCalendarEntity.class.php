<?php
class VRCalendarEntity extends VRCSingleton {

    public $table_name;

    private $calendar_id;

    protected function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'vrcalandar';
    }

    function createTable() {
        global $wpdb;

        $calendar_table_sql = "CREATE TABLE {$this->table_name} (
			calendar_id INT(11) NOT NULL AUTO_INCREMENT,
			calendar_name TEXT,
			calendar_links TEXT,
			calendar_layout_options TEXT,
			calendar_author_id INT(11),
			calendar_is_synchronizing ENUM('yes','no') DEFAULT 'no',
			calendar_last_synchronized DATETIME,
			calendar_created_on DATETIME,
			calendar_modified_on DATETIME,
			PRIMARY KEY  (calendar_id)
		);";
        dbDelta($calendar_table_sql);
    }

    function getAllCalendar() {
        global $wpdb;
        $sql = "select calendar_id from {$this->table_name}";
        $cals = $wpdb->get_results($sql);
        $arr = array();
        foreach($cals as $cal) {
            $arr[] = $this->getCalendar($cal->calendar_id);
        }
        return $arr;
    }

    function saveCalendar($data) {
        global $wpdb;
        $data['calendar_links'] = json_encode($data['calendar_links']);
        $data['calendar_layout_options'] = json_encode($data['calendar_layout_options']);
        $calendar_id = $data['calendar_id'];
        if($data['calendar_id']>0) {
            $cal_data = $this->getCalendar($data['calendar_id']);

            if(!isset($data['calendar_is_synchronizing']))
                $data['calendar_is_synchronizing']=$cal_data->calendar_is_synchronizing;
            if(!isset($data['calendar_last_synchronized']))
                $data['calendar_last_synchronized']=$cal_data->calendar_last_synchronized;

            $sql = "update {$this->table_name} set calendar_name='{$data['calendar_name']}', calendar_links='{$data['calendar_links']}', calendar_layout_options='{$data['calendar_layout_options']}', calendar_is_synchronizing = '{$data['calendar_is_synchronizing']}', calendar_last_synchronized='{$data['calendar_last_synchronized']}', calendar_modified_on='{$data['calendar_modified_on']}' where calendar_id='{$data['calendar_id']}';";
        }
        else {
            $sql = "insert into {$this->table_name} (calendar_name, calendar_links, calendar_layout_options, calendar_author_id, calendar_created_on, calendar_modified_on) values ('{$data['calendar_name']}', '{$data['calendar_links']}', '{$data['calendar_layout_options']}', '{$data['calendar_author_id']}', '{$data['calendar_created_on']}', '{$data['calendar_modified_on']}');";
        }
        $wpdb->query($sql);
        if($data['calendar_id']<=0)
            $calendar_id = $wpdb->insert_id;
        return true;
    }

    function deleteCalendar($calendar_id) {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        global $wpdb;
        $sql = "delete from {$this->table_name} where calendar_id='{$calendar_id}'";
        $wpdb->query($sql);

        $VRCalendarBooking->removeCalendarBookings($calendar_id);
        return true;
    }

    function getCalendar($calendar_id) {
        global $wpdb;
        $sql = "select * from {$this->table_name} where calendar_id='{$calendar_id}'";
        $data = $wpdb->get_row($sql);
        if(isset($data->calendar_id)) {
            $data->calendar_layout_options = json_decode($data->calendar_layout_options, true);
            $data->calendar_links = json_decode($data->calendar_links);
        }

        return $data;
    }

    function getEmptyCalendar() {
        $calendar = new stdClass();
        $calendar->calendar_id = '';
        $calendar->calendar_name = '';
        $calendar->calendar_links = array(
            ""
        );
        $calendar->calendar_layout_options = array (
            'columns' => '3',
            'rows' => '4',
            'size' => 'small',
            'default_bg_color' => '#FFFFFF',
            'default_font_color' => '#000000',
            'calendar_border_color' => '#CCCCCC',
            'week_header_bg_color' => '#F1F0F0',
            'week_header_font_color' => '#000000',
            'available_bg_color' => '#DDFFCC',
            'available_font_color' => '#000000',
            'unavailable_bg_color' => '#FFC0BD',
            'unavailable_font_color' => '#000000',
        );
        return $calendar;
    }

    function synchronizeCalendar ($calendar_id) {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $cal = $this->getCalendar($calendar_id);
        $calData =  json_decode(json_encode($cal), true);
        $calData['calendar_is_synchronizing'] = 'yes';
        $this->saveCalendar( $calData );
        $calLinks = $cal->calendar_links;

        /* First remove all bookings except local bookings */
        $VRCalendarBooking->removeBookingsExceptLocal($cal->calendar_id);
        foreach($calLinks as $calLink) {
            $ical   = new VRCICal($calLink);
            $events = $ical->events();

            foreach($events as $event) {
                $booking_data = array(
                    'booking_calendar_id'=>$cal->calendar_id,
                    'booking_source'=>$calLink,
                    'booking_date_from'=>date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event['DTSTART']) ),
                    'booking_date_to'=>date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event['DTEND']) ),
                    'booking_guests'=>'',
                    'booking_user_fname'=>'',
                    'booking_user_lname'=>'',
                    'booking_user_email'=>'',
                    'booking_summary'=>$event['SUMMARY'],
                    'booking_status'=>'confirmed',
                    'booking_payment_status'=>'confirmed',
                    'booking_admin_approved'=>'yes',
                    'booking_payment_data'=>'',
                    'booking_sub_price'=>array(),
                    'booking_total_price'=>0,
                    'booking_created_on'=>date('Y-m-d H:i:s'),
                    'booking_modified_on'=>date('Y-m-d H:i:s'),
                );
                $VRCalendarBooking->saveBooking( $booking_data );
            }
        }
        $calData['calendar_is_synchronizing'] = 'no';
        $calData['calendar_last_synchronized'] = date('Y-m-d H:i:s');
        $this->saveCalendar( $calData );
    }

    function getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date) {
        global $wpdb;
        /* Check if a price variation is available in b/w these dates */
        $sql = "select * from {$this->price_variation_table_name} where calendar_id={$cal_id} and(
        date(variation_start_date) BETWEEN date('{$check_in_date}') and date('{$check_out_date}') OR
        date(variation_end_date) BETWEEN date('{$check_in_date}') and date('{$check_out_date}') OR
        date('{$check_in_date}') BETWEEN date(variation_start_date) and date(variation_end_date) )";

        $price_variations = $wpdb->get_results($sql);
        return $price_variations;
    }

    function calculateNights($check_in_date, $check_out_date) {
        return ceil(abs(strtotime($check_out_date) - strtotime($check_in_date)) / 86400);
    }

    function getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations) {

        if(count($price_variations)<=0)
            return $cal_data->calendar_price_per_night;

        /* We have some variations */
        $dates = array();
        for($date = $check_in_date; $date<$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) {
            $dates[$date] = $cal_data->calendar_price_per_night;
        }
        /* Now update this array to get price from a variation */
        foreach($dates as $date=>$price) {
            //$price = $cal_data->calendar_price_per_night;
            foreach($price_variations as $variation) {
                if( strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) {
                    $price = $variation->variation_price_per_night;
                    break;
                }
            }
            $dates[$date] = $price;
        }
        return number_format((float)(array_sum($dates)/count($dates)), 2, '.', '');
    }

    function getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations) {
        $booking_days = $this->calculateNights($check_in_date, $check_out_date);
        $price_per_day = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
        return $booking_days*$price_per_day;

    }

    function getBookingPrice($cal_id, $check_in_date, $check_out_date) {
        global $wp_db;
        $cal_data = $this->getCalendar($cal_id);

        $price_variations = $this->getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date);

        $booking_days = $this->calculateNights($check_in_date, $check_out_date);

        $price_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);

        //$base_booking_price = $this->getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations);
        $base_booking_price = $booking_days*$price_per_night;

        $cleaning_fee = $cal_data->calendar_cfee_per_stay;
        $booking_price_without_taxes = $base_booking_price+$cleaning_fee;

        $tax = $cal_data->calendar_tax_per_stay;
        $tax_type = $cal_data->calendar_tax_type;
        $tax_amt = $tax;

        if($tax_type == 'percentage')
            $tax_amt = ($base_booking_price*$tax)/100;

        $booking_price_with_taxes =  $booking_price_without_taxes+$tax_amt;

        return array(
            'booking_days'=>$booking_days,
            'price_per_night'=>$price_per_night,
            'base_booking_price'=>$base_booking_price,
            'cleaning_fee'=>$cleaning_fee,
            'booking_price_without_taxes'=>$booking_price_without_taxes,
            'tax'=>$tax,
            'tax_type'=>$tax_type,
            'tax_amt'=>$tax_amt,
            'booking_price_with_taxes'=>$booking_price_with_taxes
        );

    }

}