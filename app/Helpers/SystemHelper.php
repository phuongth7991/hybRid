<?php

namespace App\Helpers;

use App\Models\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SystemHelper
{
    public function asset(string $path = null, array $size = [], $crop = false): string
    {
        if (empty($path)) {
            return '';
        }
        $cdn = env('CDN_URL');
        if (!empty($cdn)) {
            return $cdn . $path;
        }

        return asset('storage/' . $path);
    }

    public function getCdnUrl(): string
    {
        return asset('storage') . '/';
    }

    public function getFieldLocale($fieldBaseName): string
    {
        $locale = App::getLocale();

        return "{$fieldBaseName}_{$locale}";
    }

    public function getConfig($key, $default = null)
    {
        $configs = Cache::rememberForever('system_config', static function () {
            return Config::query()->get()->keyBy('key');
        });

        $config = $configs->get($key);
        if (!$config) {
            return $default;
        }

        return $config->value ?? $default;

    }
}
