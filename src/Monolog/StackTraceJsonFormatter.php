<?php declare(strict_types=1);

namespace RichPeers\LaravelLogOAuth2Curl\Monolog;

use Monolog\Formatter\JsonFormatter;

class StackTraceJsonFormatter extends JsonFormatter
{
    /**
     * Format the log for LaravelLogServer.
     *
     * @param array $record
     * @return string
     */
    public function format(array $record): string
    {
        $this->includeStacktraces();

        if (isset($record["datetime"]) && ($record["datetime"] instanceof \DateTimeInterface)) {

            $record["timestamp"] = $record["datetime"]->format("Y-m-d\TH:i:s.uO");

            unset($record["datetime"]);
        }

        return parent::format($record);
    }
}
