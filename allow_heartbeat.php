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
		$additional_pages        = array_filter( array_map( 'trim', explode( ',', $option ) ) );
		$heartbeat_allowed_pages = array_merge( $heartbeat_allowed_pages, $additional_pages );
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
	$name   = 'wpeallowheartbeat_pagelist';
	$option = get_option( $name );

	?>
	<div class="wrap">
		<h2>WPE Allow Heartbeat</h2>

		<p>
			As part of their techniques for managing resources in the Admin area of WordPress, WP Engine removes the
			<a href="https://developer.wordpress.org/plugins/javascript/heartbeat-api/">Heartbeat API</a> from most of
			the pages in the Admin area. The Heartbeat API has many purposes, but one of the primary purposes is in
			maintaining an edit lock while a particular user is editing a post. Thus, WP Engine leaves the Heartbeat
			API enabled on post edit pages.
		</p>
		<p>
			If you need the Heartbeat API to be enabled on other pages, simply add those pages to the field below. It is
			important to use the page name, which is typically the PHP file name. For example, to enable the Heartbeat
			API on the Appearance > Themes page, enter <code>themes.php</code> in the field below. To enable the API
			on the Options > General page, enter <code>options-general.php</code> in the field below.
		</p>
		<p>
			The pages where WP Engine already allows the Heartbeat API by default are these:
		</p>
		<ul>
			<li><code>edit.php</code> - Used for displaying all post types and editing individual posts </li>
			<li><code>post-new.php</code> - Used for creating a new post.</li>
			<li><code>post.php</code> - Used for submitting data for an edited post.</li>
		</ul>

		<form method="post" action="options.php">
			<?php settings_fields( 'wpeallowheartbeat-settings-group' ); ?>
			<?php do_settings_sections( 'wpeallowheartbeat-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="<?php echo esc_attr( $name ); ?>">
							List of pages with heartbeat enabled, comma separated.
						</label>
					</th>
					<td>
						<input type="text"
							   class="widefat"
							   id="<?php echo esc_attr( $name ); ?>"
							   name="<?php echo esc_attr( $name ); ?>"
							   value="<?php echo esc_attr( $option ); ?>" />
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>

		</form>
	</div>
	<?php
}
