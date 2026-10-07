<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Location Radar & Threat Intelligence Studio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Leaflet.js OpenStreetMap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: .3s;
        }

        .info-card:hover {
            transform: translateY(-4px);
        }

        #map {
            height: 380px;
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
        }

        .flag-img {
            width: 38px;
            height: 26px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            object-fit: cover;
        }

        .badge-safe { background-color: #198754; color: #fff; }
        .badge-suspicious { background-color: #ffc107; color: #212529; }
        .badge-threat { background-color: #dc3545; color: #fff; }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- Navigation Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="fw-bold text-primary m-0">
                    🌍 IP Geo-Location Radar & Threat Intelligence Studio
                </h2>
                <p class="text-muted small m-0">
                    Search IP Address, Live Leaflet Map, Threat Score, Subnet & GeoJSON Exporters
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-success rounded-pill px-3">
                    <i class="fa-solid fa-chart-pie me-1"></i> Dashboard
                </a>
                <a href="{{ route('history') }}" class="btn btn-dark rounded-pill px-3">
                    <i class="fa-solid fa-history me-1"></i> History
                </a>
                <a href="{{ route('subnet') }}" class="btn btn-outline-primary rounded-pill px-3">
                    <i class="fa-solid fa-network-wired me-1"></i> CIDR Subnet
                </a>
                <a href="{{ route('blacklist.index') }}" class="btn btn-outline-danger rounded-pill px-3">
                    <i class="fa-solid fa-shield-halved me-1"></i> Blacklist
                </a>
                <button class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#bulkModal">
                    <i class="fa-solid fa-layer-group me-1"></i> Bulk Lookup
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- IP Search Box -->
        <div class="card p-3 mb-4">
            <div class="card-body">
                <form action="{{ route('user') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-globe text-primary"></i></span>
                                <input type="text" name="ip" class="form-control border-0 bg-light"
                                    placeholder="Enter IP Address (e.g. 8.8.8.8 or 1.1.1.1)" value="{{ request('ip') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Search IP
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($currentUserInfo)
            <div class="row g-4 mb-4">

                <!-- Left Column: IP Information & Threat Intelligence -->
                <div class="col-lg-6">
                    <div class="card info-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <div class="d-flex align-items-center">
                                <img src="{{ $flagUrl }}" class="flag-img me-3" alt="Flag">
                                <div>
                                    <h4 class="fw-bold m-0 text-dark">{{ $currentUserInfo->ip }}</h4>
                                    <small class="text-muted">{{ $currentUserInfo->cityName }}, {{ $currentUserInfo->countryName }}</small>
                                </div>
                            </div>
                            <span class="badge rounded-pill px-3 py-2 fs-6 badge-{{ strtolower(str_replace(' ', '', $riskScore)) }}">
                                🛡️ {{ $riskScore }}
                            </span>
                        </div>

                        <table class="table table-hover align-middle">
                            <tr>
                                <th width="35%"><i class="fa-solid fa-flag me-2 text-primary"></i>Country</th>
                                <td class="fw-bold">{{ $currentUserInfo->countryName }} ({{ strtoupper($currentUserInfo->countryCode ?? 'US') }})</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-building me-2 text-info"></i>Region / State</th>
                                <td>{{ $currentUserInfo->regionName }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-city me-2 text-success"></i>City</th>
                                <td class="fw-bold text-success">{{ $currentUserInfo->cityName }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-envelopes-bulk me-2 text-danger"></i>Zip Code</th>
                                <td>{{ $currentUserInfo->zipCode }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-server me-2 text-warning"></i>ISP Provider</th>
                                <td>{{ $isp }}</td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-clock me-2 text-primary"></i>Timezone Clock</th>
                                <td>
                                    <span class="fw-bold text-primary">{{ $timezone }}</span>
                                    <span class="badge bg-light text-dark border ms-2" id="liveClock">Loading clock...</span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fa-solid fa-location-crosshairs me-2 text-danger"></i>Coordinates</th>
                                <td class="font-monospace text-primary fw-bold">
                                    {{ $currentUserInfo->latitude }}, {{ $currentUserInfo->longitude }}
                                </td>
                            </tr>
                        </table>

                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <a href="https://www.google.com/maps?q={{ $currentUserInfo->latitude }},{{ $currentUserInfo->longitude }}"
                                target="_blank" class="btn btn-outline-success btn-sm rounded-pill">
                                📍 View on Google Maps
                            </a>

                            <a href="{{ route('export.geojson') }}" class="btn btn-outline-dark btn-sm rounded-pill">
                                🌐 Export GeoJSON
                            </a>
                            <a href="{{ route('export.kml') }}" class="btn btn-outline-warning btn-sm rounded-pill">
                                🗺️ Export KML (Google Earth)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive Leaflet.js Map Studio -->
                <div class="col-lg-6">
                    <div class="card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold m-0 text-dark"><i class="fa-solid fa-map-location-dot text-danger me-2"></i>Leaflet OpenStreetMap Radar</h5>
                            <small class="text-muted">Live Interactive Pin</small>
                        </div>

                        <!-- Leaflet Container -->
                        <div id="map"></div>
                    </div>
                </div>

            </div>
        @endif

    </div>

    <!-- Bulk IP Lookup Modal Form -->
    <div class="modal fade" id="bulkModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-layer-group text-primary me-2"></i>Bulk Batch IP Parser</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('bulk.lookup') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <label class="form-label fw-semibold">Paste IP Addresses (Comma or Newline separated)</label>
                        <textarea name="bulk_ips" class="form-control font-monospace" rows="6" placeholder="8.8.8.8&#10;1.1.1.1&#10;162.159.24.227"></textarea>
                        <small class="text-muted mt-1 d-block">Batch limit: up to 20 IPs per execution.</small>
                    </div>
                    <div class="modal-footer bg-light">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary px-4" type="submit">Process Batch IP Lookup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet.js OpenStreetMap Script -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @if($currentUserInfo)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $currentUserInfo->latitude }};
            const lng = {{ $currentUserInfo->longitude }};

            // Initialize Leaflet Map
            const map = L.map('map').setView([lat, lng], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Add Pin Marker with Custom Popup Card
            const popupContent = `
                <div style="font-family: system-ui; text-align: center; padding: 5px;">
                    <img src="{{ $flagUrl }}" style="width: 32px; height: 20px; border-radius: 3px;" />
                    <h6 style="margin: 5px 0 2px 0; font-weight: bold; color: #0d6efd;">{{ $currentUserInfo->ip }}</h6>
                    <p style="margin: 0; font-size: 12px; color: #666;">{{ $currentUserInfo->cityName }}, {{ $currentUserInfo->countryName }}</p>
                    <span style="font-size: 10px; background: #e2e8f0; padding: 2px 6px; border-radius: 10px; display: inline-block; margin-top: 4px;">ISP: {{ $isp }}</span>
                </div>
            `;

            L.marker([lat, lng]).addTo(map)
                .bindPopup(popupContent)
                .openPopup();

            // Real-Time Timezone Live Clock Ticker
            function updateClock() {
                try {
                    const now = new Date();
                    const options = { timeZone: "{{ $timezone }}", hour: '2-digit', minute: '2-digit', second: '2-digit' };
                    document.getElementById('liveClock').innerText = new Intl.DateTimeFormat([], options).format(now);
                } catch(e) {
                    document.getElementById('liveClock').innerText = new Date().toLocaleTimeString();
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>
    @endif

</body>

</html>