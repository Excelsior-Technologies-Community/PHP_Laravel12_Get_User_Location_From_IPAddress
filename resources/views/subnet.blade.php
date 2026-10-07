<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIDR Subnet Calculator & Range Studio</title>
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
                <h2 class="fw-bold text-primary m-0"><i class="fa-solid fa-network-wired me-2"></i>CIDR Subnet & Range Calculator</h2>
                <p class="text-muted small m-0">Calculate Subnet Masks, Network Ranges, Wildcard, and Usable IPs</p>
            </div>
            <div>
                <a href="{{ route('user') }}" class="btn btn-outline-primary rounded-pill"><i class="fa-solid fa-location-dot me-1"></i>IP Tracker</a>
                <a href="{{ route('dashboard') }}" class="btn btn-success rounded-pill"><i class="fa-solid fa-chart-line me-1"></i>Dashboard</a>
                <a href="{{ route('history') }}" class="btn btn-dark rounded-pill"><i class="fa-solid fa-history me-1"></i>History</a>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <form action="{{ route('subnet') }}" method="GET" class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="cidr" class="form-control form-control-lg rounded-pill" value="{{ $cidr }}" placeholder="Enter CIDR (e.g. 192.168.1.0/24 or 8.8.8.0/24)">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-lg rounded-pill w-100"><i class="fa-solid fa-calculator me-1"></i>Calculate Subnet</button>
                </div>
            </form>
        </div>

        @if($subnetData)
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>Subnet Breakdown</h5>
                    <table class="table align-middle">
                        <tr>
                            <th width="40%">CIDR Notation</th>
                            <td class="fw-bold text-primary">{{ $subnetData['cidr'] }}</td>
                        </tr>
                        <tr>
                            <th>Network Address</th>
                            <td class="fw-bold text-dark">{{ $subnetData['network_ip'] }}</td>
                        </tr>
                        <tr>
                            <th>Subnet Mask</th>
                            <td>{{ $subnetData['netmask'] }}</td>
                        </tr>
                        <tr>
                            <th>Wildcard Mask</th>
                            <td>{{ $subnetData['wildcard'] }}</td>
                        </tr>
                        <tr>
                            <th>Broadcast Address</th>
                            <td class="text-danger fw-bold">{{ $subnetData['broadcast_ip'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-4">
                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-server text-success me-2"></i>Usable Host Range</h5>
                    <table class="table align-middle">
                        <tr>
                            <th width="40%">First Usable Host</th>
                            <td class="text-success fw-bold">{{ $subnetData['first_host'] }}</td>
                        </tr>
                        <tr>
                            <th>Last Usable Host</th>
                            <td class="text-success fw-bold">{{ $subnetData['last_host'] }}</td>
                        </tr>
                        <tr>
                            <th>Total Usable Hosts</th>
                            <td class="fw-bold fs-4 text-primary">{{ $subnetData['total_hosts'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
