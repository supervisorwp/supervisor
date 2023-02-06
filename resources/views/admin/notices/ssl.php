<?php
if ( ! defined( 'SUPV' ) ) {
	exit;
}

$days_to_expire = supv()->core()->ssl()->is_expiring();

if ( false === $days_to_expire || ! is_int( $days_to_expire ) ) {
	return;
}

$status = $days_to_expire <= 0 ? 'expired' : 'expiring_soon';

$ssl_data = get_transient( \SUPV\Core\SSL::SSL_DATA_TRANSIENT );

$issuer = empty( $ssl_data['issuer'] ) ? '' : ' (' . $ssl_data['issuer'] . ')';
$days   = _n( 'day', 'days', abs( $days_to_expire ), 'supervisor' );

$messages = [
	'expiring_soon' => [
		'class'   => 'notice-warning is-dismissible',
		/* translators: %1$s is the certificate issuer, %2$d is the number of days, %3$s is 'day' or 'days' string */
		'message' => sprintf( __( 'Your SSL certificate%1$s will expire in %2$d %3$s. Please don\'t forget to renew it!', 'supervisor' ), $issuer, $days_to_expire, $days ),
	],
	'expired'       => [
		'class'   => 'notice-error is-dismissible',
		/* translators: %1$s is the certificate issuer, %2$d is the number of days, %3$s is 'day' or 'days' string */
		'message' => sprintf( __( 'Your SSL certificate%1$s has expired %2$d %3$s ago. Please renew it as soon as possible!', 'supervisor' ), $issuer, abs( $days_to_expire ), $days ),
	],
];
?>

<div class="notice supervisor-notice supervisor-notice-ssl <?php echo esc_attr( $messages[ $status ]['class'] ); ?>">
	<p>
		<strong>Supervisor:</strong>
		<?php echo esc_html( $messages[ $status ]['message'] ); ?>
	</p>
</div>
