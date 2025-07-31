<?php
namespace Service;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct(string $dbFile)
    {
        $this->pdo = new PDO('sqlite:' . $dbFile);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initTable();
    }

    private function initTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            url TEXT UNIQUE,
            title TEXT,
            price TEXT,
            availability TEXT,
            scraped_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

        )");
    }

    public function save(string $url, array $data): void
    {
        try {
            // set timezone to Athens to ensure 'scraped_at' stores the current local time
            date_default_timezone_set('Europe/Athens');

            $stmt = $this->pdo->prepare("
            INSERT OR REPLACE INTO products 
            (url, title, price, availability, scraped_at)
            VALUES 
            (:url, :title, :price, :availability, :scraped_at)
        ");

            $stmt->execute([
                ':url' => $url,
                ':title' => $data['title'],
                ':price' => $data['price'],
                ':availability' => $data['availability'],
                ':scraped_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

}
