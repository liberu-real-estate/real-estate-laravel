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

        var markers = L.layerGroup().addTo(map);

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
                featureGroup: markers,
                remove: true
            }
        });
        map.addControl(drawControl);

        map.on('draw:created', function (e) {
            var layer = e.layer;
            markers.addLayer(layer);
            var geoJSON = layer.toGeoJSON();
            @this.call('updateDrawnArea', geoJSON.geometry.coordinates[0]);
        });

        map.on('draw:deleted', function (e) {
            @this.call('updateDrawnArea', null);
        });

        function createMarkerPopup(property) {
            return `
                <div>
                    <h3>${property.title}</h3>
                    <p>Price: $${property.price}</p>
                    <p>Bedrooms: ${property.bedrooms}</p>
                    <p>Bathrooms: ${property.bathrooms}</p>
                    <a href="/property/${property.id}" target="_blank">View Details</a>
                </div>
            `;
        }

        Livewire.on('propertiesUpdated', function (properties) {
            markers.clearLayers();
            properties.forEach(function (property) {
                if (property.latitude && property.longitude) {
                    L.marker([property.latitude, property.longitude])
                        .bindPopup(createMarkerPopup(property))
                        .addTo(markers);
                }
            });
            if (properties.length > 0) {
                map.fitBounds(markers.getBounds());
            }
        });

        // Listen for filter changes
        Livewire.on('filtersChanged', function (filters) {
            @this.call('applyFilters', filters);
        });
    });
</script>
@endpush