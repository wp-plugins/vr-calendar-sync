<div class="vrc" id="vrc-payment-form-wrapper">
    <div id="vrc-payment-error" class="bg-danger hidden"></div>
    <?php if($data['cal_data']->calendar_payment_method == 'stripe' || $data['cal_data']->calendar_payment_method == 'both' ){
        include('Partial/Payment/Stripe.view.php');
    }?>
    <?php if($data['cal_data']->calendar_payment_method == 'paypal' || $data['cal_data']->calendar_payment_method == 'both' ){
        include('Partial/Payment/Paypal.view.php');
    }?>
</div>
