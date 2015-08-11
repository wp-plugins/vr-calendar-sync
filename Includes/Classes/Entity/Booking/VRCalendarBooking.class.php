<?php
class VRCalendarBooking extends VRCSingleton {

    public $table_name;

    private $calendar_id;

    protected function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'vrcalandar_bookings';
    }

    function createTable() {
        global $wpdb;

        $calendar_table_sql = "CREATE TABLE {$this->table_name} (
            booking_id INT(11) NOT NULL AUTO_INCREMENT,
			booking_calendar_id INT(11),
			booking_source TEXT,
			booking_date_from DATETIME,
			booking_date_to DATETIME,
            booking_guests INT(11),
            booking_user_fname TEXT,
            booking_user_lname TEXT,
            booking_user_email TEXT,
            booking_summary TEXT,
            booking_status ENUM('pending','confirmed'),
            booking_payment_status ENUM('pending','confirmed','not_required'),
            booking_admin_approved ENUM('yes','no'),
            booking_payment_data TEXT,
            booking_sub_price TEXT,
            booking_total_price TEXT,
            booking_created_on DATETIME,
            booking_modified_on DATETIME,
			PRIMARY KEY  (booking_id)
		);";
        dbDelta($calendar_table_sql);
    }

    function saveBooking($data) {
        global $wpdb;
        $data['booking_sub_price'] = json_encode($data['booking_sub_price']);
        $data['booking_payment_data'] = json_encode($data['booking_payment_data']);

        $data['booking_summary'] = htmlentities($data['booking_summary'], ENT_QUOTES);

        if(@$data['booking_id']>0) {
            $sql = "update {$this->table_name} set booking_calendar_id='{$data['booking_calendar_id']}', booking_source='{$data['booking_source']}', booking_date_from='{$data['booking_date_from']}', booking_date_to='{$data['booking_date_to']}', booking_guests='{$data['booking_guests']}', booking_user_fname='{$data['booking_user_fname']}', booking_user_lname='{$data['booking_user_lname']}', booking_user_email='{$data['booking_user_email']}', booking_summary='{$data['booking_summary']}', booking_status='{$data['booking_status']}', booking_payment_status='{$data['booking_payment_status']}', booking_admin_approved='{$data['booking_admin_approved']}', booking_payment_data='{$data['booking_payment_data']}', booking_sub_price='{$data['booking_sub_price']}', booking_total_price='{$data['booking_total_price']}', booking_modified_on='{$data['booking_modified_on']}' where booking_id='{$data['booking_id']}';";
        }
        else {
            $sql = "insert into {$this->table_name} (booking_calendar_id, booking_source, booking_date_from, booking_date_to, booking_guests, booking_user_fname, booking_user_lname, booking_user_email, booking_summary, booking_status, booking_payment_status, booking_admin_approved, booking_payment_data, booking_sub_price, booking_total_price, booking_created_on, booking_modified_on) values ('{$data['booking_calendar_id']}', '{$data['booking_source']}', '{$data['booking_date_from']}', '{$data['booking_date_to']}', '{$data['booking_guests']}', '{$data['booking_user_fname']}', '{$data['booking_user_lname']}', '{$data['booking_user_email']}', '{$data['booking_summary']}', '{$data['booking_status']}', '{$data['booking_payment_status']}', '{$data['booking_admin_approved']}', '{$data['booking_payment_data']}', '{$data['booking_sub_price']}', '{$data['booking_total_price']}', '{$data['booking_created_on']}', '{$data['booking_modified_on']}');";
        }
        $wpdb->query($sql);

        if(@$data['booking_id']<=0 || !isset($data['booking_id']))
            return $wpdb->insert_id;

        return true;
    }

    function deleteBooking($booking_id) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_id='{$booking_id}'";
        $wpdb->query($sql);
        return true;
    }

    function getBookingByID($booking_id) {
        global $wpdb;
        $sql = "select * from {$this->table_name} where booking_id='{$booking_id}'";
        $data = $wpdb->get_row($sql);
        $data->booking_sub_price = json_decode($data->booking_sub_price, true);
        $data->booking_payment_data = json_decode($data->booking_payment_data, true);
        $data->booking_summary = html_entity_decode($data->booking_summary, ENT_QUOTES);
        return $data;
    }

    function getBookings($calendar_id) {
        global $wpdb;
        $sql = "select booking_id from {$this->table_name} where booking_calendar_id='{$calendar_id}'";
        $data = $wpdb->get_results($sql);

        $arr = array();

        foreach($data as $tdata) {
            $arr[] = $this->getBookingByID($tdata->booking_id);
        }
        return $arr;
    }

    function removeBookingsBySource($calendar_id, $source) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_calendar_id='{$calendar_id}' and booking_source='{$source}'";
        $wpdb->query($sql);
        return true;
    }

    function removeBookingsExceptLocal($calendar_id) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_calendar_id='{$calendar_id}' and booking_source != 'website'";
        $wpdb->query($sql);
        return true;
    }
    function removeCalendarBookings($calendar_id) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_calendar_id='{$calendar_id}'";
        $wpdb->query($sql);
        return true;
    }

    function availableTill( $cal_id, $start_date ) {
        $sql = "select date(booking_date_from) from {$this->table_name} where booking_calendar_id='{$cal_id}' and date(booking_date_from) > date('{$start_date}') order by booking_date_from asc limit 1";
        global $wpdb;
        $booking_range_ends = $wpdb->get_var($sql);
        /* Dec 1 date */
        if(!empty($booking_range_ends)) {
            //$booking_range_ends = date('Y-m-d', strtotime("-1 day", strtotime($booking_range_ends)) );
            $booking_range_ends = date('Y-m-d', strtotime($booking_range_ends) );
        }
        return $booking_range_ends;
    }

    function getBookedDates( $cal_data ) {
        global $wpdb;

        /* loop from first of this month to 36 months */
        $unavailable_dates = array();
        for($i=0; $i<36; $i++) {
            $next_date = date('Y-m-d', strtotime("+{$i} months"));
            $month = date('n', strtotime($next_date));
            $year =  date('Y', strtotime($next_date));
            $days_in_month = date('t',mktime(0,0,0,$month,1,$year));

            for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
                $cDate = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));
                if(!$this->isDateAvailable($cal_data, $cDate )) {
                    $unavailable_dates[] = $cDate;
                }
            }
        }
        return $unavailable_dates;
    }

    function isDateRangeAvailable($cal_data, $start_date, $end_date) {
        global $wpdb;
        $cdate = $start_date;
        /* check if start date is available */
        if($this->isStartEndDate($cal_data, $start_date )) {
            return false;
        }
        else if($this->isStartDate($cal_data, $start_date )) {
            return false;
        }
        else if(!$this->isDateAvailable($cal_data, $start_date)) {
            if(!$this->isEndDate($cal_data, $start_date )) {
                return false;
            }
        }
        while(strtotime($cdate)<=strtotime($end_date)) {
            if(!$this->isDateAvailable($cal_data, $cdate)) {
                return false;
            }
            $cdate = date('Y-m-d', strtotime("$cdate + 1 day"));
        }
        return true;
    }

    function isDateAvailable($cal_data, $date) {
        global $wpdb;
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and ( date(booking_date_from) BETWEEN date('{$date}') and date('{$date}') OR date(booking_date_to) BETWEEN date('{$date}') and date('{$date}') OR date('{$date}') BETWEEN date(booking_date_from) and date(booking_date_to) )";
        $booking_count = $wpdb->get_var($sql);
        if($booking_count>0) {
            if($this->isStartEndDate($cal_data, $date)) {
                return false;
            }
            if(!$this->isEndDate($cal_data, $date)) {
                return false;
            }
        }

        /* Dates in past are also not allowed */
        $current_data = date('Y-m-d');
        if(strtotime($date)<strtotime($current_data))
            return false;

        return true;
    }

    function isStartEndDate($cal_data, $date ) {
        if($this->isStartDate($cal_data, $date) && $this->isEndDate($cal_data, $date))
            return true;

        return false;
    }
    function isStartDate($cal_data, $date ) {
        global $wpdb;
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and ( date(booking_date_from) = date('{$date}') )";
        $booking_count = $wpdb->get_var($sql);
        if($booking_count>0)
            return true;

        return false;
    }
    function isEndDate($cal_data, $date ) {
        global $wpdb;
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and ( date(booking_date_to) = date('{$date}') )";
        $booking_count = $wpdb->get_var($sql);
        if($booking_count>0)
            return true;

        return false;
    }
}