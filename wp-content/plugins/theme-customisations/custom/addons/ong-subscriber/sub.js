jQuery(document).ready(function($) {

    $(".m-kontent-item--wrap-sub").on('click', function(e){
        e.preventDefault();
        var $this = $(this);
        $this.css('display', 'none');
        $('#form--subscribe-wrap').css('display', 'block');
    });

    jQuery('#form-name').attr('value', sub_ajax.user.Name);
    jQuery('#form-email').attr('value', sub_ajax.user.Email);

    $('#sub_submit').on('click', function () {

        var name = $("#form-name").val();
        var email = $("#form-email").val();

        jQuery.ajax({
            type: "POST",
            url: sub_ajax.url,
            data: {
                ajax_rx_sub_formName: name,
                ajax_rx_sub_formEmail: email,
                ajax_rx_sub_nonce: sub_ajax.nonce,
                action: 'sub_ajax_action'
            },

            success: function(res){

                $('.form-error-message').html(res);
                if(res === 'Subscribed!' || res === 'You are already subscribed!'){

                    $('#sub_form_subscriber').empty();
                    $('#sub-by-email').empty();
                    $('#sub-by-email').text(res);

                    setTimeout(function(){
                        $('.m-kontent-item--wrap-sub').css('display', 'block');
                    }, 2000);
                }
            },

            error: function(){
                alert('Error!');
            }

        });
        return false;
    });



    $('#unsubsubmit').on('click', function () {

        var text = $("#unsub_text").val();
        var nonce = $("#data").val();
        var email = $("#email").val();

            jQuery.ajax({
            type: "POST",
            url: sub_ajax.url,
            data: {
                ajax_rx_un_sub_formEmail: email,
                ajax_rx_un_sub_formText: text,
                ajax_rx_un_sub_formNonce: nonce,
                action: 'rx_sub_unsubscribe'
            },
            beforeSend: function(){
                // alert('beforeSend');
                jQuery('#unsub_res').empty();
                jQuery('#loader').fadeIn();

                jQuery('#unsub_text').attr('disabled', 'disabled');

            },
            success: function(res){
                // alert('success');
                jQuery('#unsub_text').removeAttr("disabled");

                jQuery('#loader').fadeOut(900, function(){
                    jQuery('#unsub_res').text(res);
                    if(res === 'You unsubscribe'){
                        jQuery("#unsub_form_subscriber").remove();
                    }
                });
            },
            error: function(){
                alert('Error!');
            }
        });
    });

});
