<?php
if ( ! defined( 'SUPV' ) ) {
	exit;
}
?>

<div class="supv-header">
	<div class="supv-header-logo">
		<img src="<?php echo SUPV_PLUGIN_URL . '/resources/assets/images/supervisor.png'; ?>" title="Supervisor" />
	</div>
</div>

<div class="supv-container">
	<div class="supv-overview">
		<?php $server_info = supv()->core()->server()->get_data(); ?>

		<div class="supv-overview-box">
			<p>WordPress</p>
			<p><?php echo ! empty( $server_info['wp'] ) ? esc_html( $server_info['wp'] ) : '-'; ?></p>
		</div>

		<div class="supv-overview-box">
			<p>PHP</p>
			<p><?php echo ! empty( $server_info['php'] ) ? esc_html( $server_info['php'] ) : '-'; ?></p>
		</div>

		<div class="supv-overview-box">
			<p><?php echo ! empty( $server_info['database']['service'] ) ? esc_html( $server_info['database']['service'] ) : 'Database'; ?></p>
			<p><?php echo ! empty( $server_info['database']['version'] ) ? esc_html( $server_info['database']['version'] ) : '-'; ?></p>
		</div>

		<div class="supv-overview-box">
			<p>Web Server</p>
			<p>
				<?php
				echo ! empty( $server_info['web']['service'] ) ? esc_html( $server_info['web']['service'] ) . '/' : '';
				echo ! empty( $server_info['web']['version'] ) ? esc_html( $server_info['web']['version'] ) : '-';
				?>
			</p>
		</div>

		<div class="supv-overview-box">
			<p>Server IP</p>
			<p>
				<?php echo ! empty( supv()->core()->server()->get_ip() ) ? supv()->core()->server()->get_ip() : '-'; ?>
			</p>
		</div>

		<div class="supv-overview-box">
			<p>SSL</p>
			<p><?php echo ! supv()->core()->ssl()->is_available() ? 'Enabled' : 'Not available'; ?></p>
		</div>
	</div>

	<div class="supv-box">
		<h3>Transients</h3>

		<div>
			<p>WordPress transients are used to temporarily cache specific data. For example, developers often use them to improve their themes and plugins performance by caching database queries and script results.</p>
			<p>However, some badly coded plugins and themes can store too much information on these transients, or can even create an excessively high number of transients, resulting in performance degradation.</p>
		</div>
	</div>

	<div class="supv-box">
		<h3>Autoload Options</h3>

		<div>
			<p>WordPress autoload options are very similar to transients. The main difference is: transients are used to store temporary data, while options are used to store permanent data.</p>
			<p>All the autoload options, as well as transients, are loaded automatically when WordPress loads itself. Thus, the number and size of these options can directly affect your site performance.</p>
		</div>
	</div>

	<div class="supv-box">
		<h3>Orphaned Data: Meta</h3>

		<div>
			<p>WordPress allows the plugins and themes developers to include their own meta data on existing users and posts.</p>
			<p>However, sometimes when an user or a post is removed from your site, this meta data is not removed, and they become a piece of data that's not required anymore.</p>
		</div>
	</div>
</div>
