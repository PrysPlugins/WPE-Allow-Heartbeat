<?php
/**
 * Plugin Name: WPE Allow Heartbeat
 * Plugin URI: https://github.com/PrysPlugins/WPE-Allow-Heartbeat
 * Description: Allow the Heartbeat API in more places on WP Engine
 * Version: 0.2.0
 * Author: Jeremy Pry, Jason Stallings
 * Author URI: http://jeremypry.com/
 * License: GPL2
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}

// create custom plugin settings menu
add_action( 'admin_menu', 'wpeallowheartbeat_create_menu' );
add_filter( 'wpe_heartbeat_allowed_pages', 'wpeallowheartbeat_add_allowed_pages' );

/**
 * Filter the pages where WPE allows the heartbeat.
 *
 * @param array $heartbeat_allowed_pages
 *
 * @return array
 */
function wpeallowheartbeat_add_allowed_pages( $heartbeat_allowed_pages ) {
	$option = get_option( 'wpeallowheartbeat_pagelist' );

	if ( false !== $option ) {
		// Takes the comma separated list of pages, turns it into an array, then strips whitespace and removes blank entries.
		$additional_pages = array_filter( array_map( 'trim', explode( ',', $option ) ) );
		array_merge( $heartbeat_allowed_pages, $additional_pages );
	}

	return $heartbeat_allowed_pages;
}

/**
 * Add our menu.
 *
 * @author Jeremy Pry
 */
function wpeallowheartbeat_create_menu() {
	// Create new top-level menu
	add_submenu_page(
		'options-general.php',
		'WPE Allow Heartbeat',
		'WPE Allow Heartbeat',
		'administrator',
		__FILE__,
		'wpeallowheartbeat_settings_page'
	);

	// Call register settings function
	add_action( 'admin_init', 'register_wpeallowheartbeat_settings' );
}

/**
 * Register our settings.
 *
 * @author Jeremy Pry
 */
function register_wpeallowheartbeat_settings() {
	register_setting( 'wpeallowheartbeat-settings-group', 'wpeallowheartbeat_pagelist' );
}

/**
 * Output the settings form.
 *
 * @author Jeremy Pry
 */
function wpeallowheartbeat_settings_page() {
	$option = esc_attr( get_option( 'wpeallowheartbeat_pagelist' ) );

	?>
	<div class="wrap">
		<h2>WPE Allow Heartbeat</h2>

		<p>
			Some details here about how to use this plugin.
		</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'wpeallowheartbeat-settings-group' ); ?>
			<?php do_settings_sections( 'wpeallowheartbeat-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="wpeallowheartbeat_pagelist">
							List of pages with heartbeat enabled, comma seperated.
						</label>
					</th>
					<td>
						<input type="text" id="wpeallowheartbeat_pagelist" name="wpeallowheartbeat_pagelist" value="<?php echo $option ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>

		</form>
	</div>
	<?php
}
