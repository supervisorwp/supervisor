<?php

use SUPV\Helpers\Request as RequestHelper;

/**
 * Gets the asset URL.
 *
 * @since 1.0.0
 *
 * @param string $name The asset filename.
 * @param string $type The asset type ('images', 'css', 'js', or 'fonts').
 *
 * @return string The asset URL.
 */
function supv_get_asset_url( $name, $type = 'images' ) {

	$file = sprintf(
		'/assets/%s/%s',
		$type,
		$name
	);

	return file_exists( SUPV_PLUGIN_DIR . $file ) ? SUPV_PLUGIN_URL . $file : '';
}

/**
 * Determines whether the current screen is the Supervisor dashboard screen.
 *
 * @since 1.0.0
 *
 * @return bool True if it is the Supervisor dashboard.
 */
function supv_is_supervisor_screen() {

	$page = RequestHelper::get_arg( 'page', null, 'sanitize_key' );

	return is_admin() && $page === 'supervisor';
}

/**
 * Determines whether the current request is a WP-CLI request.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function supv_is_doing_wpcli() {

	return defined( 'WP_CLI' ) && WP_CLI;
}

/**
 * Gets the user IP address.
 *
 * @since 1.3.0
 *
 * @return string|false The user's IP address, or false on error.
 */
function supv_get_user_ip() {

	$user_ip = false;

	$headers = [
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR',
	];

	foreach ( $headers as $header ) {
		if ( ! array_key_exists( $header, $_SERVER ) ) {
			continue;
		}

		$ip_chain = explode( ',', sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ) );

		if ( ! empty( $ip_chain[0] ) ) {
			$possible_ip = trim( $ip_chain[0] );

			if ( filter_var( $possible_ip, FILTER_VALIDATE_IP ) ) {
				$user_ip = $possible_ip;

				break;
			}
		}
	}

	/**
	 * Filters the user IP address.
	 *
	 * @since 1.3.0
	 *
	 * @param string|false $user_ip The user's IP address, or false on error.
	 */
	$user_ip = apply_filters( 'supv_helpers_get_user_ip', $user_ip );

	return $user_ip ?? false;
}

/**
 * Prepares a WP Error object.
 *
 * @since 1.3.0
 *
 * @param string $error_code    The error code.
 * @param string $error_message The error message.
 *
 * @return WP_Error
 */
function supv_prepare_wp_error( $error_code, $error_message ) {

	$error = new WP_Error();

	$message = sprintf(
		'<strong>%s</strong>: %s',
		esc_html__( 'Error', 'supervisor' ),
		$error_message
	);

	$error->add( $error_code, $message );

	return $error;
}
