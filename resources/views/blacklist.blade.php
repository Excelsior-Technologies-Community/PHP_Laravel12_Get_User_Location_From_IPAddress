<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Blacklist & Access Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f0f4f8; font-family: 'Segoe UI', system-ui, sans-serif; }
        .card { border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-danger m-0"><i class="fa-solid fa-shield-halved me-2"></i>IP Blacklist & Threat Radar Control Panel</h2>
                <p class="text-muted small m-0">Manage blocked IP addresses and security restrictions</p>
            </div>
            <div>
                <a href="{{ route('user') }}" class="btn btn-outline-primary rounded-pill"><i class="fa-solid fa-location-dot me-1"></i>IP Tracker</a>
                <a href="{{ route('dashboard') }}" class="btn btn-success rounded-pill"><i class="fa-solid fa-chart-line me-1"></i>Dashboard</a>
                <a href="{{ route('history') }}" class="btn btn-dark rounded-pill"><i class="fa-solid fa-history me-1"></i>History</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-ban text-danger me-2"></i>Blacklist IP Address</h5>
                    <form action="{{ route('blacklist.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Target IP Address</label>
                            <input type="text" name="ip" class="form-control" placeholder="e.g. 192.168.1.100" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reason / Threat Note</label>
                            <input type="text" name="reason" class="form-control" placeholder="e.g. Brute-force attacks / Malicious traffic">
                        </div>
                        <button type="submit" class="btn btn-danger w-100 rounded-pill"><i class="fa-solid fa-lock me-1"></i>Add to Blacklist</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-ul me-2"></i>Active Security Blacklist Registry</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Reason</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blacklists as $item)
                                <tr>
                                    <td class="fw-bold text-danger">{{ $item->ip }}</td>
                                    <td>{{ $item->reason }}</td>
                                    <td>{{ $item->created_at->format('d M Y h:i A') }}</td>
                                    <td>
                                        <form action="{{ route('blacklist.delete', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="return confirm('Remove IP from blacklist?')"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No blacklisted IP addresses registered.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
