jQuery(document).ready(function($) {
	/**
	 * Add the onclick actions.
	 */
	$(document)
		.on('click', '.supv-notice .notice-dismiss', function() {
			const classes  = $(this).closest('.supv-notice').attr('class');
			const software = classes.match(/supv-notice-(?:ssl|https)\s/)[0].replace('supv-notice-', '');

			supv_do_ajax('supv_hide_admin_notice', {'software': software}, false);
		})
});

/**
 * Run the AJAX requests.
 *
 * @param {string} action
 * @param {Object} params
 * @param {string|false} target
 */
function supv_do_ajax(action, params, target) {
	var data = {
		'action': action
	};

	if (jQuery('#' + action + '_wpnonce').length) {
		_wpnonce = jQuery('#' + action + '_wpnonce').val();

		data = jQuery.extend(data, {'_wpnonce': _wpnonce});
	}

	if (typeof params === 'object') {
		data = jQuery.extend(data, params);
	}

	if (typeof target === 'string') {
		target = '#' + target;

		if (jQuery(target).length) {
			target = jQuery(target);
		} else {
			target = false;
		}
	}

	jQuery.ajax({
		method: 'POST',
		url: ajaxurl,
		data: data,
		beforeSend: function() {
			if (target) {
				target.html('<p class="supv_loading">Loading...</p>');
			}
		}
	})
		.done(function(response) {
			if (target) {
				target.html(response);
			}
		})
		.fail(function() {
			console.log('Supervisor: Unable to process the AJAX request for ' + action + '.');
		});
}
