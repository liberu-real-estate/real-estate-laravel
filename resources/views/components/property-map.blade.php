<div id="map" style="height: 400px;" wire:ignore></div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

<script>
    document.addEventListener('livewire:load', function () {
        var map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                polygon: true,
                polyline: false,
                rectangle: true,
                circle: false,
                marker: false,
                circlemarker: false
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        map.addControl(drawControl);

        map.on('draw:created', function (e) {
            drawnItems.clearLayers();
            var layer = e.layer;
            drawnItems.addLayer(layer);
            var geoJSON = layer.toGeoJSON();
            @this.call('updateDrawnArea', geoJSON.geometry.coordinates[0]);
        });

        map.on('draw:deleted', function (e) {
            @this.call('updateDrawnArea', null);
        });

        Livewire.on('propertiesUpdated', function (properties) {
            drawnItems.clearLayers();
            properties.forEach(function (property) {
                L.marker([property.lat, property.lng])
                    .addTo(map)
                    .bindPopup(property.title + '<br>Price: $' + property.price);
            });
        });
    });
</script>
@endpush