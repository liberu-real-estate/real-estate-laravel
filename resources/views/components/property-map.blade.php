<div id="map" style="height: 400px;"></div>

<script type="module">
    let properties = @json($properties);
    var map = L.map('map', {doubleClickZoom: false}).locate({setView: true, maxZoom: 16})
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    L.marker([51.5, -0.09]).addTo(map)
        .bindPopup("{{ SiteConfig::get('name') }}")
        .openPopup();
        properties.forEach(function(loc) {
    L.marker([loc.latitude, loc.longitude]).addTo(map)
        .bindPopup('<b>' + loc.title + '</b><br>' + loc.location);
    });
</script>
