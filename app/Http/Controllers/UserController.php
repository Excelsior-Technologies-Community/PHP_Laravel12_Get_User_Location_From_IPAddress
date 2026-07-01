<?php

namespace App\Http\Controllers;

use App\Models\IpHistory;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class UserController extends Controller
{
    /**
     * Home Page
     */
    public function index(Request $request)
    {
        // User entered IP, otherwise use current user's IP
        $ip = $request->ip;

        if (!$ip) {
            $ip = $request->ip();
        }

        // For localhost testing
        if ($ip == "127.0.0.1" || $ip == "::1") {
            $ip = "162.159.24.227";
        }

        $currentUserInfo = Location::get($ip);

        // Save history
        if ($currentUserInfo) {

            IpHistory::create([
                'ip' => $currentUserInfo->ip,
                'country' => $currentUserInfo->countryName,
                'country_code' => $currentUserInfo->countryCode,
                'region' => $currentUserInfo->regionName,
                'city' => $currentUserInfo->cityName,
                'zip' => $currentUserInfo->zipCode,
                'latitude' => $currentUserInfo->latitude,
                'longitude' => $currentUserInfo->longitude,
            ]);
        }

        return view('user', compact('currentUserInfo'));
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        $totalSearches = IpHistory::count();

        $totalCountries = IpHistory::distinct('country')->count('country');

        $totalCities = IpHistory::distinct('city')->count('city');

        $todaySearches = IpHistory::whereDate(
            'created_at',
            today()
        )->count();

        return view('dashboard', compact(
            'totalSearches',
            'totalCountries',
            'totalCities',
            'todaySearches'
        ));
    }

    /**
     * History
     */
    public function history(Request $request)
    {
        $query = IpHistory::query();

        if ($request->search) {

            $query->where('ip', 'like', '%' . $request->search . '%')
                ->orWhere('country', 'like', '%' . $request->search . '%')
                ->orWhere('city', 'like', '%' . $request->search . '%')
                ->orWhere('region', 'like', '%' . $request->search . '%');
        }

        $histories = $query
            ->oldest()
            ->paginate(5);

        return view('history', compact('histories'));
    }

    /**
     * Delete History
     */
    public function destroy($id)
    {
        IpHistory::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'History deleted successfully.');
    }
}