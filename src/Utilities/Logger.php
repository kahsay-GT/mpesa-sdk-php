<?php

namespace Mpesa\Sdk\Utilities;

class Logger
{
    private string $logFile;

    public function __construct(string $logFile = 'mpesa_sdk.log')
    {
        $this->logFile = $logFile;
    }

    public function info(string $message): void
    {
        $this->log('INFO', $message);
    }

    public function error(string $message): void
    {
        $this->log('ERROR', $message);
    }

    private function log(string $level, string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}