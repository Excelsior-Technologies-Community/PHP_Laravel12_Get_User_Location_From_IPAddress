<?php

namespace App\Http\Controllers;

use App\Models\IpHistory;
use App\Models\IpBlacklist;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Home Page - Live IP Search & Leaflet Map Visual Radar
     */
    public function index(Request $request)
    {
        $ip = trim($request->input('ip', ''));

        if (!$ip) {
            $ip = $request->ip();
        }

        // For localhost testing
        if ($ip == "127.0.0.1" || $ip == "::1") {
            $ip = "162.159.24.227"; // Cloudflare DNS
        }

        $currentUserInfo = Location::get($ip);

        // Security & Threat Intelligence Resolution
        $riskScore = 'Safe';
        $isp = 'Cloudflare / Global ISP';
        $asn = 'AS13335';
        $timezone = 'UTC';
        $isVpnProxy = false;
        $isBlacklisted = false;

        if ($currentUserInfo) {
            // Check if IP is in Blacklist database
            if (IpBlacklist::where('ip', $currentUserInfo->ip)->exists()) {
                $isBlacklisted = true;
                $riskScore = 'Threat Level';
            }

            // Heuristic Threat Intelligence Rule Engine
            if ($currentUserInfo->countryCode === 'RU' || $currentUserInfo->countryCode === 'CN' || $isBlacklisted) {
                $riskScore = 'Threat Level';
            } elseif (filter_var($currentUserInfo->ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                $riskScore = 'Suspicious';
                $isVpnProxy = true;
            }

            // Estimate Timezone based on country
            $timezone = $this->estimateTimezone($currentUserInfo->countryCode);
            $isp = $this->estimateIsp($currentUserInfo->ip);

            // Save history
            IpHistory::create([
                'ip' => $currentUserInfo->ip,
                'country' => $currentUserInfo->countryName,
                'country_code' => strtolower($currentUserInfo->countryCode ?? 'us'),
                'region' => $currentUserInfo->regionName,
                'city' => $currentUserInfo->cityName,
                'zip' => $currentUserInfo->zipCode,
                'latitude' => $currentUserInfo->latitude,
                'longitude' => $currentUserInfo->longitude,
                'isp' => $isp,
                'asn' => $asn,
                'timezone' => $timezone,
                'risk_score' => $riskScore,
                'is_vpn_proxy' => $isVpnProxy,
                'is_blacklisted' => $isBlacklisted,
            ]);
        }

        $flagUrl = $currentUserInfo && $currentUserInfo->countryCode 
            ? 'https://flagcdn.com/w40/' . strtolower($currentUserInfo->countryCode) . '.png' 
            : 'https://flagcdn.com/w40/us.png';

        return view('user', compact(
            'currentUserInfo',
            'riskScore',
            'isp',
            'asn',
            'timezone',
            'isVpnProxy',
            'isBlacklisted',
            'flagUrl'
        ));
    }

    /**
     * Dashboard & Global Heatmap / Multi-Pin Map Studio
     */
    public function dashboard()
    {
        $totalSearches = IpHistory::count();
        $totalCountries = IpHistory::distinct('country')->count('country');
        $totalCities = IpHistory::distinct('city')->count('city');
        $todaySearches = IpHistory::whereDate('created_at', today())->count();
        $threatCount = IpHistory::where('risk_score', 'Threat Level')->count();

        // Top Countries
        $topCountries = IpHistory::selectRaw('country, country_code, COUNT(*) as total')
            ->whereNotNull('country')
            ->groupBy('country', 'country_code')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top Cities
        $topCities = IpHistory::selectRaw('city, country, COUNT(*) as total')
            ->whereNotNull('city')
            ->groupBy('city', 'country')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // All Map Pins for Global Leaflet Heatmap
        $mapPins = IpHistory::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->limit(100)
            ->get()
            ->map(function ($h) {
                return [
                    'ip' => $h->ip,
                    'city' => $h->city ?? 'Unknown',
                    'country' => $h->country ?? 'Unknown',
                    'lat' => (float)$h->latitude,
                    'lng' => (float)$h->longitude,
                    'risk' => $h->risk_score,
                    'flag' => 'https://flagcdn.com/w20/' . strtolower($h->country_code ?? 'us') . '.png',
                ];
            });

        return view('dashboard', compact(
            'totalSearches',
            'totalCountries',
            'totalCities',
            'todaySearches',
            'threatCount',
            'topCountries',
            'topCities',
            'mapPins'
        ));
    }

    /**
     * History Page
     */
    public function history(Request $request)
    {
        $query = IpHistory::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ip', 'like', '%' . $request->search . '%')
                    ->orWhere('country', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%')
                    ->orWhere('region', 'like', '%' . $request->search . '%')
                    ->orWhere('isp', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->risk) {
            $query->where('risk_score', $request->risk);
        }

        $histories = $query->latest()->paginate(10);

        return view('history', compact('histories'));
    }

    /**
     * Bulk Batch IP Lookup Parser
     */
    public function bulkLookup(Request $request)
    {
        $rawIps = $request->input('bulk_ips', '');
        $ipList = preg_split('/[\s,]+/', $rawIps, -1, PREG_SPLIT_NO_EMPTY);
        $processed = 0;

        foreach (array_slice($ipList, 0, 20) as $ip) {
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $info = Location::get($ip);
                if ($info) {
                    IpHistory::create([
                        'ip' => $info->ip,
                        'country' => $info->countryName,
                        'country_code' => strtolower($info->countryCode ?? 'us'),
                        'region' => $info->regionName,
                        'city' => $info->cityName,
                        'zip' => $info->zipCode,
                        'latitude' => $info->latitude,
                        'longitude' => $info->longitude,
                        'isp' => $this->estimateIsp($info->ip),
                        'risk_score' => 'Safe',
                    ]);
                    $processed++;
                }
            }
        }

        return redirect()->route('history')->with('success', "Batch operation completed. Processed {$processed} IP addresses.");
    }

    /**
     * CIDR Subnet & Range Calculator Studio
     */
    public function subnetCalculator(Request $request)
    {
        $cidr = $request->input('cidr', '192.168.1.0/24');
        $subnetData = null;

        if (str_contains($cidr, '/')) {
            list($ip, $prefix) = explode('/', $cidr);
            $prefix = (int)$prefix;

            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $prefix >= 0 && $prefix <= 32) {
                $ipLong = ip2long($ip);
                $maskLong = -1 << (32 - $prefix);
                $netLong = $ipLong & $maskLong;
                $bcastLong = $netLong | ~$maskLong;
                $totalHosts = max(0, pow(2, 32 - $prefix) - 2);

                $subnetData = [
                    'cidr' => $cidr,
                    'network_ip' => long2ip($netLong),
                    'netmask' => long2ip($maskLong),
                    'wildcard' => long2ip(~$maskLong),
                    'broadcast_ip' => long2ip($bcastLong),
                    'first_host' => long2ip($netLong + 1),
                    'last_host' => long2ip($bcastLong - 1),
                    'total_hosts' => number_format($totalHosts),
                ];
            }
        }

        return view('subnet', compact('cidr', 'subnetData'));
    }

    /**
     * IP Blacklist Control Panel
     */
    public function blacklistIndex()
    {
        $blacklists = IpBlacklist::latest()->get();
        return view('blacklist', compact('blacklists'));
    }

    public function blacklistStore(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip|unique:ip_blacklists,ip',
            'reason' => 'nullable|string|max:255',
        ]);

        IpBlacklist::create([
            'ip' => $request->ip,
            'reason' => $request->reason ?? 'Manual Administrator Blacklist',
        ]);

        return redirect()->back()->with('success', "IP {$request->ip} added to security blacklist.");
    }

    public function blacklistDestroy($id)
    {
        IpBlacklist::findOrFail($id)->delete();
        return redirect()->back()->with('success', "IP removed from blacklist.");
    }

    /**
     * GeoJSON Exporter
     */
    public function exportGeoJson()
    {
        $histories = IpHistory::whereNotNull('latitude')->whereNotNull('longitude')->get();

        $features = [];
        foreach ($histories as $h) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$h->longitude, (float)$h->latitude]
                ],
                'properties' => [
                    'ip' => $h->ip,
                    'city' => $h->city,
                    'country' => $h->country,
                    'risk_score' => $h->risk_score,
                    'isp' => $h->isp
                ]
            ];
        }

        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        return response()->json($geoJson, 200, [
            'Content-Disposition' => 'attachment; filename="ip_locations.geojson"'
        ]);
    }

    /**
     * KML Google Earth Exporter
     */
    public function exportKml()
    {
        $histories = IpHistory::whereNotNull('latitude')->whereNotNull('longitude')->get();

        $kml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2">' . "\n";
        $kml .= '  <Document>' . "\n";
        $kml .= '    <name>IP Geo Location Radar</name>' . "\n";

        foreach ($histories as $h) {
            $kml .= '    <Placemark>' . "\n";
            $kml .= '      <name>' . htmlspecialchars($h->ip) . ' (' . htmlspecialchars($h->city ?? 'Unknown') . ')</name>' . "\n";
            $kml .= '      <description>' . htmlspecialchars("Country: {$h->country} | Risk: {$h->risk_score}") . '</description>' . "\n";
            $kml .= '      <Point>' . "\n";
            $kml .= "        <coordinates>{$h->longitude},{$h->latitude},0</coordinates>\n";
            $kml .= '      </Point>' . "\n";
            $kml .= '    </Placemark>' . "\n";
        }

        $kml .= '  </Document>' . "\n";
        $kml .= '</kml>';

        return response($kml, 200, [
            'Content-Type' => 'application/vnd.google-earth.kml+xml',
            'Content-Disposition' => 'attachment; filename="ip_locations.kml"'
        ]);
    }

    /**
     * Export History CSV & XLSX / PDF Print View
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = IpHistory::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ip', 'like', '%' . $request->search . '%')
                    ->orWhere('country', 'like', '%' . $request->search . '%')
                    ->orWhere('city', 'like', '%' . $request->search . '%');
            });
        }

        $histories = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ip-history.csv"',
        ];

        $callback = function () use ($histories) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'IP Address', 'Country', 'Country Code', 'Region', 'City', 
                'ZIP', 'Latitude', 'Longitude', 'ISP', 'Timezone', 'Risk Score', 'Created At'
            ]);

            foreach ($histories as $history) {
                fputcsv($file, [
                    $history->ip,
                    $history->country,
                    strtoupper($history->country_code),
                    $history->region,
                    $history->city,
                    $history->zip,
                    $history->latitude,
                    $history->longitude,
                    $history->isp ?? 'N/A',
                    $history->timezone ?? 'N/A',
                    $history->risk_score,
                    $history->created_at->format('d-m-Y H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $histories = IpHistory::latest()->get();
        return view('exports.ip_pdf', compact('histories'));
    }

    public function jsonApi()
    {
        return response()->json([
            'status' => 'success',
            'data' => IpHistory::latest()->limit(50)->get()
        ]);
    }

    public function destroy($id)
    {
        IpHistory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'History deleted successfully.');
    }

    // Helper functions
    private function estimateTimezone($countryCode)
    {
        $zones = [
            'US' => 'America/New_York',
            'IN' => 'Asia/Kolkata',
            'GB' => 'Europe/London',
            'DE' => 'Europe/Berlin',
            'CA' => 'America/Toronto',
            'AU' => 'Australia/Sydney',
            'JP' => 'Asia/Tokyo',
        ];
        return $zones[strtoupper($countryCode ?? '')] ?? 'UTC';
    }

    private function estimateIsp($ip)
    {
        if (str_starts_with($ip, '8.8.') || str_starts_with($ip, '8.34.')) return 'Google LLC';
        if (str_starts_with($ip, '1.1.1.') || str_starts_with($ip, '162.159.')) return 'Cloudflare Inc.';
        if (str_starts_with($ip, '208.67.')) return 'OpenDNS / Cisco';
        return 'Telecom / Broadband Network Provider';
    }
}
