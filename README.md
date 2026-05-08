# Little DB Parser

PHP парсер для замены ключевых слов в описаниях товаров на HTML ссылки.

## Структура проекта

```
/workspace
├── config/
│   ├── ConfigLoader.php    # Загрузчик конфигурации
│   └── keywords.json       # Файл с ключевыми словами и ссылками
├── src/
│   └── LinkReplacer.php    # Логика замены ключевых слов
├── db/
│   └── ProductDB.php       # Работа с базой данных
├── main.php                # Главный класс ProductParser
└── example_usage.php       # Пример использования
```

## Требования

- PHP 7.4 или выше
- PDO драйвер для вашей базы данных
- MySQL или совместимая БД

## Конфигурация

Формат файла `config/keywords.json`:

```json
[
    {
        "keyword": "слово",
        "link": "https://example.com/link"
    }
]
```

## Использование

1. Настройте подключение к базе данных в `example_usage.php`
2. Добавьте ключевые слова в `config/keywords.json`
3. Запустите скрипт:

```bash
php example_usage.php
```

## Основные возможности

- Заменяет только первое вхождение ключевого слова в описании
- Максимум одна ссылка на одно описание
- Не заменяет слова, уже находящиеся внутри тегов `<a>`
- Поддержка UTF-8 и кириллицы
- Регистронезависимый поиск

## API

### ProductParser

```php
// Обработать все товары
$parser->processAllProducts(): array
// Возвращает: ['processed' => int, 'updated' => int, 'errors' => int]

// Обработать один товар
$parser->processProduct(int $productId): ?array
```
