<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Location Tracker</title>

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

        .info-card {
            transition: .3s;
        }

        .info-card:hover {
            transform: translateY(-4px);
        }

        .title {
            font-size: 32px;
            font-weight: bold;
        }

        .value {
            font-size: 18px;
            color: #0d6efd;
            font-weight: 600;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="fw-bold text-primary">
                    🌍 Laravel IP Location Tracker
                </h2>

                <p class="text-muted">
                    Search any IP Address and view its location.
                </p>

            </div>

            <div>

                <a href="{{ route('dashboard') }}" class="btn btn-success">
                    Dashboard
                </a>

                <a href="{{ route('history') }}" class="btn btn-dark">
                    History
                </a>

            </div>

        </div>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <div class="card mb-4">

            <div class="card-body">

                <form action="{{ route('user') }}" method="GET">

                    <div class="row">

                        <div class="col-md-10">

                            <input type="text" name="ip" class="form-control form-control-lg"
                                placeholder="Enter IP Address (Example : 8.8.8.8)" value="{{ request('ip') }}">

                        </div>

                        <div class="col-md-2">

                            <button class="btn btn-primary btn-lg w-100">

                                Search

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        @if($currentUserInfo)

            <div class="row">

                <div class="col-md-6 mb-4">

                    <div class="card info-card">

                        <div class="card-header bg-primary text-white">

                            IP Information

                        </div>

                        <div class="card-body">

                            <table class="table">

                                <tr>

                                    <th width="40%">
                                        IP Address
                                    </th>

                                    <td>

                                        {{ $currentUserInfo->ip }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>
                                        Country
                                    </th>

                                    <td>

                                        {{ $currentUserInfo->countryName }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Country Code

                                    </th>

                                    <td>

                                        {{ $currentUserInfo->countryCode }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Region

                                    </th>

                                    <td>

                                        {{ $currentUserInfo->regionName }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        City

                                    </th>

                                    <td>

                                        {{ $currentUserInfo->cityName }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Zip Code

                                    </th>

                                    <td>

                                        {{ $currentUserInfo->zipCode }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Latitude

                                    </th>

                                    <td class="text-primary fw-bold">

                                        {{ $currentUserInfo->latitude }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>

                                        Longitude

                                    </th>

                                    <td class="text-primary fw-bold">

                                        {{ $currentUserInfo->longitude }}

                                    </td>

                                </tr>

                            </table>

                            <div class="mt-3">

                                <a href="https://www.google.com/maps?q={{ $currentUserInfo->latitude }},{{ $currentUserInfo->longitude }}"
                                    target="_blank" class="btn btn-success">

                                    📍 View on Google Maps

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="card info-card">

                        <div class="card-header bg-dark text-white">

                            Location Summary

                        </div>

                        <div class="card-body">

                            <div class="row text-center">

                                <div class="col-6 mb-4">

                                    <h6 class="text-muted">

                                        Country

                                    </h6>

                                    <h4 class="text-primary">

                                        {{ $currentUserInfo->countryName }}

                                    </h4>

                                </div>

                                <div class="col-6 mb-4">

                                    <h6 class="text-muted">

                                        City

                                    </h6>

                                    <h4 class="text-success">

                                        {{ $currentUserInfo->cityName }}

                                    </h4>

                                </div>

                                <div class="col-6 mb-4">

                                    <h6 class="text-muted">

                                        Region

                                    </h6>

                                    <h4 class="text-warning">

                                        {{ $currentUserInfo->regionName }}

                                    </h4>

                                </div>

                                <div class="col-6 mb-4">

                                    <h6 class="text-muted">

                                        ZIP Code

                                    </h6>

                                    <h4 class="text-danger">

                                        {{ $currentUserInfo->zipCode }}

                                    </h4>

                                </div>

                            </div>

                            <hr>

                            <h5 class="mb-3">

                                Coordinates

                            </h5>

                            <div class="alert alert-info">

                                <strong>Latitude:</strong>

                                {{ $currentUserInfo->latitude }}

                                <br>

                                <strong>Longitude:</strong>

                                {{ $currentUserInfo->longitude }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

        <div class="text-center mt-5">

            <hr>

            <p class="text-muted mb-0">
                Laravel 12 IP Location Tracker
            </p>

            <small class="text-secondary">
                Search • Dashboard • History • Bootstrap 5
            </small>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>