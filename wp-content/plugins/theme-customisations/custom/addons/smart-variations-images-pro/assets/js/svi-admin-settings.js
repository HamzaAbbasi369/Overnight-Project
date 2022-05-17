if (!WOOSVIADM) {
    var WOOSVIADM = {}
} else {
    if (WOOSVIADM && typeof WOOSVIADM !== "object") {
        throw new Error("WOOSVIADM is not an Object type")
    }
}
WOOSVIADM.isLoaded = false;
WOOSVIADM.STARTS = function ($) {
    return{NAME: "Application initialize module", VERSION: 1.0, init: function () {
            if ($('body').hasClass('woocommerce_page_woocommerce_svi')) {
                this.loadInits();
                this.licenseOptions();
            }
        },
        loadInits: function () {
            $('input#columns').attr('type', 'number').attr('min', 1).attr('max', 10);
        },
        licenseOptions: function () {
            $('form#woosvi_license').find('.fa-refresh').hide().removeClass('hidden');
            $("form#woosvi_license").submit(function (event) {
                event.preventDefault();

                $('span.submittext').fadeIn().html('Validating...');
                 $('form#woosvi_license').find('i.fa-refresh').fadeIn();

                jQuery.ajax({
                    url: ajaxurl,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'woosvi_licenseverify',
                        data: $('form#woosvi_license').serialize()
                    },
                    success: function (response) {
                        $('form#woosvi_license').find('.fa-refresh').fadeOut(function () {
                            if (response.status) {
                                $('form#woosvi_license').prepend('<div class="alert alert-dismissible alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + response.message + '. Page wil reload shorthly.</div>');
                                $('span.submittext').fadeIn().html('Saved');
                                setTimeout(function () {
                                    $('.alert-success').slideUp('fast').fadeOut(function () {
                                        window.location.reload();
                                        /* or window.location = window.location.href; */
                                    });
                                }, 2500);
                            } else {
                                $('span.submittext').fadeIn().html('Validate');
                                $('form#woosvi_license').prepend('<div class="alert alert-dismissible alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + response.message + '</div>');
                            }
                        });
                    }
                });
            })
        }
    }
}(jQuery);
jQuery(document).ready(function () {
    WOOSVIADM.STARTS.init();
});