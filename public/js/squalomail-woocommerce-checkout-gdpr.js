(function( $ ) {
	'use strict';

	$( document ).ready(function() {
		var squalomail_woocommerce_newsletter = $('#squalomail_woocommerce_newsletter');
		var gdprFields = $('#squalomail-gdpr-fields');
		if (gdprFields.length) {
			showHideGDPR(squalomail_woocommerce_newsletter, gdprFields);
			
			squalomail_woocommerce_newsletter.change(function () {
				showHideGDPR(squalomail_woocommerce_newsletter, gdprFields);
			});
		}

	})
	function showHideGDPR(squalomail_woocommerce_newsletter, gdprFields) {
		if (squalomail_woocommerce_newsletter.prop('checked') == true) {
			gdprFields.slideDown();
		}
		else {
			gdprFields.slideUp();
		}
	}
})( jQuery );