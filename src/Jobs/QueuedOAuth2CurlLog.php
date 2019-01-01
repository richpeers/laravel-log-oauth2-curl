<?php

namespace RichPeers\LaravelLogOAuth2Curl\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use RichPeers\LaravelLogOAuth2Curl\OAuth2\ClientCredentialsGrantToken;

class QueuedOAuth2CurlLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $config, $record;

    /**
     * Create a new job instance.
     *
     * @param array $config
     * @param string $record
     * @return void
     */
    public function __construct(array $config, string $record)
    {
        $this->config = $config;
        $this->record = $record;
    }

    /**
     * Run the queued job.
     *
     * @param ClientCredentialsGrantToken $token
     * @return void
     */
    public function handle(ClientCredentialsGrantToken $token)
    {
        try {
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token->get()
            ];

            $ch = \curl_init();

            \curl_setopt($ch, CURLOPT_URL, $this->config['host'] . '/' . $this->config['endpoint']);
            \curl_setopt($ch, CURLOPT_POST, true);
            \curl_setopt($ch, CURLOPT_POSTFIELDS, $this->record);
            \curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            \curl_exec($ch);

            \curl_close($ch);

        } catch (\Exception $e) {
            $this->logErrorToSingleChannelOnly($e->getMessage());
        }
    }

    /**
     * Log caught exceptions to 'single' channel only.
     * To prevent loop and enable debug on setup.
     *
     * @param $message
     */
    protected function logErrorToSingleChannelOnly($message): void
    {
        app('log')->channel('single')->error(self::class . ' : ' . $message);
    }
}
