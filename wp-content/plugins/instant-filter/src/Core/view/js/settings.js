jQuery(document).ready(function ($) {
    $('#run_data_sync').click(function () {
        var data = {
            'action': 'ong_data_sync',
            'step_nonce': $('#step_nonce').val(),
            'step': 1
        };

        jQuery.post(ajaxurl, data, function (response) {
            alert('Got this from the server: ' + response);
        });
    });
});