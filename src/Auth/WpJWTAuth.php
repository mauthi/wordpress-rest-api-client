<?php

namespace Vnn\WpApiClient\Auth;

use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Vnn\WpApiClient\Auth\AbstractAuth;
use Vnn\WpApiClient\WpClient;

/**
 * Class WpJWTAuth.
 */
class WpJWTAuth extends AbstractAuth implements AuthInterface
{
    const ACCESS_TOKEN_KEY = 'wp_access_token';
    const REFRESH_TOKEN_KEY = 'wp_refresh_token';

    /**
     * {@inheritdoc}
     */
    public function addCredentials(RequestInterface $request)
    {
        return $request->withHeader(
            'Authorization',
            'Bearer '.$this->getToken(),
        );
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
            'api_key' => config('wordpress.key'),
            'api_secret' => config('wordpress.secret'),
        ]);

        return $this->getTokenFromResponse($response);
    }

    private function checkIfCredentialsAreSet()
    {
        if (!config('wordpress.key') || !config('wordpress.secret')) {
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
