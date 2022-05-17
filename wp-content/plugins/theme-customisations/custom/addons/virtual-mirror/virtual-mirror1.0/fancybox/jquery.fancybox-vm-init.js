jQuery(document).ready(function() {
	if (jQuery('body').innerWidth() > 531) {
		jQuery('a#vmirror').fancybox({
			type: 'iframe',
			width: 482,
			height: 580,
			openEffect: 'elastic',
			closeEffect: 'elastic',
			padding: 4,
			fitToView: false,
			autoSize: false,
			scrolling: 'no',
		
			helpers: {
				overlay: {
					css: {'background': 'rgba(95, 95, 95, 0.3)'}
				}
			}	
		});
		
	} else {
		jQuery('a#vmirror').click(function(e) {
			e.stopImmediatePropagation();
			let c = jQuery(this).attr('href').split('?')[1];
			window.open('/virtual-mirror/fancybox/basic_vm.html?' + c);
			return false;
		});
	}
});

