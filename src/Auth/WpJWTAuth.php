<?php

namespace Vnn\WpApiClient\Auth;

use Psr\Http\Message\RequestInterface;
use Vnn\WpApiClient\WpClient;
use InvalidArgumentException;
use Illuminate\Support\Facades\Cache;

/**
 * Class WpJWTAuth
 * @package Vnn\WpApiClient\Auth
 */
class WpJWTAuth implements AuthInterface
{
    const ACCESS_TOKEN_KEY = 'wp_access_token';
    const REFRESH_TOKEN_KEY = 'wp_refresh_token';

    private WpClient $client;
    
    /**
     * {@inheritdoc}
     */
    public function addCredentials(RequestInterface $request)
    {
        return $request->withHeader(
            'Authorization',
            'Bearer ' . $this->getToken()
        );
    }

    public function setClient(WpClient $client) : WpJWTAuth 
    {
        $this->client = $client;

        return $this;
    }


    private function getToken() : string
    {
        // if token is in cache: return 
        if (Cache::has(self::ACCESS_TOKEN_KEY)) {
            return Cache::get(self::ACCESS_TOKEN_KEY);
        }

        // if token is not in cache: try to refresh
        return $this->refresh();
    }


    private function refresh() : string 
    {
        if (Cache::has(self::REFRESH_TOKEN_KEY)) { 
            return $this->refreshRequest(Cache::get(self::REFRESH_TOKEN_KEY));
        }

        return $this->authenticate();
    }

    private function refreshRequest(string $refreshToken) : string 
    {
        return "xx";
    }

    private function authenticate(): string
    {
        $this->checkIfCredentialsAreSet();

        $response = $this->client->token()->save([
            'api_key' => env('WP_REST_API_KEY'),
            'api_secrect' => env('WP_REST_API_SECRET'),
        ]);

        return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvaHAyMDE5Lm5ldHdlcmtlci5hdCIsImlhdCI6MTYwNjY1MjE0NCwibmJmIjoxNjA2NjUyMTQ0LCJleHAiOjE2MDcyNTY5NDQsImRhdGEiOnsidXNlciI6eyJpZCI6MywidHlwZSI6IndwX3VzZXIiLCJ1c2VyX2xvZ2luIjoibWljaGFlbC5tYXV0aG5lciIsInVzZXJfZW1haWwiOiJtaWNoYWVsLm1hdXRobmVyQG5ldHdlcmtlci5hdCIsImFwaV9rZXkiOiIzd1Q0S0REY0FqakoyZWFjNnBsZlk0RlZNIn19fQ.1LWVUrzN1jJWDul6cGDSGQk4vO_ZeoDUOmibuSPoBpI";
    }

    private function checkIfCredentialsAreSet() {
        if (!env("WP_REST_API_KEY") || !env('WP_REST_API_SECRET')) {
            throw new InvalidArgumentException('You need to set both WP_REST_API_KEY and WP_REST_API_SECRET in env');
        }
    }
}
