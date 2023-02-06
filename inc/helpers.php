<?php
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
		'/resources/assets/%s/%s',
		$type,
		$name
	);

	return file_exists( SUPV_PLUGIN_DIR . $file ) ? SUPV_PLUGIN_URL . $file : '';
}

/**
 * Outputs a view.
 *
 * @since 1.0.0
 *
 * @param string $name The name of the view to output.
 */
function supv_output_view( $name ) {

	$file = sprintf( '/resources/views/%s.php', $name );

	if ( file_exists( SUPV_PLUGIN_DIR . $file ) ) {
		include SUPV_PLUGIN_DIR . $file;
	}
}

/**
 * Is Supervisor dashboard screen.
 *
 * @since 1.0.0
 *
 * @return bool True if it is the Supervisor dashboard.
 */
function supv_is_supervisor_screen() {

	return is_admin() && ! empty( $_GET['page'] ) && 'supervisor' === sanitize_key( wp_unslash( $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}
