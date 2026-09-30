<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IpCheckController extends Controller
{
    /**
     * Display the IP abuse checker page.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Check an IP address against AbuseIPDB.
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'ip' => [
                'required',
                'ip',
            ],
        ]);

        $ip = $validated['ip'];

        /*
        |--------------------------------------------------------------------------
        | AbuseIPDB API Configuration
        |--------------------------------------------------------------------------
        |
        | Add your API key to .env:
        |
        | ABUSEIPDB_API_KEY=your_api_key
        |
        */

        $apiKey = config('services.abuseipdb.key');

        if (!$apiKey) {
            return back()
                ->withErrors([
                    'ip' => 'AbuseIPDB API key is not configured.',
                ])
                ->withInput();
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Key' => $apiKey,
                    'Accept' => 'application/json',
                ])
                ->get(
                    'https://api.abuseipdb.com/api/v2/check',
                    [
                        'ipAddress' => $ip,
                        'maxAgeInDays' => 90,
                    ]
                );

            if ($response->failed()) {
                return back()
                    ->withErrors([
                        'ip' => 'Unable to check this IP address. AbuseIPDB returned an error.',
                    ])
                    ->withInput();
            }

            $apiData = $response->json();

            $data = $apiData['data'] ?? [];

            return view('welcome', [
                'data' => [
                    'ipAddress' => $data['ipAddress'] ?? $ip,

                    'abuseConfidenceScore' =>
                        $data['abuseConfidenceScore'] ?? 0,

                    'countryName' =>
                        $data['countryName']
                        ?? $data['countryCode']
                        ?? 'Unknown',

                    'usageType' =>
                        $data['usageType']
                        ?? 'Unknown',

                    'isp' =>
                        $data['isp']
                        ?? 'Unknown',

                    'domain' =>
                        $data['domain']
                        ?? 'Unknown',

                    'totalReports' =>
                        $data['totalReports']
                        ?? 0,

                    'lastReportedAt' =>
                        $data['lastReportedAt']
                        ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            return back()
                ->withErrors([
                    'ip' => 'An error occurred while checking the IP address. Please try again.',
                ])
                ->withInput();
        }
    }
}