<?php declare(strict_types=1);

namespace RichPeers\LaravelLogOAuth2Curl\Monolog;

use RichPeers\LaravelLogOAuth2Curl\Jobs\QueuedOAuth2CurlLog;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Logger;

class LaravelLogOAuth2CurlHandler extends AbstractProcessingHandler
{
    protected $config;

    /**
     * @param array $config
     * @param int $level
     * @param bool $bubble
     * @throws MissingExtensionException
     */
    public function __construct(array $config, int $level = Logger::DEBUG, bool $bubble = false)
    {
        if (!extension_loaded('curl')) {
            throw new MissingExtensionException('The curl extension is needed to use the LaravelLogStoreHandler');
        }

        $this->config = $config;

        parent::__construct($level, $bubble);
    }

    /**
     * Write the log.
     *
     * @param array $record
     */
    protected function write(array $record): void
    {
        dispatch(new QueuedOAuth2CurlLog($this->config, $record['formatted']));
    }

    /**
     * Get formatter.
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        $formatter = $this->config['classes']['formatter'];

        return new $formatter();
    }
}
