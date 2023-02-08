<?php
namespace SUPV\Admin\Views;

/**
 * The AutoloadView class.
 *
 * @package supervisor
 * @since 1.0.0
 */
final class AutoloadView extends AbstractView {

	/**
	 * Outputs the view.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?>
		<div class="supv-card">
			<div class="header">
				<div class="text">Autoload Options</div>
			</div>

			<div class="content">
				<p>WordPress autoload options are very similar to transients. The main difference is: transients are used to store temporary data, while options are used to store permanent data.</p>
				<p>All the autoload options, as well as transients, are loaded automatically when WordPress loads itself. Thus, the number and size of these options can directly affect your site performance.</p>
			</div>
		</div>
		<?php
	}
}
