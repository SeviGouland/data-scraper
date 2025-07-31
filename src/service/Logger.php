<?php
namespace Service;

class Logger
{
    private string $logFile;

    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
    }

    public function log(string $message): void
    {
        date_default_timezone_set('Europe/Athens');
        $time = date('Y-m-d H:i:s');
        $result = @file_put_contents($this->logFile, "[$time] $message\n", FILE_APPEND);
        if ($result === false) {
            error_log("Failed to write to log file: " . $this->logFile);
        }
    }

}
