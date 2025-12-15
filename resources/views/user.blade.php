<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>How to Get Current User Location with Laravel 12</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  
<div class="container">
    <div class="card mt-5">
        <h3 class="card-header p-3">How to Get Current User Location with Laravel 12</h3>
        <div class="card-body">
            @if($currentUserInfo)
                <p><strong>IP:</strong> {{ $currentUserInfo->ip }}</p>
                <p><strong>Country Name:</strong> {{ $currentUserInfo->countryName }}</p>
                <p><strong>Country Code:</strong> {{ $currentUserInfo->countryCode }}</p>
                <p><strong>Region Code:</strong> {{ $currentUserInfo->regionCode }}</p>
                <p><strong>Region Name:</strong> {{ $currentUserInfo->regionName }}</p>
                <p><strong>City Name:</strong> {{ $currentUserInfo->cityName }}</p>
                <p><strong>Zip Code:</strong> {{ $currentUserInfo->zipCode }}</p>
                <p><strong>Latitude:</strong> {{ $currentUserInfo->latitude }}</p>
                <p><strong>Longitude:</strong> {{ $currentUserInfo->longitude }}</p>
            @endif
        </div>
    </div>
</div>
  
</body>
</html>