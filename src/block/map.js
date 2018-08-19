import React from 'react';

export default class Map extends React.Component {
    render() {
        return <div id="mapbox-map" />;
    }

    componentDidMount() {
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FtaGVybWVzIiwiYSI6ImNpbGxjeGhmYzVvMm52bm1jdmx0NmtvbXoifQ.uf5gBnnbU05bnaw7atDu9A';

        this.map = new mapboxgl.Map({
            container: 'mapbox-map',
            style: 'mapbox://styles/mapbox/streets-v9'
        });

        this.map.addControl( new MapboxGeocoder({ accessToken: mapboxgl.accessToken }) );
    }
}