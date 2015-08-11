<style>
    .ui-widget-header {
        background: none;
        border: none;
    }
    .ui-state-default, .ui-widget-content .ui-state-default,
    .ui-widget-header .ui-state-default {
        background: <?php echo $data['calendar_data']->calendar_layout_options['available_bg_color'] ?>;
        border: none;
    }
    .ui-state-disabled, .ui-widget-content .ui-state-disabled,
    .ui-widget-header .ui-state-disabled {
        opacity: 1;
        filter: Alpha(Opacity=100);
    }
    .ui-state-default, .ui-widget-content .ui-state-disabled .ui-state-default,
    .ui-widget-header .ui-state-disabled .ui-state-default {
        background: <?php echo $data['calendar_data']->calendar_layout_options['unavailable_bg_color'] ?>;
    }
</style>
<?php
/* Add calendar style */
?>
<div class="vrc" id="vrc-booking-form-wrapper">
    <form name="vrc-booking-form" id="vrc-booking-form" class="vrc-validate" method="post" action="">
        <div class="booking-heading clearfix">
                <div id="booking-price-per-night" class="pull-left">$<span id="price-per-night"><?php echo $data['booking_price']['price_per_night']; ?></span></div>
            <div class="pull-right">Per Night</div>
        </div>
        <div id="booking-form-fields">
            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_checkin_date required">Check In</label>
                        <input type="text" class="form-control required" name="booking_checkin_date" id="booking_checkin_date" readonly value="<?php echo $data['check_in_date'] ?>" placeholder="Check In Date">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_checkout_date required">Check Out</label>
                        <input type="text" class="form-control required" name="booking_checkout_date" id="booking_checkout_date" readonly value="<?php echo $data['check_out_date'] ?>" placeholder="Check Out Date">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_guests_count required">Guests</label>
                        <input type="number" min="1" max="10" class="form-control required" name="booking_guests_count" id="booking_guests_count" value="1" placeholder="Guests">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_first_name">First Name</label>
                        <input type="text" class="form-control required" name="user_first_name" id="user_first_name" placeholder="First name" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_last_name">Last Name</label>
                        <input type="text" class="form-control required" name="user_last_name" id="user_last_name" placeholder="Last name" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" class="form-control required " name="user_email" id="user_email" placeholder="Email" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="booking_note">Note To Host</label>
                        <textarea class="form-control" name="booking_note" id="booking_note" placeholder="Note To Host"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div id="booking-form-charges">
            <table class="table table-hover ">
                <tr>
                    <td>
                        $<span id="table-price-per-night"><?php echo $data['booking_price']['price_per_night']; ?></span> x <span id="table-booking-days"><?php echo $data['booking_price']['booking_days']; ?></span> nights
                    </td>
                    <td>
                        $<span id="table-base-booking-price"><?php echo $data['booking_price']['base_booking_price']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cleaning Fee
                    </td>
                    <td>
                        $<span id="table-cleaning-fee"><?php echo $data['booking_price']['cleaning_fee']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Taxes
                    </td>
                    <td>
                        $<span id="table-tax-amt"><?php echo $data['booking_price']['tax_amt']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Total
                    </td>
                    <td>
                        $<span id="table-booking-price-with-taxes"><?php echo $data['booking_price']['booking_price_with_taxes']; ?></span>
                    </td>
                </tr>
            </table>

        </div>
        <div id="booking-form-action">
            <div class="row">
                <div class="col-xs-12">
                    <input type="hidden" name="cal_id" id="cal_id" value="<?php echo $data['calendar_data']->calendar_id ?>">
                    <input type="hidden" id="booked_dates" value='<?php echo json_encode($data['booked_dates']); ?>'>
                    <input type="hidden" id="vrc_pcmd" name="vrc_pcmd" value='saveBooking'>
                    <input type="submit" class="btn btn-danger btn-lg col-xs-12" value="Request to Book" />
                </div>
            </div>
        </div>
    </form>
</div>