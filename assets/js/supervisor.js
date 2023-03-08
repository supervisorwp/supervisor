'use strict';

const Supervisor = ( function( document, $ ) {
	const app = {};

	/**
	 * Init.
	 *
	 * @since {VERSION}
	 */
	app.init = function() {
		$( document ).ready( app.ready );
	};

	/**
	 * Document ready.
	 *
	 * @since {VERSION}
	 */
	app.ready = function() {
		app.events();
	};

	/**
	 * Events.
	 *
	 * @since {VERSION}
	 */
	app.events = function() {
		/**
		 * Add the onclick events.
		 */
		$( document )
			.on( 'click', '.supv-notice .notice-dismiss', function() {
				const classes = $( this ).closest( '.supv-notice' ).attr( 'class' );
				const software = classes.match( /supv-notice-(?:ssl|https)\s/ )[ 0 ].replace( 'supv-notice-', '' );

				app.ajax_request( 'supv_hide_admin_notice', { software }, false );
			} )
			.on( 'click', '#supv-btn-transients-clear', function() {
				app.ajax_request( 'supv_transients_cleanup', null, '#supv-transients-stats' );
			} )
			.on( 'click', '#supv-btn-autoload-options', function() {
				$( '#supv-autoload-result' ).hide();

				if ( $( '#supv-autoload-options' ).html().trim() === '' ) {
					app.ajax_request( 'supv_autoload_options_list', null, '#supv-autoload-options' );
				} else {
					$( '#supv-autoload-options' ).html( '' );
				}
			} )
			.on( 'click', '#supv-btn-autoload-history', function() {
				$( '#supv-autoload-result' ).hide();

				app.ajax_request( 'supv_autoload_options_history', null, '#supv-autoload-options' );
			} )
			.on( 'click', '#supv-btn-autoload-close', function() {
				$( '#supv-autoload-result' ).hide();

				$( '#supv-autoload-options' ).html( '' );
			} )
			.on( 'click', '#supv-secure-login-restrict-attempts-switch', function() {
				if ( $( this ).is( ':checked' ) ) {
					app.ajax_request( 'supv_secure_login_restrict_attempts_settings_output', null, '#supv-secure-login-restrict-attempts-settings' );
				} else {
					$( '#supv-secure-login-restrict-attempts-settings' ).empty();
				}
			} );

		/**
		 * Add the onchange events.
		 */
		$( document )
			.on( 'change', '#supv-wordpress-update-policy', function() {
				app.ajax_request( 'supv_wordpress_auto_update_policy', { wp_auto_update_policy: $( '#supv-wordpress-update-policy' ).find( ':selected' ).val() }, '#supv-wordpress-update-policy-box' );
			} );

		/**
		 * Add the onsubmit events.
		 */
		$( document )
			.on( 'submit', '#supv-autoload-form', function() {
				const data = $( '#supv-autoload-form' ).serializeArray();
				const params = {};

				$( data ).each(
					function( i, field ) {
						params[ field.name ] = field.value;
					}
				);

				$( '#supv-autoload-result' ).show();

				app.ajax_request( 'supv_autoload_update_option', params, '#supv-autoload-stats' );

				return false;
			} );

		/**
		 * Adds the onfocus events.
		 */
		$( document )
			.on( 'focus', '.supv-secure-login-restrict-attempts-settings > ul > li > input', function() {
				$( '.supv-secure-login-restrict-attempts-settings > ul > p.box-info' ).hide();
				$( this ).parent().next( 'p' ).show();
			} )
			.on( 'focusout', '.supv-secure-login-restrict-attempts-settings > ul > li > input', function() {
				$( '.supv-secure-login-restrict-attempts-settings > ul > p.box-info' ).hide();
			} );
	};

	/**
	 * Run the AJAX requests.
	 *
	 * @param {string}       action
	 * @param {Object}       params
	 * @param {string|false} target
	 */
	app.ajax_request = function( action, params, target ) {
		let data = {
			action,
		};

		if ( $( '#' + action + '_wpnonce' ).length ) {
			const _wpnonce = $( '#' + action + '_wpnonce' ).val();

			data = $.extend( data, { _wpnonce } );
		}

		if ( typeof params === 'object' ) {
			data = $.extend( data, params );
		}

		if ( typeof target === 'string' ) {
			if ( $( target ).length ) {
				target = $( target );
			} else {
				target = false;
			}
		}

		$.ajax( {
			method: 'POST',
			url: ajaxurl,
			data,
			beforeSend() {
				if ( target ) {
					target.html( '<p class="supv_loading"><img src="/wp-includes/images/spinner.gif"> ' + supv.loading + '</p>' );
				}
			},
		} )
			.done( function( response ) {
				if ( target ) {
					target.html( response );
				}
			} )
			.fail( function() {
				// console.log( 'Supervisor: Unable to process the AJAX request for ' + action + '.' );
			} );
	};

	return app;
}( document, jQuery ) );

Supervisor.init();
