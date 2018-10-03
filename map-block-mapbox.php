<?php
/**
 * Plugin Name: Map Block for Mapbox
 * Plugin URI: https://github.com/samhermes/map-block-gutenberg/
 * Description: Adds a map block to the Gutenberg editor using Mapbox.
 * Author: Sam Hermes
 * Author URI: https://samhermes.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package map_block_mapbox
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

/**
 * Add settings page link to plugin actions.
 */
function mapbox_block_add_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=mapbox-block-settings">' . __( 'Settings' ) . '</a>';
	array_push( $links, $settings_link );
	
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'mapbox_block_add_settings_link' );

/**
 * Redirect user to settings page after plugin activation.
 */
function mapbox_block_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'options-general.php?page=mapbox-block-settings' ) ) );
    }
}
add_action( 'activated_plugin', 'mapbox_block_activation_redirect' );