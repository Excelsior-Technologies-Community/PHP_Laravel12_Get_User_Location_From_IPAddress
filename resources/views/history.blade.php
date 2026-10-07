<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Search History & Multi-Format Exporters</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

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

        .flag-icon {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            object-fit: cover;
        }

        .badge-safe { background-color: #198754; }
        .badge-threat { background-color: #dc3545; }
        .badge-suspicious { background-color: #ffc107; color: #212529; }
    </style>
</head>

<body>

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark m-0">
                    📜 IP Search History & Exporter Studio
                </h2>
                <p class="text-muted small m-0">Comprehensive Audit Trail Log and Multi-Format Exports</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('user') }}" class="btn btn-outline-primary rounded-pill">
                    <i class="fa-solid fa-location-dot me-1"></i> Search Tracker
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-success rounded-pill">
                    <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <form action="{{ route('history') }}" method="GET" class="d-flex gap-2 flex-grow-1">
                    <input type="text" name="search" class="form-control rounded-pill" placeholder="🔍 Search IP, Country, City, Region, ISP..." value="{{ request('search') }}">
                    <select name="risk" class="form-select rounded-pill" style="width: 180px;">
                        <option value="">All Risk Levels</option>
                        <option value="Safe" {{ request('risk')=='Safe'?'selected':'' }}>Safe</option>
                        <option value="Threat Level" {{ request('risk')=='Threat Level'?'selected':'' }}>Threat Level 🛡️</option>
                    </select>
                    <button class="btn btn-primary rounded-pill px-4">Search</button>
                    <a href="{{ route('history') }}" class="btn btn-secondary rounded-pill">Reset</a>
                </form>

                <!-- Multi-Format Exporter Studio -->
                <div class="btn-group">
                    <button class="btn btn-outline-success dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-file-export me-1"></i> Exporter Studio
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a href="{{ route('history.export.csv', request()->query()) }}" class="dropdown-item"><i class="fa-solid fa-file-csv text-success me-2"></i> Export CSV</a></li>
                        <li><a href="{{ route('history.export.pdf', request()->query()) }}" target="_blank" class="dropdown-item"><i class="fa-solid fa-file-pdf text-danger me-2"></i> Print / PDF Report</a></li>
                        <li><a href="{{ route('export.geojson') }}" class="dropdown-item"><i class="fa-solid fa-globe text-primary me-2"></i> GeoJSON Map Export</a></li>
                        <li><a href="{{ route('export.kml') }}" class="dropdown-item"><i class="fa-solid fa-earth-americas text-warning me-2"></i> Google Earth KML Sync</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="{{ route('api.history') }}" target="_blank" class="dropdown-item"><i class="fa-solid fa-code text-info me-2"></i> Restful JSON API Endpoint</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>IP Address</th>
                            <th>Country & Location</th>
                            <th>ISP / Network</th>
                            <th>Coordinates</th>
                            <th>Risk Score</th>
                            <th>Searched At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                        <tr>
                            <td class="fw-bold text-primary">{{ $history->ip }}</td>
                            <td>
                                <img src="https://flagcdn.com/w20/{{ strtolower($history->country_code ?? 'us') }}.png" class="flag-icon me-1" alt="Flag">
                                <strong>{{ $history->country }}</strong> <small class="text-muted">({{ $history->city }}, {{ $history->region }})</small>
                            </td>
                            <td><small class="text-secondary">{{ $history->isp ?? 'Cloudflare / Global Telecom' }}</small></td>
                            <td class="font-monospace small">{{ $history->latitude }}, {{ $history->longitude }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-1 badge-{{ strtolower(str_replace(' ', '', $history->risk_score ?? 'Safe')) }}">
                                    {{ $history->risk_score }}
                                </span>
                            </td>
                            <td>{{ $history->created_at->format('d M Y h:i A') }}</td>
                            <td>
                                <form action="{{ route('history.delete', $history->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Delete record?')">
                                        <i class="fa-solid fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No history records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($histories->lastPage() > 1)
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    @for ($i = 1; $i <= $histories->lastPage(); $i++)
                        <li class="page-item {{ $histories->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $histories->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                </ul>
            </nav>
            @endif
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>