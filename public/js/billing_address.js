function billing_address_changed() {
    if($('#billing_address_chb').get(0).checked != 0){
        $('#billing_address_ul')
            .css('display', "block");
        $('#billing_address')
            .attr('required', 'required');

    }
    else{
        $('#billing_address_ul')
            .css('display', "none");
        $('#billing_address')
            .attr('required', false);

    }
}