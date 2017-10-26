<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://codex.management
 * @since      1.0.0
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<p>Lees hier alle instructies om de Wordpress Plug-in te gebruiken: 
	<a href="http://codex.support/integraties/integratie-wordpress-codex-connector" target="_blank">http://codex.support/integraties/integratie-wordpress-codex-connector</a>
	</p>
	<form action="options.php" method="post">
		<?php
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		?>
		<?php
		submit_button();
		?>
	</form>

</div>
	<div class="wrap">
	<h2>Verbinding status</h2>
	<?php if($this->api_connection === false) {

		?>
		<p>Er kon geen verbinding gemaakt worden. Probeer het nog eens! Hieronder staan hints wat er mis is:</p>
		<p>
		<code><?php 
				echo ($this->api_error);
				 ?></code>
			
		</p>
		<?php
	} else {

		?>
		Er is een verbinding. Goed bezig!
	<?php } ?>

	</div>
