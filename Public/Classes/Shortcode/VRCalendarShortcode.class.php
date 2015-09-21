<?php
class VRCalendarShortcode extends VRCShortcode {

    protected $slug = 'vrcalendar';

    function shortcode_handler($atts, $content = "") {
        $this->atts = shortcode_atts(
            array(
                'id'=>false
            ),$atts, 'vrcalendar');

        if(!$this->atts['id'])
            return 'Calendar id is missing';

        $VRCalendarEntity = VRCalendarEntity::getInstance();

        $cal_data = $VRCalendarEntity->getCalendar( $this->atts['id'] );

        $calendar_html = $this->getCalendar($cal_data, 36);
        $calendar_css =$this->getCalendarCSS($cal_data);
        $uid = uniqid();
        $last_sync_date = date('F d, Y \a\t h:i a', strtotime($cal_data->calendar_last_synchronized));
        $output = <<<E
<div class="vrc vrc-calendar vrc-calendar-{$cal_data->calendar_layout_options['size']} vrc-calendar-id-{$cal_data->calendar_id}" id="vrc-calendar-uid-{$uid}">
    <div class=" calendar-header">
        <div>
            <div class="calendar-info">Calendar Updated on {$last_sync_date}</div>
            <div class="pull-left">
                <div class="calendar-legend">
                    <div class="day-number normal-day day_number_header"></div>
                    <div class="calendar-legend-text">Available&nbsp;&nbsp;</div>
                    <div class="day-number event-day day_number_header"></div>
                    <div class="calendar-legend-text">Unavailable</div>
                </div>
            </div>
            <div class="pull-right">
                <div class="button_calaner_header">
                    <div class="customNavigation">
                        <a class="btn-prev pull-left">Previous</a>
                        <a class="btn-next pull-right">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="calendar-slides">
        {$calendar_html}
    </div>
</div>
{$calendar_css}
E;
;

        return $output;
    }

    function getCalendarCSS($cal_data) {
        $style = <<<E
<style>
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .calendar-month-container {
        background:{$cal_data->calendar_layout_options['default_bg_color']};
        color:{$cal_data->calendar_layout_options['default_font_color']};
        border-color:{$cal_data->calendar_layout_options['calendar_border_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .calendar-month-container td {

    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day-head {
        background:{$cal_data->calendar_layout_options['week_header_bg_color']};
        color:{$cal_data->calendar_layout_options['week_header_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number,
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .day-number{
        background:{$cal_data->calendar_layout_options['available_bg_color']};
        color:{$cal_data->calendar_layout_options['available_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-day,
     .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .day-number.event-day{
        background:{$cal_data->calendar_layout_options['unavailable_bg_color']};
        color:{$cal_data->calendar_layout_options['unavailable_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-start {
        background: {$cal_data->calendar_layout_options['available_bg_color']}; /* Old browsers */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjZGRmZmNjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZGRmZmNjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZjMGJkIiBzdG9wLW9wYWNpdHk9IjEiLz4KICA8L2xpbmVhckdyYWRpZW50PgogIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZ3JhZC11Y2dnLWdlbmVyYXRlZCkiIC8+Cjwvc3ZnPg==);
        background: -moz-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['unavailable_bg_color']})); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* IE10+ */
        background: linear-gradient(135deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['available_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-end {
        background: {$cal_data->calendar_layout_options['unavailable_bg_color']}; /* Old browsers */
        /* IE9 SVG, needs conditional override of 'filter' to 'none' */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2ZmYzBiZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RkZmZjYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkZGZmY2MiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
        background: -moz-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(50%,{$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(100%,{$cal_data->calendar_layout_options['available_bg_color']})); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']}c 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* IE10+ */
        background: linear-gradient(135deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['available_bg_color']}',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.start-end-day {
        background: {$cal_data->calendar_layout_options['unavailable_bg_color']};
        background: -moz-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -webkit-gradient(left top, right bottom, color-stop(0%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(46%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(47%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(54%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(55%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(100%, {$cal_data->calendar_layout_options['unavailable_bg_color']}));
        background: -webkit-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -o-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -ms-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: linear-gradient(135deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        color: #000000 !important;
        /* filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['available_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}',GradientType=1 ); */
    }
</style>
E;
        return $style;
    }

    function getCalendar($cal_data, $months) {
        $calendar_html = '';

        $months_per_page =  $cal_data->calendar_layout_options['rows'] * $cal_data->calendar_layout_options['columns'];
        $pages = ceil($months/$months_per_page);
        $next_month = 0;
        $page = 0;
        while($page<$pages) {
            $calendar_html .= '<div class="calendar-page">';
            for($row=1; $row<=$cal_data->calendar_layout_options['rows'] && $next_month<=$months; $row++) {
                $calendar_html .= '<div class="row">';
                for($col=1; $col<=$cal_data->calendar_layout_options['columns'] && $next_month<=$months; $col++) {

                    $next_data = date('Y-m-d', strtotime("+{$next_month} months"));
                    $month = date('n', strtotime($next_data));
                    $year =  date('Y', strtotime($next_data));

                    $col_class = floor(12/$cal_data->calendar_layout_options['columns']);

                    $calendar_html .= '<div class="col-md-'.$col_class.'">';
                    $calendar_html .= $this->getMonthCalendar($cal_data, $month, $year);
                    $calendar_html .= '</div>';
                    $next_month++;
                }
                $calendar_html .= '</div>';
            }
            $calendar_html .= '</div>';
            $page++;
        }
        return $calendar_html;
    }

    function getMonthCalendar($cal_data, $month, $year) {

        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $month_name = date('F', strtotime("{$year}-{$month}-1"));
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        /* table headings */
        $headings = array(
            'S',
            'M',
            'T',
            'W',
            'T',
            'F',
            'S'
        );
        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w',mktime(0,0,0,$month,1,$year));
        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $cDate = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));
            if($VRCalendarBooking->isStartEndDate($cal_data, $cDate )) {
                $booked_class = 'start-end-day';
            }
            else if($VRCalendarBooking->isStartDate($cal_data, $cDate )) {
                $booked_class = 'event-start';
            }
            else if($VRCalendarBooking->isEndDate($cal_data, $cDate )) {
                $booked_class = 'no-event-day event-end';
            }
            else if( $VRCalendarBooking->isDateAvailable($cal_data, $cDate )) {
                $booked_class = 'no-event-day';
            }
            else {
                $booked_class = 'event-day';
            }
            //$booked_class = $VRCalendarBooking->isDateAvailable($cal_data, $cDate )?'no-event-day':'event-day';



            $calendar.= '<td class="calendar-day">';
            /* add in the day number */
            $calendar.= '<div class="day-number '.$booked_class.'" data-calendar-id="'.$cal_data->calendar_id.'" data-booking-date="'.$cDate.'">'.$list_day.'</div>';

            $calendar.= '</td>';
            if($running_day == 6):
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if($days_in_this_week < 8 && $days_in_this_week>1):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';


        $result = <<<E
<div class="calendar-month-container">
    <div class="calendar-month-name">{$month_name} {$year}</div>
    {$calendar}
</div>
E;
        return $result;
    }

}