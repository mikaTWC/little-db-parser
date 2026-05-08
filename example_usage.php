<?php

/**
 * Пример использования парсера товаров
 * 
 * Этот скрипт демонстрирует как использовать ProductParser
 * для обработки всех товаров в базе данных
 */

require_once __DIR__ . '/main.php';

// Конфигурация базы данных (замените на свои данные)
$dsn = 'mysql:host=localhost;dbname=your_database;charset=utf8mb4';
$dbUsername = 'your_username';
$dbPassword = 'your_password';

try {
    // Создаем подключение к базе данных
    $db = new ProductDB($dsn, $dbUsername, $dbPassword);
    
    // Создаем парсер с путем к конфигу
    $parser = new ProductParser(__DIR__ . '/config/keywords.json', $db);
    
    // Обрабатываем все товары
    echo "Начинаем обработку товаров...\n";
    $result = $parser->processAllProducts();
    
    echo "Обработка завершена!\n";
    echo "Всего обработано: {$result['processed']}\n";
    echo "Обновлено: {$result['updated']}\n";
    echo "Ошибок: {$result['errors']}\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
