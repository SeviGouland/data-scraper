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
        date_default_timezone_set('Europe/Athens');
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
            $stmt = $this->pdo->prepare("INSERT OR REPLACE INTO products (url, title, price, availability) VALUES (:url, :title, :price, :availability)");
            $stmt->execute([
                ':url' => $url,
                ':title' => $data['title'],
                ':price' => $data['price'],
                ':availability' => $data['availability'],
            ]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
