
export default class Map extends React.Component {
    render() {
        return <div id="mapbox-map" />;
    }

    componentDidMount() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FtaGVybWVzIiwiYSI6ImNpbGxjeGhmYzVvMm52bm1jdmx0NmtvbXoifQ.uf5gBnnbU05bnaw7atDu9A';

        const mapPoint = [
            this.props.lng,
            this.props.lat
        ];

        this.map = new mapboxgl.Map({
            container: 'mapbox-map',
            style: 'mapbox://styles/mapbox/streets-v9',
            center: mapPoint,
            zoom: this.props.zoom
        });

        this.addControls();
    }

    componentDidUpdate( prevProps ) {
		if ( this.props.zoom !== prevProps.zoom ) {
			this.map.flyTo({ zoom: this.props.zoom });
		}
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
                "type": "circle",
                "paint": {
                    "circle-radius": 10,
                    "circle-color": "#007cbf"
                }
            });

            this.controls.geocoder.on('result', (ev) => {
                this.map.getSource('single-point').setData(ev.result.geometry);

                this.props.onChange({
                    lng: ev.result.geometry.coordinates[0],
                    lat: ev.result.geometry.coordinates[1]
                });
            });
        });
    }
}