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
            'Bearer ' . $this->getToken(),
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
        $response = $this->client->token()->save([
            'refresh_token' => Cache::get(self::REFRESH_TOKEN_KEY),
        ]);

        return $this->getTokenFromResponse($response);
    }

    private function authenticate(): string
    {
        $this->checkIfCredentialsAreSet();

        $response = $this->client->token()->save([
            'api_key' => env('WP_REST_API_KEY'),
            'api_secret' => env('WP_REST_API_SECRET'),
        ]);

        return $this->getTokenFromResponse($response);
    }

    private function checkIfCredentialsAreSet()
    {
        if (!env("WP_REST_API_KEY") || !env('WP_REST_API_SECRET')) {
            throw new InvalidArgumentException('You need to set both WP_REST_API_KEY and WP_REST_API_SECRET in env');
        }
    }

    private function getTokenFromResponse(array $response) : string
    {
        Cache::put(self::ACCESS_TOKEN_KEY, $response['access_token'], $response['exp']);
        Cache::put(self::REFRESH_TOKEN_KEY, $response['refresh_token']);

        return $response['access_token'];
    }
}
