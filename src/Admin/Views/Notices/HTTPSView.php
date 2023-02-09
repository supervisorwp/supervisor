<?php
namespace SUPV\Admin\Views\Notices;

use SUPV\Admin\Views\AbstractView;

/**
 * The HTTPSView class.
 *
 * @package supervisor-wp
 * @since 1.0.0
 */
final class HTTPSView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		if ( supv()->core()->ssl()->is_available() ) {
			return;
		}

		?>
		<div class="notice supv-notice supv-notice-https notice-error is-dismissible <?php echo supv_is_supervisor_screen() ? 'supv-notice-margin' : ''; ?>">
			<p>
				<strong>Supervisor:</strong>
				<?php esc_html_e( 'Your site is not currently using HTTPS. This is insecure and can negatively impact your search engine rankings. Please contact your developer(s) and/or hosting company to enable HTTPS for you as soon as possible!', 'supervisor-wp' ); ?>

				<strong>
					<?php
					echo sprintf(
						/* translators: %s is "Let's Encrypt". */
						esc_html__( '%s offers free SSL certificates!', 'supervisor-wp' ),
						'<a href="https://letsencrypt.org/">Let\'s Encrypt</a>'
					);
					?>
				</strong>
			</p>
		</div>
		<?php
	}
}
