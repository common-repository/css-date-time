jQuery(function($) {
	
	if (typeof css_date_time.js_parse != 'undefined') {
		if (css_date_time.js_parse == true) {
			var _d = new Date();
				var Y = _d.getFullYear();
				var m = ('0' + (_d.getMonth() + 1)).slice(-2);
				var d = ('0' + _d.getDate()).slice(-2);
				var H = ('0' + _d.getHours()).slice(-2);
				var i = ('0' + _d.getMinutes()).slice(-2);
				var s = ('0' + _d.getSeconds()).slice(-2);
			var post_data 		= {};
			post_data.action 	= 'css_date_time_retrieve_data';
			post_data.datetime 	= Y + '-' + m + '-' + d + ' ' + H + ':' + i + ':' + s;
			post_data.uncache 	= _d.valueOf();
			post_data.nonce		= css_date_time.nonce;
			
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: css_date_time.ajaxurl,
				data: post_data,
				cache: false,
				success: function (data) {
					if (data.success) {
						if (data.css != '') $('body').addClass(data.css);
					}
				}
			});
		}
	}
	
});

