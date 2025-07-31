<?php
namespace Service;

use Service\Fetcher;
use Service\Parser;
use Service\Database;
use Service\Logger;
use PDOException;

class ScraperService
{
    private Fetcher $fetcher;
    private Parser $parser;
    private Database $database;
    private Logger $logger;

    public function __construct(string $dbFile, string $logFile)
    {
        $this->fetcher = new Fetcher();
        $this->parser = new Parser();
        $this->database = new Database($dbFile);
        $this->logger = new Logger($logFile);
    }

    public function run(array $urls): void
    {
        foreach ($urls as $url) {
            $this->logger->log("Bringing data from URL: $url");

            // fetch with retry
            $html = $this->fetchWithRetry($url, 2);
            if ($html === null) {
                $this->logger->log("Failed to fetch after retries: $url");
                continue;
            }

            $data = $this->parser->parse($html);

            // check for missing required fields
            $missingFields = [];
            foreach (['title', 'price', 'availability'] as $field) {
                if (empty($data[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                $this->logger->log("Missing fields (" . implode(', ', $missingFields) . ") from URL: $url");
            }

            $this->saveWithRetry($url, $data, 2);
        }
    }

    // try to fetch html from the URL with retry attempts
    private function fetchWithRetry(string $url, int $maxAttempts = 2): ?string
    {
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            // log retry attempt if not the first try
            if ($attempt > 1) {
                $this->logger->log("Retry fetch attempt $attempt for URL: $url");
            }
            $html = $this->fetcher->fetch($url);

            if ($html !== null) {
                return $html;
            }

            sleep(1);
        }
        return null;
    }

    // try to save data to the database with retry attempts
    private function saveWithRetry(string $url, array $data, int $maxAttempts = 2): void
    {
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                // log retry attempt if not the first try
                if ($attempt > 1) {
                    $this->logger->log("Retry save attempt $attempt for URL: $url");
                }
                $this->database->save($url, $data);
                return;
            } catch (PDOException $e) {
                if ($attempt === $maxAttempts) {
                    $this->logger->log("Insert Error for $url after $attempt attempts: " . $e->getMessage());
                } else {
                    // wait 1 second before retrying
                    sleep(1);
                }
            }
        }
    }

}
