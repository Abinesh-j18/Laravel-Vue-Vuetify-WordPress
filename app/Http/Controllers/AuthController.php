<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WpToken;

class AuthController extends Controller
{
    /**
     * Redirect user to WordPress OAuth page
     */
    public function redirectToWordPress()
    {
        $clientId = '124969';
        $redirectUri = 'http://127.0.0.1:8000/auth/callback';

        $url = "https://public-api.wordpress.com/oauth2/authorize?client_id={$clientId}&redirect_uri={$redirectUri}&response_type=code&scope=global";

        return redirect()->away($url);
    }

    /**
     * Handle WordPress OAuth callback
     */
    public function handleWordPressCallback(Request $request)
    {
        // Log the full URL for debugging
        \Log::info('WordPress Callback URL: ' . $request->fullUrl());

        $code = $request->get('code');

        if (!$code) {
            return response()->json(['error' => 'No code received!'], 400);
        }

        // Exchange code for access token
        $response = Http::asForm()->post('https://public-api.wordpress.com/oauth2/token', [
            'client_id'     => '124969',
            'client_secret' => 'M8bOBIo0sNl1fLVvQ76uUpX51UZHb8UJVnpcUW41yw9VTHCWxloDDFs5pW8gocE5',
            'redirect_uri'  => 'http://127.0.0.1:8000/auth/callback',
            'grant_type'    => 'authorization_code',
            'code'          => $code,
        ]);

        if (!$response->ok()) {
            return response()->json(['error' => $response->json()], 400);
        }

        $data = $response->json();

        // Save OAuth token in the database
        WpToken::updateOrCreate(
            ['id' => 1],
            [
                'access_token'  => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_at'    => now()->addSeconds($data['expires_in'] ?? 3600),
            ]
        );

        // Redirect to /home (or your dashboard)
        return redirect('/home')->with('success', 'Connected to WordPress successfully!');
    }
}
