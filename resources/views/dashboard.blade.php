<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fb, #eef2f7);
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .number {
            font-size: 34px;
            font-weight: bold;
        }

        .chart-box {
            position: relative;
            height: 340px;
            width: 100%;
        }

        canvas {
            max-width: 100%;
            max-height: 100%;
        }

        .header-title {
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .stat-card h6 {
            color: #6c757d;
        }
    </style>

</head>

<body>

<div class="container py-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="header-title text-success">📊 IP Analytics Dashboard</h2>
            <p class="text-muted mb-0">Real-time location tracking insights</p>
        </div>

        <div>
            <a href="{{ route('user') }}" class="btn btn-primary me-2">🔍 Search IP</a>
            <a href="{{ route('history') }}" class="btn btn-dark">📜 History</a>
        </div>

    </div>

    <!-- STATS -->
    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card p-3 stat-card text-center">
                <h6>Total Searches</h6>
                <div class="number text-primary">{{ $totalSearches }}</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card p-3 stat-card text-center">
                <h6>Countries</h6>
                <div class="number text-success">{{ $totalCountries }}</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card p-3 stat-card text-center">
                <h6>Cities</h6>
                <div class="number text-warning">{{ $totalCities }}</div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card p-3 stat-card text-center">
                <h6>Today's Searches</h6>
                <div class="number text-danger">{{ $todaySearches }}</div>
            </div>
        </div>

    </div>

    <!-- SUMMARY -->
    <div class="card p-4 mb-4">
        <h5 class="mb-3">📈 Dashboard Summary</h5>

        <div class="row">
            <div class="col-md-3"><strong>Total:</strong> {{ $totalSearches }}</div>
            <div class="col-md-3"><strong>Countries:</strong> {{ $totalCountries }}</div>
            <div class="col-md-3"><strong>Cities:</strong> {{ $totalCities }}</div>
            <div class="col-md-3"><strong>Today:</strong> {{ $todaySearches }}</div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="row">

        <!-- COUNTRY CHART -->
        <div class="col-lg-6 mb-4">
            <div class="card p-3">
                <h5 class="mb-3">🌍 Top 5 Countries</h5>
                <div class="chart-box">
                    <canvas id="countryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- CITY CHART -->
        <div class="col-lg-6 mb-4">
            <div class="card p-3">
                <h5 class="mb-3">🏙 Top 5 Cities</h5>
                <div class="chart-box">
                    <canvas id="cityChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-4 text-muted">
        <hr>
        Laravel 12 IP Tracker • Analytics Dashboard
    </div>

</div>

<script>
    const countryLabels = @json($topCountries->pluck('country'));
    const countryData = @json($topCountries->pluck('total'));

    new Chart(document.getElementById('countryChart'), {
        type: 'bar',
        data: {
            labels: countryLabels,
            datasets: [{
                label: 'Searches',
                data: countryData,
                backgroundColor: '#198754',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: "#eee" } },
                x: { grid: { display: false } }
            }
        }
    });

    const cityLabels = @json($topCities->pluck('city'));
    const cityData = @json($topCities->pluck('total'));

    new Chart(document.getElementById('cityChart'), {
        type: 'doughnut',
        data: {
            labels: cityLabels,
            datasets: [{
                data: cityData,
                backgroundColor: [
                    '#0d6efd',
                    '#198754',
                    '#ffc107',
                    '#dc3545',
                    '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

</body>
</html>