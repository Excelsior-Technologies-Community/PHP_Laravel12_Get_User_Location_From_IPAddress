<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Analytics & Global Geo Heatmap Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .stat-box {
            border-left: 4px solid #0d6efd;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
        }

        #globalMap {
            height: 420px;
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
        }

        .flag-icon {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            object-fit: cover;
            vertical-align: middle;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary m-0">
                    📊 IP Analytics & Global Geo Radar Dashboard
                </h2>
                <p class="text-muted small m-0">Real-Time IP Intelligence, Threat Counters, and World Map Visualization</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('user') }}" class="btn btn-outline-primary rounded-pill">
                    <i class="fa-solid fa-location-dot me-1"></i> Search Tracker
                </a>
                <a href="{{ route('history') }}" class="btn btn-dark rounded-pill">
                    <i class="fa-solid fa-history me-1"></i> History
                </a>
                <a href="{{ route('subnet') }}" class="btn btn-outline-primary rounded-pill">
                    <i class="fa-solid fa-network-wired me-1"></i> CIDR Subnet
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-box border-primary">
                    <div class="text-muted small fw-bold">TOTAL SEARCHES</div>
                    <h3 class="fw-bold text-primary m-0">{{ $totalSearches }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-box border-success">
                    <div class="text-muted small fw-bold">UNIQUE COUNTRIES</div>
                    <h3 class="fw-bold text-success m-0">{{ $totalCountries }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-box border-info">
                    <div class="text-muted small fw-bold">UNIQUE CITIES</div>
                    <h3 class="fw-bold text-info m-0">{{ $totalCities }}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-box border-danger">
                    <div class="text-muted small fw-bold">THREAT LEVEL ALERTS 🛡️</div>
                    <h3 class="fw-bold text-danger m-0">{{ $threatCount }}</h3>
                </div>
            </div>
        </div>

        <!-- Global Multi-Pin Leaflet OpenStreetMap Radar -->
        <div class="card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-earth-americas text-primary me-2"></i>Global Geo Multi-Pin Map Radar</h5>
            <div id="globalMap"></div>
        </div>

        <!-- Top Countries and Cities -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-flag text-warning me-2"></i>Top Searched Countries</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($topCountries as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="https://flagcdn.com/w20/{{ strtolower($item->country_code ?? 'us') }}.png" class="flag-icon me-2" alt="Flag">
                                <strong>{{ $item->country }}</strong>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $item->total }} searches</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-city text-success me-2"></i>Top Searched Cities</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($topCities as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fa-solid fa-location-dot text-danger me-2"></i>
                                <strong>{{ $item->city }}</strong> <small class="text-muted">({{ $item->country }})</small>
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $item->total }} searches</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- Leaflet.js Script -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Global World Map
            const globalMap = L.map('globalMap').setView([20, 0], 2);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(globalMap);

            const mapPins = @js($mapPins);

            mapPins.forEach(pin => {
                const popup = `
                    <div style="font-family: system-ui; text-align: center;">
                        <img src="${pin.flag}" style="width: 20px; height: 14px; margin-bottom: 2px;" />
                        <h6 style="margin: 2px 0; font-weight: bold; color: #0d6efd;">${pin.ip}</h6>
                        <small style="color: #555;">${pin.city}, ${pin.country}</small><br>
                        <span style="font-size: 10px; font-weight: bold; color: ${pin.risk === 'Threat Level' ? '#dc3545' : '#198754'};">Risk: ${pin.risk}</span>
                    </div>
                `;

                L.marker([pin.lat, pin.lng]).addTo(globalMap)
                    .bindPopup(popup);
            });
        });
    </script>

</body>

</html>