<?php
if ( ! defined( 'SUPV' ) ) {
	exit;
}

if ( supv()->core()->ssl->is_ssl_available() ) {
	return;
}
?>

<div class="notice supv-notice supv-notice-https notice-error is-dismissible">
	<p>
		<strong>Supervisor:</strong>
		<?php _e( 'Your site is not currently using HTTPS. This is insecure and can negatively impact your search engine rankings. Please contact your developer(s) and/or hosting company to enable HTTPS for you as soon as possible!', 'supervisor' ); ?>

		<strong><?php _e( '<a href="https://letsencrypt.org/">Let\'s Encrypt</a> offers free SSL certificates!', 'supervisor' ); ?></strong>
	</p>
</div>
