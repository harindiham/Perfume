<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController
{
    public function show(Request $request)
    {
        $validated = $request->validate([
            'from' => ['required', 'string', 'size:3'],
            'to' => ['required', 'string', 'size:3'],
        ]);

        $from = strtoupper($validated['from']);
        $to = strtoupper($validated['to']);

        if ($from === $to) {
            return response()->json([
                'success' => true,
                'from' => $from,
                'to' => $to,
                'rate' => 1,
            ]);
        }

        $response = Http::timeout(5)
            ->get("https://api.frankfurter.dev/v2/rate/{$from}/{$to}");

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to retrieve exchange rate.',
            ], 502);
        }

        $data = $response->json();

        if (!isset($data['rate'])) {
            return response()->json([
                'success' => false,
                'message' => 'Exchange rate not available for the requested currencies.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'from' => $data['base'],
            'to' => $data['quote'],
            'rate' => $data['rate'],
            'date' => $data['date'] ?? null,
        ]);
    }
}