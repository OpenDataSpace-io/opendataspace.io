import React, { useEffect, useRef } from 'react';
import L from 'leaflet';

interface Position {
    latitude: number;
    longitude: number;
}

interface GeoProps {
    value: Position;
    onChange: (position: Position) => void;
}

const GeoWidget: React.FC<GeoProps> = ({ value, onChange }) => {
    const mapRef = useRef<L.Map | null>(null);
    const markerRef = useRef<L.Marker | null>(null);

    useEffect(() => {
        if (typeof window !== 'undefined') {
            // Initialize the map
            mapRef.current = L.map('map').setView([value.latitude, value.longitude], 13);

            // Add the tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            }).addTo(mapRef.current);

            // Add the marker
            markerRef.current = L.marker([value.latitude, value.longitude], { draggable: true }).addTo(mapRef.current);

            // Update the position when the marker is dragged
            markerRef.current.on('dragend', (event) => {
                const marker = event.target as L.Marker;
                const position = marker.getLatLng();
                onChange({ latitude: position.lat, longitude: position.lng });
            });

            return () => {
                // Clean up the map and marker when the component is unmounted
                mapRef.current?.remove();
                markerRef.current?.remove();
            };
        }
    }, [value.latitude, value.longitude, onChange]);

    const handleLatitudeChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const newLatitude = parseFloat(event.target.value);
        onChange({ ...value, latitude: newLatitude });
    };

    const handleLongitudeChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const newLongitude = parseFloat(event.target.value);
        onChange({ ...value, longitude: newLongitude });
    };

    return (
        <>
            <div id="map" style={{ height: '400px' }}></div>

            <label>Latitude:</label>
            <input type="number" value={value.latitude} onChange={handleLatitudeChange} />

            <label>Longitude:</label>
            <input type="number" value={value.longitude} onChange={handleLongitudeChange} />
        </>
    );
};

export default GeoWidget;