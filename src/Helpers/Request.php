<?php
namespace SUPV\Helpers;

/**
 * The Request class.
 *
 * @package supervisor
 * @since {VERSION}
 */
final class Request {

	/**
	 * Allowed sanitization callbacks.
	 *
	 * @since {VERSION}
	 *
	 * @var array
	 */
	const ALLOWED_SANITIZERS = [
		'sanitize_text_field',
		'sanitize_key',
	];

	/**
	 * Gets a GET parameter.
	 *
	 * @since {VERSION}
	 *
	 * @param string   $key           The parameter key.
	 * @param mixed    $default_value Optional. Default value if not found. Default null.
	 * @param callable $sanitizer     Optional. Sanitization callback. Default 'sanitize_text_field'.
	 *
	 * @return mixed The sanitized value, or default if not found.
	 */
	public static function get_arg( $key, $default_value = null, $sanitizer = 'sanitize_text_field' ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET[ $key ] ) ) {
			return $default_value;
		}

		// Validate sanitizer against allowlist.
		if ( ! in_array( $sanitizer, self::ALLOWED_SANITIZERS, true ) ) {
			$sanitizer = 'sanitize_text_field';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$value = wp_unslash( $_GET[ $key ] );

		return $sanitizer( $value );
	}

	/**
	 * Gets a POST parameter.
	 *
	 * @since {VERSION}
	 *
	 * @param string   $key           The parameter key.
	 * @param mixed    $default_value Optional. Default value if not found. Default null.
	 * @param callable $sanitizer     Optional. Sanitization callback. Default 'sanitize_text_field'.
	 *
	 * @return mixed The sanitized value, or default if not found.
	 */
	public static function get_post_arg( $key, $default_value = null, $sanitizer = 'sanitize_text_field' ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST[ $key ] ) ) {
			return $default_value;
		}

		// Validate sanitizer against allowlist.
		if ( ! in_array( $sanitizer, self::ALLOWED_SANITIZERS, true ) ) {
			$sanitizer = 'sanitize_text_field';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$value = wp_unslash( $_POST[ $key ] );

		return $sanitizer( $value );
	}
}
