<?php
if ( ! defined( 'SUPV' ) ) {
	exit;
}
?>

<div class="supv-container-fluid">
	<div class="supv-header">
		<div class="supv-container">
			<div class="supv-header-content">
				<img src="<?php echo SUPV_PLUGIN_URL . '/resources/assets/images/supervisor.png'; ?>" />
			</div>
		</div>
	</div>

	<div class="supv-container">
		<div class="supv-row">
			<div class="supv-col col-md-4">
				<div class="supv-card">
					<div class="header">
						<div class="text">Autoload Options</div>
					</div>

					<div class="content">
						<p>WordPress autoload options are very similar to transients. The main difference is: transients are used to store temporary data, while options are used to store permanent data.</p>

						<p>All the autoload options, as well as transients, are loaded automatically when WordPress loads itself. Thus, the number and size of these options can directly affect your site performance.</p>
					</div>
				</div>
			</div>

			<div class="supv-col col-md-4">
				<div class="supv-card">
					<div class="header">
						<div class="text">Transients</div>
					</div>
				</div>
			</div>

			<div class="supv-col col-md-4">
				<div class="supv-card">
					<div class="header">
						<div class="text">SSL</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
