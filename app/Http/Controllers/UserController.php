<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        $token = session('auth_token');

        if (!$token) {
            Log::debug('Redirecting to login: Authentication token not found in session.');
            return redirect('/login');
        }

        Log::debug('Attempting to validate token', ['token' => $token]); // TODO: Remove this in production

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post('https://prim-api.o513.dev/api/v1/auth/token/validate', [
                'token' => $token,
                'with' => ['user']
            ]);

            Log::debug('Token validation response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::warning('Token validation failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return redirect('/login')->withErrors('Authentication failed');
            }

            $data = $response->json();

            if (isset($data['user'])) {
                Log::debug('User data found in response', ['user_id' => $data['user']['id']]);
                $data['user'] = [
                    'user_type' => $data['user']['user_type'],
                    'first_name' => $data['user']['first_name'],
                    'last_name' => $data['user']['last_name'],
                    'id' => $data['user']['id'],
                    'uuid' => $data['user']['uuid']
                ];
            } else {
                Log::debug('No user data found in token validation response');
            }

            Log::debug('Token validated successfully, rendering dashboard');
            return view('dashboard', [
                'title' => 'Dashboard',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Exception during token validation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->withErrors('Authentication error');
        }
    }

    public function analytics()
    {
        $token = session('auth_token');

        if (!$token) {
            Log::debug('Redirecting to login: Authentication token not found in session.');
            return redirect('/login');
        }

        Log::debug('Attempting to validate token', ['token' => $token]); // TODO: Remove this in production

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post('https://prim-api.o513.dev/api/v1/auth/token/validate', [
                'token' => $token,
                'with' => ['user']
            ]);

            Log::debug('Token validation response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                Log::warning('Token validation failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return redirect('/login')->withErrors('Authentication failed');
            }

            $data = $response->json();

            if (isset($data['user'])) {
                Log::debug('User data found in response', ['user_id' => $data['user']['id']]);
                $data['user'] = [
                    'user_type' => $data['user']['user_type'],
                    'first_name' => $data['user']['first_name'],
                    'last_name' => $data['user']['last_name'],
                    'id' => $data['user']['id'],
                    'uuid' => $data['user']['uuid']
                ];
            } else {
                Log::debug('No user data found in token validation response');
            }

            if ($data['user']['user_type'] !== 'admin') {
                Log::warning('Unauthorized access attempt', [
                    'user_id' => $data['user']['id'],
                    'user_type' => $data['user']['user_type']
                ]);
                return redirect('/login')->withErrors('Unauthorized access');
            }

            Log::debug('Token validated successfully and authorized properly, rendering analytics');
            return view('analytics', [
                'title' => 'PRIM Analytics',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Exception during token validation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->withErrors('Authentication error');
        }
    }
}
