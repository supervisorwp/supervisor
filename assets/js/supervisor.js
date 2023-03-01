jQuery( document ).ready( function( $ ) {
	/**
	 * Add the onclick actions.
	 */
	$( document )
		.on('click', '.supv-notice .notice-dismiss', function() {
			const classes  = $( this ).closest( '.supv-notice' ).attr( 'class' );
			const software = classes.match(/supv-notice-(?:ssl|https)\s/)[0].replace( 'supv-notice-', '' );

			supv_do_ajax( 'supv_hide_admin_notice', { 'software': software }, false );
		} )
		.on( 'click', '#supv-btn-transients-clear', function() {
			supv_do_ajax( 'supv_transients_cleanup', null, '#supv-transients-stats' );
		} )
		.on( 'click', '#supv-btn-autoload-options', function () {
			$( '#supv-autoload-result' ).hide();

			if ( $( '#supv-autoload-options' ).html().trim() === '' ) {
				supv_do_ajax( 'supv_autoload_options_list', null, '#supv-autoload-options' );
			} else {
				$( '#supv-autoload-options' ).html( '' );
			}
		} )
		.on( 'click', '#supv-btn-autoload-history', function () {
			$( '#supv-autoload-result' ).hide();

			supv_do_ajax( 'supv_autoload_options_history', null, '#supv-autoload-options' );
		} )
		.on( 'click', '#supv-btn-autoload-close', function() {
			$( '#supv-autoload-result' ).hide();

			$( '#supv-autoload-options' ).html( '' );
		} );

	/**
	 * Add the onchange actions.
	 */
	$( document )
		.on( 'change', '#supv-wordpress-update-policy', function () {
			supv_do_ajax( 'supv_wordpress_auto_update_policy', { 'wp_auto_update_policy': $( '#supv-wordpress-update-policy' ).find( ':selected' ).val() }, '#supv-wordpress-update-policy-box' );
		} );

	/**
	 * Add the onsubmit actions.
	 */
	$( document )
		.on( 'submit', '#supv-autoload-form', function() {
			let data   = $( '#supv-autoload-form' ).serializeArray();
			let params = {};

			$( data ).each( function( i, field ) {
				params[ field.name ] = field.value;
			} );

			$( '#supv-autoload-result' ).show();

			supv_do_ajax( 'supv_autoload_update_option', params, '#supv-autoload-stats' );

			return false;
		} );
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
				target.html('<p class="supv_loading"><img src="/wp-includes/images/spinner.gif"> ' + supv.loading + '</p>');
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
