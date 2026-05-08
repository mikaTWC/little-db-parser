<?php

class ConfigLoader
{
    private string $configPath;
    private array $keywords = [];

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        if (!file_exists($this->configPath)) {
            throw new Exception("Config file not found: {$this->configPath}");
        }

        $jsonContent = file_get_contents($this->configPath);
        if ($jsonContent === false) {
            throw new Exception("Failed to read config file: {$this->configPath}");
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON in config file: " . json_last_error_msg());
        }

        foreach ($data as $item) {
            if (!isset($item['keyword']) || !isset($item['link'])) {
                throw new Exception("Invalid config format. Each item must have 'keyword' and 'link' fields.");
            }
            $this->keywords[] = [
                'keyword' => $item['keyword'],
                'link' => $item['link']
            ];
        }
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }
}
