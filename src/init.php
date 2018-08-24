<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package mapbox_block_gutenberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * `wp-blocks`: includes block type registration and related functions.
 *
 * @since 1.0.0
 */
function mapbox_block_gutenberg_assets() {
	// Styles.
	wp_enqueue_style(
		'mapbox_block_gutenberg-style', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);

	wp_enqueue_style(
		'mapbox_block_gutenberg-mapbox-style', // Handle.
		'https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.css',
		array( 'wp-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);
	

	// Mapbox script.
	wp_enqueue_script(
		'mapbox_block_gutenberg-mapbox-gl-js',
		'https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.js',
		array(),  // Dependencies
		'0.48.0',
		true // Enqueue the script in the footer.
	);

} // End function mapbox_block_gutenberg_assets().

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'mapbox_block_gutenberg_assets' );

/**
 * Enqueue Gutenberg block assets for frontend.
 *
 * @since 1.0.0
 */
function mapbox_block_gutenberg_frontend_assets() {
	// Front end map script.
	wp_enqueue_script(
		'mapbox_block_gutenberg-frontend',
		plugins_url( 'dist/mapbox-block.js', dirname( __FILE__ ) ),
		array('mapbox_block_gutenberg-mapbox-gl-js'),  // Dependencies
		'1.0.0',
		true // Enqueue the script in the footer.
	);

	wp_localize_script( 'mapbox_block_gutenberg-frontend', 'mapboxBlock', [
		'apiKey' => true ? 'pk.eyJ1Ijoic2FtaGVybWVzIiwiYSI6ImNpbGxjeGhmYzVvMm52bm1jdmx0NmtvbXoifQ.uf5gBnnbU05bnaw7atDu9A' : null
	] );

} // End function mapbox_block_gutenberg_frontend_assets().

// Hook: Frontend assets.
add_action( 'wp_enqueue_scripts', 'mapbox_block_gutenberg_frontend_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function mapbox_block_gutenberg_editor_assets() {
	// Mapbox geocoder script.
	wp_enqueue_script(
		'mapbox_block_gutenberg-mapbox-geocoder',
		'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js',
		array('mapbox_block_gutenberg-mapbox-gl-js'),  // Dependencies
		'2.3.0',
		true // Enqueue the script in the footer.
	);

	// Scripts.
	wp_enqueue_script(
		'mapbox_block_gutenberg-block', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'mapbox_block_gutenberg-mapbox-gl-js' ), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	wp_localize_script( 'mapbox_block_gutenberg-block', 'mapboxBlock', [
		'apiKey' => true ? 'pk.eyJ1Ijoic2FtaGVybWVzIiwiYSI6ImNpbGxjeGhmYzVvMm52bm1jdmx0NmtvbXoifQ.uf5gBnnbU05bnaw7atDu9A' : null
	] );

	// Styles.
	wp_enqueue_style(
		'mapbox_block_gutenberg-block-editor', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
	);

	wp_enqueue_style(
		'mapbox_block_gutenberg-mapbox-geocoder', // Handle.
		'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css',
		array( 'wp-edit-blocks', 'mapbox_block_gutenberg-mapbox-style' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);

} // End function mapbox_block_gutenberg_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'mapbox_block_gutenberg_editor_assets' );
