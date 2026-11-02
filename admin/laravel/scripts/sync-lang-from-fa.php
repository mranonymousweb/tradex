<?php

declare(strict_types=1);

/**
 * Sync all lang files from Persian (fa) master structure.
 * Usage: php scripts/sync-lang-from-fa.php
 */

$basePath = dirname(__DIR__);
$masterLocale = 'fa';
$targetLocales = ['en', 'ar', 'fr', 'de', 'es', 'ru', 'zh', 'tr'];
$googleLocales = [
    'en' => 'en',
    'ar' => 'ar',
    'fr' => 'fr',
    'de' => 'de',
    'es' => 'es',
    'ru' => 'ru',
    'zh' => 'zh-CN',
    'tr' => 'tr',
];

$cacheDir = $basePath . '/storage/app/lang-cache';
if (! is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

$masterFiles = array_values(array_filter(scandir("{$basePath}/lang/{$masterLocale}"), fn ($f) => str_ends_with($f, '.php')));

function loadCache(string $locale): array
{
    global $cacheDir;
    $file = "{$cacheDir}/sync-{$locale}.json";
    return file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
}

function saveCache(string $locale, array $cache): void
{
    global $cacheDir;
    file_put_contents("{$cacheDir}/sync-{$locale}.json", json_encode($cache, JSON_UNESCAPED_UNICODE));
}

function protectPlaceholders(string $text): array
{
    $map = [];
    $i = 0;
    $protected = preg_replace_callback('/:\w+|%\w|%s|\{[^}]+\}/u', function (array $m) use (&$map, &$i) {
        $token = "<xph{$i}xph>";
        $map[$token] = $m[0];
        $i++;
        return $token;
    }, $text);

    return [$protected ?? $text, $map];
}

function restorePlaceholders(string $text, array $map): string
{
    foreach ($map as $token => $original) {
        $text = str_replace($token, $original, $text);
    }
    return $text;
}

function shouldSkipTranslation(mixed $text): bool
{
    if (! is_string($text)) {
        return true;
    }
    $trimmed = trim($text);
    return $trimmed === ''
        || preg_match('/^[\d\s\.\,\:\;\-\+\*\/\(\)\[\]{}#@]+$/u', $trimmed)
        || preg_match('/^https?:\/\//i', $trimmed);
}

function translateFromFa(string $text, string $googleLocale, array &$cache): string
{
    if (shouldSkipTranslation($text)) {
        return $text;
    }

    $cacheKey = md5('fa::' . $googleLocale . '::' . $text);
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }

    [$protected, $map] = protectPlaceholders($text);
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=fa&tl=' . urlencode($googleLocale) . '&dt=t&q=' . urlencode($protected);
    $response = @file_get_contents($url);
    if ($response === false) {
        $cache[$cacheKey] = $text;
        return $text;
    }

    $json = json_decode($response, true);
    $translated = '';
    if (is_array($json[0] ?? null)) {
        foreach ($json[0] as $part) {
            $translated .= $part[0] ?? '';
        }
    }

    $translated = restorePlaceholders($translated ?: $text, $map);
    $cache[$cacheKey] = $translated;
    usleep(50000);

    return $translated;
}

function syncFromFa(array $fa, ?array $existing, string $locale, string $googleLocale, array &$cache): array
{
    $existing ??= [];
    $result = [];

    foreach ($fa as $key => $faValue) {
        if (is_array($faValue)) {
            $childExisting = (isset($existing[$key]) && is_array($existing[$key])) ? $existing[$key] : [];
            $result[$key] = syncFromFa($faValue, $childExisting, $locale, $googleLocale, $cache);
            continue;
        }

        if ($locale === 'fa') {
            $result[$key] = $faValue;
            continue;
        }

        if (array_key_exists($key, $existing) && is_string($existing[$key]) && trim($existing[$key]) !== '') {
            $result[$key] = $existing[$key];
            continue;
        }

        $result[$key] = is_string($faValue)
            ? translateFromFa($faValue, $googleLocale, $cache)
            : $faValue;
    }

    return $result;
}

function exportPhpArray(array $array, int $indent = 1): string
{
    $pad = str_repeat('    ', $indent);
    $lines = ["[\n"];
    foreach ($array as $key => $value) {
        $keyExport = is_int($key) ? (string) $key : "'" . str_replace("'", "\\'", (string) $key) . "'";
        if (is_array($value)) {
            $lines[] = "{$pad}{$keyExport} => " . exportPhpArray($value, $indent + 1) . ",\n";
            continue;
        }
        $lines[] = "{$pad}{$keyExport} => " . var_export($value, true) . ",\n";
    }
    $lines[] = str_repeat('    ', max(0, $indent - 1)) . ']';
    return implode('', $lines);
}

function writeLangFile(string $path, array $data): void
{
    file_put_contents($path, "<?php\n\nreturn " . exportPhpArray($data) . ";\n");
}

echo 'Master files: ' . implode(', ', $masterFiles) . PHP_EOL;

foreach ($targetLocales as $locale) {
    echo "=== {$locale} ===\n";
    $cache = loadCache($locale);
    $googleLocale = $googleLocales[$locale];
    $targetDir = "{$basePath}/lang/{$locale}";
    if (! is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    foreach ($masterFiles as $file) {
        $fa = include "{$basePath}/lang/{$masterLocale}/{$file}";
        $existingPath = "{$targetDir}/{$file}";
        $existing = file_exists($existingPath) ? include $existingPath : [];
        echo "  {$file}\n";
        $synced = syncFromFa($fa, $existing, $locale, $googleLocale, $cache);
        writeLangFile($existingPath, $synced);
        saveCache($locale, $cache);
    }
}

echo "Done.\n";
