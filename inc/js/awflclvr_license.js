if (typeof window.awflclvr_license_update === 'undefined') {
	jQuery(function($) {
		$('.awflclvr_license_button').click(function () {
			var license = $(this).parent().parent().find('.awflclvr_license_key').val();
			var pid 	= $(this).data('pid');
			var url 	= $(this).data('url');
			var site 	= $(this).data('site');
			var action	= $(this).data('action');
			var slug	= $(this).data('slug');
			var nonce 	= $(this).data('nonce');
			var $this 	= $(this);
			
			$.ajax({
				type: 'GET',
				url: url,
				data: {
					edd_action: action,
					item_id: pid,
					license: license,
					url: site
				},
				cache: false,
				success: function (response) {
					var new_action 	= 'activate_license';
					var new_label 	= 'Activate License';
					var new_status 	= 'Inactive';
					var message		= 'License successfully deactivated.';
					if (response.success == true) {
						if (response.license == 'valid') {
							new_action 	= 'deactivate_license';
							new_label 	= 'Deactivate License';
							new_status 	= 'Active';
							message		= 'License successfully activated.';
						}
					} else {
						message = 'An error occurred, please try again.';
						if (response.error == 'expired') message = 'Your license key has expired.';
						if (response.error == 'revoked') message = 'Your license key has been disabled.';
						if (response.error == 'missing') message = 'Invalid license.';
						if (response.error == 'invalid') message = 'Your license is not active for this URL.';
						if (response.error == 'site_inactive') message = 'Your license is not active for this URL.';
						if (response.error == 'item_name_mismatch') message = 'This appears to be an invalid license key.';
						if (response.error == 'no_activations_left') message = 'Your license key has reached its activation limit.';
					}
					$this.val(new_label);
					$this.data('action', new_action);
					$this.parent().find('.awflclvr_license_status').html(new_status);
					$this.parent().find('.awflclvr_license_message').html(message);
					$.ajax({
						type: 'GET',
						url: ajaxurl,
						data: {
							action: 'awflclvr_license_update',
							license: license,
							status: new_status,
							slug: slug,
							_wpnonce: nonce
						},
						cache: false,
						success: function (response) {
							if (!response.success) $this.parent().find('.awflclvr_license_message').html('An error occurred, please try again.');
						},
						error: function () {
							$this.parent().find('.awflclvr_license_message').html('An error occurred, please contact Awful Clever customer support.');
						}
					});
				}
			});
		});
	});
	
	function awflclvr_license_update () {
		return true;
	}
}