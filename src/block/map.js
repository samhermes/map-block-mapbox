import React from 'react';

export default class Map extends React.Component {
    render() {
        return <div id="mapbox-map" />;
    }

    componentDidMount() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FtaGVybWVzIiwiYSI6ImNpbGxjeGhmYzVvMm52bm1jdmx0NmtvbXoifQ.uf5gBnnbU05bnaw7atDu9A';

        this.map = new mapboxgl.Map({
            container: 'mapbox-map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: this.props.center,
        });

        this.addControls();
    }

    onChange() {
		this.props.onChange( {
			center: this.map.getCenter().toArray(),
		} );
	}

    addControls() {
		this.controls = {};

		this.controls.geocoder = new MapboxGeocoder({ accessToken: mapboxgl.accessToken });
		this.map.addControl( this.controls.geocoder, 'top-right' );

		this.controls.nav = new mapboxgl.NavigationControl();
		this.map.addControl( this.controls.nav, 'top-right' );
        
        this.map.on('load', () => {
            this.map.addSource('single-point', {
                "type": "geojson",
                "data": {
                    "type": "FeatureCollection",
                    "features": []
                }
            });

            this.map.addLayer({
                "id": "point",
                "source": "single-point",
                "type": "symbol",
                "layout": {
                    "icon-image": "marker-15",
                }
            });

            this.controls.geocoder.on('result', (ev) => {
                this.map.getSource('single-point').setData(ev.result.geometry);
            });
        });
	}
}

Map.defaultProps = {
	center: [ 0, 0 ]
};