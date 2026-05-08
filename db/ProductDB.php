<?php

use PDO;
use PDOException;

class ProductDB
{
    private PDO $pdo;

    public function __construct(string $dsn, string $username = '', string $password = '')
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Получает все товары из базы данных
     * 
     * @return array Массив товаров с полями id, name, description
     */
    public function getAllProducts(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, description FROM products");
        return $stmt->fetchAll();
    }

    /**
     * Обновляет описание товара в базе данных
     * 
     * @param int $id ID товара
     * @param string $description Новое описание
     * @return bool Успешность обновления
     */
    public function updateProductDescription(int $id, string $description): bool
    {
        $stmt = $this->pdo->prepare("UPDATE products SET description = :description WHERE id = :id");
        return $stmt->execute([
            ':description' => $description,
            ':id' => $id
        ]);
    }

    /**
     * Получает товар по ID
     * 
     * @param int $id ID товара
     * @return array|null Данные товара или null если не найден
     */
    public function getProductById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, name, description FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
