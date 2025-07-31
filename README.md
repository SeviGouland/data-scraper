# PHP Web Scraper with Retry and Logging

This project is a PHP-based web scraper that:

- Reads product URLs from a text file
- Fetches HTML content with retry support
- Extracts product title, price, and availability
- Saves data to a local SQLite database
- Logs all actions and errors


## Project Structure

project-root/
├── libraries                   
    └── simple_html_dom.php     # A PHP library designed for easy parsing and manipulation of HTML documents
├── run.php                     # Main script to execute
├── url-products.txt            # List of URLs to scrape (one per line)
├── logs.txt                    # Log file
├── products.sqlite             # SQLite database file
└── src/
    └── service/
        └── Database.php        # Handles DB connection and saving data
        ├── Fetcher.php         # Handles HTTP requests via cURL
        ├── Logger.php          # Simple file for logs
        ├── Parser.php          # Parse data using the simple_html_dom library
        ├── ScraperServise.php  # Main scraper class with retry logic



## How to Run the Script from the Terminal

1. Open a terminal window.

2. Navigate to the project root directory.

3. Run the script with: php run.php


### Viewing Retry Logs

To see the retry logs for failed fetch attempts, you can comment out the `CURLOPT_SSL_VERIFYPEER` option in the `Fetcher.php` class. After commenting it out, run the script again (`php run.php`) and the retry attempts and failures will be logged in `logs.txt`.

