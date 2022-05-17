var Upload = function (file) {
	this.file = file;
};
Upload.prototype.getType = function() {
	return this.file.type;
};
Upload.prototype.getSize = function() {
	return this.file.size;
};
Upload.prototype.getName = function() {
	return this.file.name;
};
Upload.prototype.doUpload = function (file_num) {
    var dataurl = null;
    var filesToUpload = document.getElementById('alg_checkout_files_upload_1').files;
    var file = filesToUpload[0];
    var file2 = this.file;
    var thename = this.getName();
    var do_resize = 0;

    var img = document.createElement("img");
    var reader = new FileReader();

    // Set the image once loaded into file reader
    console.log("start reader");
    reader.onload = function(e)
    {
	img.src = e.target.result;

	//console.log("Img src: "+img.src);
        img.onload = function () {
            var canvas = document.createElement("canvas");
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0);

		
            var MAX_WIDTH = 1920;
            var MAX_HEIGHT = 1080;
            var width = img.width;
            var height = img.height;

	    //console.log("Current image size: width: "+width+ " " + height);

            if (width > height) {
              if (width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
		do_resize = 1;
              }
            } else {
              if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
		do_resize = 1;
              }
            }

	    if (do_resize == 1) {
		    canvas.width = width;
		    canvas.height = height;
		    console.log("Resize image to:" + "width: "+ width + "height: " +height);
		    var ctx = canvas.getContext("2d");
		    ctx.drawImage(img, 0, 0, width, height);

		    dataurl = canvas.toDataURL("image/jpeg");

			var blobBin = atob(dataurl.split(',')[1]);
			var array = [];
			for(var i = 0; i < blobBin.length; i++) {
			  array.push(blobBin.charCodeAt(i));
			}
			file2 = new Blob([new Uint8Array(array)], {type: 'image/png', name: thename});
		   }
	    }
     	}
	reader.readAsDataURL(file);
	//console.log("Upload via ajax");

	var formData = new FormData();
	formData.append("file", file2, this.getName());
	formData.append("action", "wcj_ajax_file_upload");
	formData.append("file-num", file_num);
	formData.append("order_id", jQuery("#alg_checkout_files_upload_order_id_"+file_num).val());

	jQuery("#alg_checkout_files_upload_"+file_num+"_label").hide();
    	jQuery("#alg-wc-checkout-files-upload-progress-wrapper-"+file_num).show();

	jQuery.ajax({
		type: "POST",
		url: ajax_object.ajax_url,
		xhr: function () {
			var xhr = jQuery.ajaxSettings.xhr();
			if (alg_wc_checkout_files_upload.progress_bar_enabled) {
				if (xhr.upload) {
					xhr.upload.file_num = file_num;
					xhr.upload.addEventListener('progress', function(evt){
						if (evt.lengthComputable) {
							var percentComplete = Math.floor(evt.loaded / evt.total * 100);
							var progress_bar_id = "#alg-wc-checkout-files-upload-progress-wrapper-"+event.target.file_num;
							jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-bar").css("width", +percentComplete + "%");
							jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-status").text(percentComplete + "%");
						}
					}, false);
				}
			}
			return xhr;
		},
		success: function (data) {
			var data_decoded = jQuery.parseJSON(data);
			if ( 0 != data_decoded['result'] ) {
                jQuery("#alg-wc-checkout-files-upload-progress-wrapper-"+file_num).hide();
                //jQuery("#alg_checkout_files_upload_"+file_num).hide();
                jQuery("#alg_checkout_files_upload_"+file_num+"_label").hide();
				jQuery("#alg_checkout_files_upload_result_"+file_num).show();
				jQuery("#alg_checkout_files_upload_result_file_name_"+file_num).html(data_decoded['data']);
			} else {
				jQuery("#alg_checkout_files_upload_form_"+file_num)[0].reset();
				jQuery("#alg_checkout_files_upload_result_file_name_"+file_num).text("");
				if (alg_wc_checkout_files_upload.progress_bar_enabled) {
					var progress_bar_id = "#alg-wc-checkout-files-upload-progress-wrapper-"+file_num;
					jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-bar").css("width", "0%");
					jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-status").text("0%");
				}
			}
			if (''!=data_decoded['message']) {
				alert(data_decoded['message']);
			}
		},
		error: function (error) {},
		async: true,
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		timeout: 60000
	});
};

jQuery(document).ready(function() {
	jQuery(".alg_checkout_files_upload_file_input").on("change", function (e) {
		var file = jQuery(this)[0].files[0];
		var upload = new Upload(file);
		var max_file_size = parseInt(alg_wc_checkout_files_upload.max_file_size);
		if (max_file_size > 0 && upload.getSize() > max_file_size) {
			alert(alg_wc_checkout_files_upload.max_file_size_exceeded_message);
			jQuery("#alg_checkout_files_upload_form_"+jQuery(this).attr('file-num'))[0].reset();
		} else {
			upload.doUpload(jQuery(this).attr('file-num'));
		}
	});
	jQuery(".alg_checkout_files_upload_result_delete").on("click", function (e) {
		e.preventDefault();
		var file_num = jQuery(this).attr('file-num');
		var formData = new FormData();
		formData.append("action", "wcj_ajax_file_delete");
		formData.append("file-num", file_num);
		formData.append("order_id", jQuery("#alg_checkout_files_upload_order_id_"+file_num).val());
		jQuery.ajax({
			type: "POST",
			url: ajax_object.ajax_url,
			success: function (data) {
				var data_decoded = jQuery.parseJSON(data);
				if ( 0 != data_decoded['result'] ) {
					jQuery("#alg_checkout_files_upload_form_"+file_num)[0].reset();
					//jQuery("#alg_checkout_files_upload_"+file_num).show();
                    			jQuery("#alg_checkout_files_upload_"+file_num+"_label").show();
					jQuery("#alg_checkout_files_upload_result_"+file_num).hide();
					jQuery("#alg_checkout_files_upload_result_file_name_"+file_num).text("");
					if (alg_wc_checkout_files_upload.progress_bar_enabled) {
						var progress_bar_id = "#alg-wc-checkout-files-upload-progress-wrapper-"+file_num;
						jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-bar").css("width", "0%");
						jQuery(progress_bar_id + " .alg-wc-checkout-files-upload-progress-status").text("0%");
					}
				}
				if (''!=data_decoded['message']) {
					alert(data_decoded['message']);
				}
			},
			error: function (error) {},
			async: true,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			timeout: 60000
		});
	});
});
