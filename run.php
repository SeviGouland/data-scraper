<?php

require_once __DIR__ . '/src/service/ScraperService.php';
require_once __DIR__ . '/src/service/Fetcher.php';
require_once __DIR__ . '/src/service/Parser.php';
require_once __DIR__ . '/src/service/Logger.php';
require_once __DIR__ . '/src/service/Database.php';

use Service\ScraperService;

$urls = file(__DIR__ . '/url-products.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// define paths for database and log file
$dbFile = __DIR__ . '/products.sqlite';
$logFile = __DIR__ . '/logs/log.txt';

$scraper = new ScraperService($dbFile, $logFile);

// start scraping the urls
$scraper->run($urls);
