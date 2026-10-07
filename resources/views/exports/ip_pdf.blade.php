<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IP Location & Security Intelligence Report</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fff; color: #333; margin: 30px; }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 15px; margin-bottom: 25px; }
        .header h2 { margin: 0; color: #0d6efd; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; color: #1e293b; font-weight: 600; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; color: #fff; }
        .badge-safe { background: #198754; }
        .badge-threat { background: #dc3545; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>🌍 IP Location & Security Intelligence Report</h2>
        <p>Generated on {{ date('d M Y, h:i A') }} | Total Records: {{ $histories->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Country</th>
                <th>City & Region</th>
                <th>ISP / Network</th>
                <th>Risk Score</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
            <tr>
                <td><strong>{{ $history->ip }}</strong></td>
                <td>{{ $history->country }} ({{ strtoupper($history->country_code) }})</td>
                <td>{{ $history->city }}, {{ $history->region }}</td>
                <td>{{ $history->isp ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $history->risk_score === 'Threat Level' ? 'badge-threat' : 'badge-safe' }}">
                        {{ $history->risk_score }}
                    </span>
                </td>
                <td>{{ $history->created_at->format('d M Y h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laravel 12 IP Location Radar & Threat Intelligence Report
    </div>

</body>
</html>
