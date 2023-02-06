<?php
if ( ! defined( 'SUPV' ) ) {
	exit;
}

// Name of the box views to output on admin page.
$boxes = [
	'transients',
	'autoload',
];
?>

<div class="supv-container-fluid">
	<div class="supv-header">
		<div class="supv-header-logo">
			<img src="<?php echo esc_url( supv_get_asset_url( 'supervisor.png' ) ); ?>" title="Supervisor" />
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

			<?php if ( ! empty( supv()->core()->server()->get_ip() ) ) : ?>
				<div class="supv-overview-box">
					<p>Server IP</p>
					<p>
						<?php echo esc_html( supv()->core()->server()->get_ip() ); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>

		<div class="supv-boxes supv-row">
			<?php foreach ( $boxes as $box ) : ?>
				<div class="supv-box supv-col col-md-6">
					<?php supv_output_view( 'admin/boxes/' . $box ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
