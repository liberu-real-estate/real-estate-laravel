<?php

namespace App\Modules\Traits;

use Illuminate\Support\Facades\Config;

trait Configurable
{
    public function config(string $key, mixed $default = null): mixed
    {
        $moduleName = strtolower($this->getName());

        return Config::get("{$moduleName}.{$key}", $default);
    }

    public function setConfig(string $key, mixed $value): void
    {
        $moduleName = strtolower($this->getName());
        Config::set("{$moduleName}.{$key}", $value);
    }

    public function hasConfig(string $key): bool
    {
        $moduleName = strtolower($this->getName());

        return Config::has("{$moduleName}.{$key}");
    }

    public function getAllConfig(): array
    {
        $moduleName = strtolower($this->getName());

        return Config::get($moduleName, []);
    }

    public function mergeConfig(array $config): void
    {
        $moduleName = strtolower($this->getName());
        $existing = Config::get($moduleName, []);
        Config::set($moduleName, array_merge($existing, $config));
    }
}
