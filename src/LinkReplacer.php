<?php

class LinkReplacer
{
    /**
     * Заменяет первое вхождение ключевого слова на HTML ссылку
     * 
     * @param string $text Исходный текст описания
     * @param array $keywords Массив ключевых слов с ссылками
     * @return string Текст с замененной ссылкой
     */
    public function replaceFirstKeyword(string $text, array $keywords): string
    {
        // Флаг, указывающий была ли уже добавлена ссылка
        $linkAdded = false;

        foreach ($keywords as $item) {
            if ($linkAdded) {
                break;
            }

            $keyword = $item['keyword'];
            $link = $item['link'];

            // Ищем первое вхождение ключевого слова (регистронезависимо)
            $pattern = '/(' . preg_quote($keyword, '/') . ')/ui';
            
            // Проверяем, есть ли совпадение
            if (preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
                // Проверяем, не находится ли слово уже внутри тега <a>
                $matchOffset = $matches[0][1];
                $matchLength = strlen($matches[0][0]);
                
                // Ищем ближайший открывающий тег <a> до совпадения
                $beforeMatch = substr($text, 0, $matchOffset);
                $lastOpenTag = strrpos($beforeMatch, '<a');
                $lastCloseTag = strrpos($beforeMatch, '</a>');
                
                // Если мы уже внутри ссылки, пропускаем это слово
                if ($lastOpenTag !== false && ($lastCloseTag === false || $lastOpenTag > $lastCloseTag)) {
                    continue;
                }

                // Создаем HTML ссылку
                $replacement = '<a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">' 
                             . htmlspecialchars($matches[0][0], ENT_QUOTES, 'UTF-8') . '</a>';
                
                // Заменяем только первое вхождение
                $text = substr_replace($text, $replacement, $matchOffset, $matchLength);
                $linkAdded = true;
            }
        }

        return $text;
    }
}
