<?php
/**
 * Plugin Name: Mapbox Block for Gutenberg
 * Plugin URI: https://github.com/samhermes/mapbox-block-gutenberg/
 * Description: Adds a Mapbox maps block to the Gutenberg editor.
 * Author: Sam Hermes
 * Author URI: https://samhermes.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
