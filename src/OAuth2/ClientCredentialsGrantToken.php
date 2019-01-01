<?php

namespace RichPeers\LaravelLogOAuth2Curl\OAuth2;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Encryption\DecryptException;
use GuzzleHttp\Client;

class ClientCredentialsGrantToken
{
    protected $cache, $config, $key = 'log-token';

    /**
     * @param CacheManager $cache
     * @param array $config
     */
    public function __construct(CacheManager $cache, array $config)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    /**
     * Get Client Credentials Grant Token
     *
     * @return string
     */
    public function get()
    {
        if ($token = $this->cachedToken()) {
            return $token;
        }

        return $this->newToken();
    }

    /**
     * Check for token and return decrypted
     *
     * @return string|bool
     */
    protected function cachedToken()
    {
        if ($this->cache->has($this->key)) {
            try {
                return decrypt($this->cache->get($this->key));

            } catch (DecryptException $e) {
                $this->cache->forget($this->key);
                return false;
            }
        }

        return false;
    }

    /**
     * Encrypt, cache and return new token
     *
     * @return string
     */
    protected function newToken(): string
    {
        $new = $this->fetchNewToken();

        $this->cache->put(
            $this->key,
            encrypt($new['access_token']),
            $new['expires_in'] / 60 - 1
        );

        return $new['access_token'];
    }

    /**
     * Fetch new Client Credentials Grant Token
     *
     * @return array
     */
    protected function fetchNewToken(): array
    {
        $guzzle = new Client;
        $config = $this->config;

        $response = $guzzle->post($config['host'] . '/oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $config['client_id'],
                'client_secret' => $config['client_secret']
            ]
        ]);

        return \json_decode((string)$response->getBody(), true);
    }
}
