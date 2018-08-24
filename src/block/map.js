
export default class Map extends React.Component {
    render() {
        return <div id="mapbox-map" />;
    }

    componentDidMount() {
        mapboxgl.accessToken = mapboxBlock.apiKey;

        let mapPoint = [
            this.props.lng,
            this.props.lat
        ];

        this.map = new mapboxgl.Map({
            container: 'mapbox-map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: mapPoint,
            zoom: this.props.zoom
        });

        let marker = new mapboxgl.Marker()
            .setLngLat( mapPoint )
            .addTo( this.map );

        this.addControls( marker );
    }

    componentDidUpdate( prevProps ) {
		if ( this.props.zoom !== prevProps.zoom ) {
			this.map.flyTo({ zoom: this.props.zoom });
		}
	}

    addControls( marker ) {
        this.controls = {};

		this.controls.geocoder = new MapboxGeocoder({
            accessToken: mapboxgl.accessToken,
            flyTo: false
        });
		this.map.addControl( this.controls.geocoder, 'top-right' );
        
        this.map.on('load', () => {
            this.controls.geocoder.on('result', (ev) => {
                this.map.jumpTo({
                    center: ev.result.center
                });

                marker.setLngLat( ev.result.center );

                this.props.onChange({
                    lng: ev.result.geometry.coordinates[0],
                    lat: ev.result.geometry.coordinates[1]
                });
            });
        });
    }
}