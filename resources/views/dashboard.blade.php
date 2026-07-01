<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .number {
            font-size: 35px;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold text-success">

                    📊 Dashboard

                </h2>

                <p class="text-muted">

                    IP Location Statistics

                </p>

            </div>

            <div>

                <a href="{{ route('user') }}" class="btn btn-primary">

                    🔍 Search IP

                </a>

                <a href="{{ route('history') }}" class="btn btn-dark">

                    📜 History

                </a>

            </div>

        </div>

        <div class="row">

            <div class="col-md-3 mb-4">

                <div class="card">

                    <div class="card-body text-center">

                        <h6>Total Searches</h6>

                        <div class="number text-primary">

                            {{ $totalSearches }}

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">

                    <div class="card-body text-center">

                        <h6>Countries</h6>

                        <div class="number text-success">

                            {{ $totalCountries }}

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">

                    <div class="card-body text-center">

                        <h6>Cities</h6>

                        <div class="number text-warning">

                            {{ $totalCities }}

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="card">

                    <div class="card-body text-center">

                        <h6>Today's Searches</h6>

                        <div class="number text-danger">

                            {{ $todaySearches }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row mt-3">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">
                            📈 Dashboard Summary
                        </h5>

                    </div>

                    <div class="card-body">

                        <p class="mb-2">
                            <strong>Total Searches:</strong>
                            {{ $totalSearches }}
                        </p>

                        <p class="mb-2">
                            <strong>Countries Found:</strong>
                            {{ $totalCountries }}
                        </p>

                        <p class="mb-2">
                            <strong>Cities Found:</strong>
                            {{ $totalCities }}
                        </p>

                        <p class="mb-0">
                            <strong>Today's Searches:</strong>
                            {{ $todaySearches }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <div class="text-center mt-5">

            <hr>

            <h6 class="text-muted">
                Laravel 12 IP Location Tracker
            </h6>

            <small class="text-secondary">
                Dashboard • Statistics • Search History
            </small>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>