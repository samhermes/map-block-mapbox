<?php
/**
 * Plugin Name: Map Block for Mapbox
 * Plugin URI: https://github.com/samhermes/mapbox-block-gutenberg/
 * Description: Adds a map block to the Gutenberg editor using Mapbox.
 * Author: Sam Hermes
 * Author URI: https://samhermes.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package mapbox_block_gutenberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
