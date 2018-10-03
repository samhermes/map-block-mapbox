/**
 * BLOCK: map-block-mapbox
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import.
import './style.scss';
import './editor.scss';
import Map from './map'

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { InspectorControls } = wp.editor;
const { RangeControl } = wp.components;
const { Fragment } = wp.element;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'coordinates/mapbox-map', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Mapbox Map' ), // Block title.
	icon: 'location-alt', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'embed',
	keywords: [
		__( 'map-block-mapbox' ),
		__( 'Mapbox Map Block' ),
	],
	supports: {
		multiple: false,
	},
	attributes: {
		lat: {
			type: 'number',
			source: 'attribute',
            attribute: 'data-lat',
            selector: '#mapbox-map',
			default: 0,
		},
		lng: {
			type: 'number',
			source: 'attribute',
            attribute: 'data-lng',
            selector: '#mapbox-map',
			default: 0,
		},
		zoom: {
			type: 'number',
			source: 'attribute',
			attribute: 'data-zoom',
            selector: '#mapbox-map',
			default: 1
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function( { attributes, setAttributes, className } ) {
		const { lat, lng, zoom } = attributes;

		if ( ! mapboxBlock.accessToken ) {
            return (
                <div className="mapbox-block-token">
					<h2>Block Setup</h2>
					<p>To use this block, you need to connect it to your Mapbox account. From then on out, you'll be good to go!</p>
					<a href={ mapboxBlock.optionsPage } className="mapbox-block-token-cta">Connect to Mapbox</a>
				</div>
            )
        }
		
		if ( mapboxBlock.accessToken ) {
			return (
				<Fragment>
					<InspectorControls>
						<RangeControl
							label={ __( 'Zoom Level' ) }
							value={ zoom }
							onChange={ ( value ) => setAttributes( { zoom: value } ) }
							min={ 1 }
							max={ 22 }
							/>
					</InspectorControls>
					<div className={ className }>
						<Map
							lat={ lat }
							lng={ lng }
							zoom={ zoom }
							onChange={ ( value ) => setAttributes( {
								lat: value.lat,
								lng: value.lng
							} ) }
							/>
					</div>
				</Fragment>
			);
		}
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function( { attributes } ) {
		const { lat, lng, zoom } = attributes;

		return (
			<div>
				<div id="mapbox-map"
					data-lat={ lat }
					data-lng={ lng }
					data-zoom={ zoom }
				></div>
			</div>
		);
	},
} );
