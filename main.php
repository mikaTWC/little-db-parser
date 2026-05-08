<?php

require_once __DIR__ . '/config/ConfigLoader.php';
require_once __DIR__ . '/src/LinkReplacer.php';
require_once __DIR__ . '/db/ProductDB.php';

class ProductParser
{
    private ConfigLoader $configLoader;
    private LinkReplacer $linkReplacer;
    private ProductDB $db;

    public function __construct(string $configPath, ProductDB $db)
    {
        $this->configLoader = new ConfigLoader($configPath);
        $this->linkReplacer = new LinkReplacer();
        $this->db = $db;
    }

    /**
     * Обрабатывает все товары в базе данных
     * Заменяет первое вхождение ключевого слова на HTML ссылку
     * 
     * @return array Статистика обработки
     */
    public function processAllProducts(): array
    {
        $products = $this->db->getAllProducts();
        $keywords = $this->configLoader->getKeywords();
        
        $processed = 0;
        $updated = 0;
        $errors = 0;

        foreach ($products as $product) {
            $processed++;
            
            try {
                $originalDescription = $product['description'];
                $newDescription = $this->linkReplacer->replaceFirstKeyword($originalDescription, $keywords);
                
                // Обновляем только если описание изменилось
                if ($originalDescription !== $newDescription) {
                    if ($this->db->updateProductDescription((int)$product['id'], $newDescription)) {
                        $updated++;
                    } else {
                        $errors++;
                    }
                }
            } catch (Exception $e) {
                $errors++;
                error_log("Error processing product {$product['id']}: " . $e->getMessage());
            }
        }

        return [
            'processed' => $processed,
            'updated' => $updated,
            'errors' => $errors
        ];
    }

    /**
     * Обрабатывает один товар по ID
     * 
     * @param int $productId ID товара
     * @return array|null Обновленные данные товара или null если не найден
     */
    public function processProduct(int $productId): ?array
    {
        $product = $this->db->getProductById($productId);
        if ($product === null) {
            return null;
        }

        $keywords = $this->configLoader->getKeywords();
        $newDescription = $this->linkReplacer->replaceFirstKeyword($product['description'], $keywords);
        
        if ($product['description'] !== $newDescription) {
            $this->db->updateProductDescription($productId, $newDescription);
        }

        return [
            'id' => $product['id'],
            'name' => $product['name'],
            'description' => $newDescription
        ];
    }
}
