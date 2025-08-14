<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    public function getAmadeusAccessToken()
    {
        $response = Http::asForm()->post('https://test.api.amadeus.com/v1/security/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('AMADEUS_CLIENT_ID'),
            'client_secret' => env('AMADEUS_CLIENT_SECRET'),
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];  // Guardar o usar el access token
        }

        return null;
    }
    
    public function search(Request $request)
{
    $request->validate([
        'origen'      => 'required|string|size:3',
        'destino'     => 'required|string|size:3',
        'fecha'       => 'required|date|after_or_equal:today',
        'returnDate'  => 'nullable|date|after_or_equal:fecha',
        'adults'      => 'required|integer|min:1|max:9',
        'children'    => 'nullable|integer|min:0|max:8',
        'infants'     => 'nullable|integer|min:0|max:8',
        'nonStop'     => 'nullable|in:true,false',
        'travelClass' => 'nullable|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
    ]);

    // 1) token
    $tokenResponse = Http::asForm()->post('https://test.api.amadeus.com/v1/security/oauth2/token', [
        'grant_type'    => 'client_credentials',
        'client_id'     => env('AMADEUS_CLIENT_ID'),
        'client_secret' => env('AMADEUS_CLIENT_SECRET'),
    ]);

    if (!$tokenResponse->successful()) {
        return response()->json(['error' => 'Error autenticando con Amadeus'], 500);
    }
    $accessToken = $tokenResponse->json('access_token');

    // 2) armar query
    $query = [
        'originLocationCode'      => strtoupper($request->origen),
        'destinationLocationCode' => strtoupper($request->destino),
        'departureDate'           => $request->fecha,
        'adults'                  => (int)$request->adults,
        'currencyCode'            => 'USD',
        'max'                     => 10,
    ];
    if ($request->filled('returnDate')) $query['returnDate'] = $request->returnDate;
    if ($request->filled('children') && (int)$request->children > 0) $query['children'] = (int)$request->children;
    if ($request->filled('infants') && (int)$request->infants > 0)   $query['infants']  = (int)$request->infants;
    if ($request->nonStop === 'true') $query['nonStop'] = 'true';
    if ($request->filled('travelClass')) $query['travelClass'] = $request->travelClass;

    // 3) llamada
    $response = Http::withToken($accessToken)
        ->get('https://test.api.amadeus.com/v2/shopping/flight-offers', $query);

    if (!$response->successful()) {
        return response()->json(['error' => 'Error al consultar vuelos', 'detail' => $response->json()], 500);
    }

    return $response->json();
}


}
