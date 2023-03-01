<?php
namespace SUPV\Admin\Views;

/**
 * The SystemInfoView class.
 *
 * @package supervisor
 * @since 1.1.0
 */
final class SystemInfoView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.1.0
	 */
	public function output() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$sysinfo = supv()->core()->server()->get_data();
		?>
		<div class="supv-sysinfo">
			<div class="supv-sysinfo-box">
				<p><?php esc_html_e( 'WordPress', 'supervisor' ); ?></p>
				<p><?php echo ! empty( $sysinfo['wp'] ) ? esc_html( $sysinfo['wp'] ) : '-'; ?></p>

				<p class="supv-sysinfo-box-status"><?php $this->output_software_status_icon( 'wp' ); ?></p>
			</div>

			<div class="supv-sysinfo-box">
				<p><?php esc_html_e( 'PHP', 'supervisor' ); ?></p>
				<p><?php echo ! empty( $sysinfo['php'] ) ? esc_html( $sysinfo['php'] ) : '-'; ?></p>

				<p class="supv-sysinfo-box-status"><?php $this->output_software_status_icon( 'php' ); ?></p>
			</div>

			<div class="supv-sysinfo-box">
				<p><?php echo ! empty( $sysinfo['database']['service'] ) ? esc_html( $sysinfo['database']['service'] ) : esc_html__( 'Database', 'supervisor' ); ?></p>
				<p><?php echo ! empty( $sysinfo['database']['version'] ) ? esc_html( $sysinfo['database']['version'] ) : '-'; ?></p>

				<p class="supv-sysinfo-box-status"><?php $this->output_software_status_icon( strtolower( $sysinfo['database']['service'] ) ); ?></p>
			</div>

			<div class="supv-sysinfo-box">
				<p><?php esc_html_e( 'Web Server', 'supervisor' ); ?></p>
				<p>
					<?php
					echo ! empty( $sysinfo['web']['service'] ) ? esc_html( $sysinfo['web']['service'] ) . '/' : '';
					echo ! empty( $sysinfo['web']['version'] ) ? esc_html( $sysinfo['web']['version'] ) : '-';
					?>
				</p>

				<p class="supv-sysinfo-box-status"><?php $this->output_software_status_icon( strtolower( $sysinfo['web']['service'] ) ); ?></p>
			</div>

			<?php if ( ! empty( supv()->core()->server()->get_ip() ) ) : ?>
				<div class="supv-sysinfo-box">
					<p><?php esc_html_e( 'Server IP', 'supervisor' ); ?></p>
					<p>
						<?php echo esc_html( supv()->core()->server()->get_ip() ); ?>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Output the software status icon.
	 *
	 * @since 1.1.0
	 *
	 * @param string $software The software name.
	 */
	private function output_software_status_icon( $software ) {

		if ( empty( $software ) ) {
			return;
		}

		$status = supv()->core()->server()->is_updated( $software );

		if ( empty( $status ) ) {
			return;
		}

		$title = '';
		$icon  = '';

		switch ( $status ) {
			case 'updated':
				$title = esc_html__( 'Your software is up-to-date', 'supervisor' );
				$icon  = 'success';
				break;

			case 'outdated':
				$title = esc_html__( 'Your software is outdated', 'supervisor' );
				$icon  = 'warning';
				break;

			case 'obsolete':
				$title = esc_html__( 'Your software is not supported anymore', 'supervisor' );
				$icon  = 'error';
				break;
		}

		echo '<span class="supv-icon-' . esc_attr( $icon ) . '" title="' . esc_html( $title ) . '"></span>';
	}
}
