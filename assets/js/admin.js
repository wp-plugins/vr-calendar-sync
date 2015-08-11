jQuery(document).ready( function ($){
    if($('#custom-price tbody tr').length <=0 ) {
        $('#custom-price').hide();
    }
    $('.nav-tab-wrapper .nav-tab').bind('click', function(e){
        e.preventDefault();
        var tabs_parent = $(this).parent().parent('.tabs-wrapper');
        jQuery('.nav-tab-wrapper .nav-tab', tabs_parent).removeClass('nav-tab-active');
        jQuery('.tabs-content-wrapper .tab-content', tabs_parent).removeClass('tab-content-active');

        jQuery(jQuery(this).attr('href')).addClass('tab-content-active');
        jQuery(this).addClass('nav-tab-active');
    });
    $(document).on('click', '.vrc-remove-link', function(e){
        e.preventDefault();
        if(confirm('Are you sure?'))
            $(this).parent().parent().remove();

    });
    $('#add-more-calendar-links').bind('click', function (e){
        e.preventDefault();
        if($('#calendar-links .calendar_link_row').length>=3) {
            alert('Max 3 links are allowed in free version\nUpgrade to the PRO or ENTERPRISE version to add more .ics links');
            return;
        }
        var cloned_row = $('#calendar-links-cloner tr').clone( true );
        $('#calendar-links').append(cloned_row);
    });

    try {
        initDatePicker();
        $('.vrc-color-picker').wpColorPicker();
    } catch(e){

    }
});
function initDatePicker() {
    jQuery('.vrc-calendar').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y"
    });
}