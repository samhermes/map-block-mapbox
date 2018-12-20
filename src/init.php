<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package map_block_mapbox
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
function mapbox_block_assets() {
	// Styles.
	wp_enqueue_style(
		'mapbox_block-style', // Handle.
		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);

	wp_enqueue_style(
		'mapbox_block-mapbox-style', // Handle.
		'https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.css',
		array( 'wp-editor', 'mapbox_block-style' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);

	// Mapbox script.
	wp_enqueue_script(
		'mapbox_block-mapbox-gl-js',
		'https://api.tiles.mapbox.com/mapbox-gl-js/v0.48.0/mapbox-gl.js',
		array(),  // Dependencies
		'0.48.0',
		true // Enqueue the script in the footer.
	);

} // End function mapbox_block_assets().

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'mapbox_block_assets' );

/**
 * Enqueue Gutenberg block assets for frontend.
 *
 * @since 1.0.0
 */
function mapbox_block_frontend_assets() {
	// Front end map script.
	wp_enqueue_script(
		'mapbox_block-frontend',
		plugins_url( 'dist/mapbox-block.js', dirname( __FILE__ ) ),
		array('mapbox_block-mapbox-gl-js'),  // Dependencies
		'1.0.1',
		true // Enqueue the script in the footer.
	);

	wp_localize_script( 'mapbox_block-frontend', 'mapboxBlock', [
		'accessToken' => get_option( 'mapbox_block_token' ) ? get_option( 'mapbox_block_token' ) : null
	] );

} // End function mapbox_block_frontend_assets().

// Hook: Frontend assets.
add_action( 'wp_enqueue_scripts', 'mapbox_block_frontend_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function mapbox_block_editor_assets() {
	// Styles.
	wp_enqueue_style(
		'mapbox_block-block-editor', // Handle.
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
	);

	wp_enqueue_style(
		'mapbox_block-mapbox-geocoder-style', // Handle.
		'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css',
		array( 'wp-edit-blocks', 'mapbox_block-mapbox-style' ) // Dependency to include the CSS after it.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: filemtime — Gets file modification time.
	);

	// Mapbox geocoder script.
	wp_enqueue_script(
		'mapbox_block-mapbox-geocoder',
		'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js',
		array('mapbox_block-mapbox-gl-js'),  // Dependencies
		'2.3.0',
		true // Enqueue the script in the footer.
	);

	// Scripts.
	wp_enqueue_script(
		'mapbox_block-block', // Handle.
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'mapbox_block-mapbox-gl-js', 'mapbox_block-mapbox-geocoder' ), // Dependencies, defined above.
		'1.0.1',
		true // Enqueue the script in the footer.
	);

	wp_localize_script( 'mapbox_block-block', 'mapboxBlock', [
		'accessToken' => get_option( 'mapbox_block_token' ) ? get_option( 'mapbox_block_token' ) : null,
		'optionsPage' => admin_url( 'options-general.php?page=mapbox-block-settings' )
	] );

} // End function mapbox_block_editor_assets().

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'mapbox_block_editor_assets' );

/**
 * Register setting field for access token from Mapbox.
 *
 * @since 1.0.0
 */
function mapbox_block_token_setting() {
	add_settings_section( 'token', '', null, 'mapbox-block-settings');

	$args = array(
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '',
	);
	register_setting( 'token', 'mapbox_block_token', $args );

	add_settings_field(
		'coordinates_api_key',
		'Mapbox Access Token',
		'mapbox_block_settings_field_cb',
		'mapbox-block-settings',
		'token',
		array( 'label_for' => 'mapbox_block_token' )
	);
}
add_action( 'admin_init', 'mapbox_block_token_setting' );

function mapbox_block_settings_field_cb() {
    $setting = get_option( 'mapbox_block_token' ); ?>
    <input type="text" name="mapbox_block_token" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
    <?php
}

/**
 * Add options page for block settings.
 *
 * @since 1.0.0
 */
function mapbox_block_options_page() {
	add_submenu_page(
		null,
		'Map Block for Mapbox Settings',
		'Map Block for Mapbox',
		'manage_options',
		'mapbox-block-settings',
		'mapbox_block_options_page_cb'
	);
}
add_action('admin_menu', 'mapbox_block_options_page');

function mapbox_block_options_page_cb() {
?>
	<h2>Map Block for Mapbox Settings</h2>

	<p>In order to use the map block, you'll need to connect to your Mapbox account.</p>
	<p>Sign in to your Mapbox account at <a href="https://www.mapbox.com/account/">mapbox.com/account</a>. From there, you'll be able to create a new token, or use your default public token.</p>
	<p>Enter the token below, and it will be used for all map blocks on your site.</p>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'token' );
			do_settings_sections( 'mapbox-block-settings' );
			submit_button();
		?>
	</form>
<?php
}