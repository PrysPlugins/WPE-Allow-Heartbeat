<?php
/**
 * Plugin Name: WPE Allow Heartbeat
 * Plugin URI: https://github.com/PrysPlugins/WPE-Allow-Heartbeat
 * Description: Allow the Heartbeat API in more places on WP Engine
 * Version: 0.1.0
 * Author: Jeremy Pry, Jason Stallings
 * Author URI: http://jeremypry.com/
 * License: GPL2
 */
 
 // Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
	die( "You can't do anything by accessing this file directly." );
}


// create custom plugin settings menu
add_action('admin_menu', 'wpeallowheartbeat_create_menu');
add_filter( 'wpe_heartbeat_allowed_pages', 'wpeallowheartbeat_add_allowed_pages' );

function wpeallowheartbeat_add_allowed_pages($heartbeat_allowed_pages)
{
	if (get_option('wpeallowheartbeat_pagelist'))
	{
		//Takes the comma separated list of pages, turns it into an array, then strips whitespace and removes blank entries.
		$additional_pages=array_filter(array_map('trim', explode(",", get_option('wpeallowheartbeat_pagelist'))));
		array_push($heartbeat_allowed_pages, $additional_pages);
	}
	return $heartbeat_allowed_pages;
}


function wpeallowheartbeat_create_menu() 
{

	//create new top-level menu
	$wpeallowheartbeat_settings_page=add_submenu_page('options-general.php', 'WPE Allow Heartbeat', 'WPE Allow Heartbeat', 'administrator', __FILE__, 'wpeallowheartbeat_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_wpeallowheartbeat_settings' );
}


function register_wpeallowheartbeat_settings() 
{

	//register our settings
	register_setting( 'wpeallowheartbeat-settings-group', 'wpeallowheartbeat_pagelist' );
}


function wpeallowheartbeat_settings_page() 
{
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
        			<th scope="row">List of pages with heartbeat enabled, comma seperated.</th>
        			<td><input type="text" name="wpeallowheartbeat_pagelist" value="<?php echo get_option('wpeallowheartbeat_pagelist'); ?>" /></td>
        		</tr>
    		</table>
    
   		<?php submit_button(); ?>

		</form>
	</div>
<?php 
}