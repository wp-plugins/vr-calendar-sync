<div id="vrc-payment-paypal">
    <form method="post" action="">
        <input type="hidden" name="vrc_pcmd" id="vrc_pcmd" value="paypalPayment" />
        <input type="hidden" name="bid" id="bid" value="<?php echo $data['booking_data']->booking_id; ?>" />
        <input type="submit" class="btn btn-primary" value="Pay via PayPal" />
    </form>
</div>
