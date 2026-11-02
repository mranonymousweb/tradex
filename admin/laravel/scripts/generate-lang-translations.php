<?php

declare(strict_types=1);

/**
 * Reliable lang generator (single-string translate + cache).
 * Usage: php scripts/generate-lang-translations.php [--force]
 */

$basePath = dirname(__DIR__);
$force = in_array('--force', $argv ?? [], true);

$locales = [
    'ar' => 'ar',
    'fr' => 'fr',
    'de' => 'de',
    'es' => 'es',
    'ru' => 'ru',
    'zh' => 'zh-CN',
    'tr' => 'tr',
];

$enFiles = ['auth.php', 'general.php', 'master.php', 'pagination.php', 'passwords.php', 'setting.php', 'table.php', 'validation.php'];
$faExtraFiles = ['actions.php', 'challenge.php'];

$cacheDir = $basePath . '/storage/app/lang-cache';
if ($force && is_dir($cacheDir)) {
    array_map('unlink', glob("{$cacheDir}/*.json") ?: []);
}
if (! is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

function loadCache(string $locale): array
{
    global $cacheDir;
    $file = "{$cacheDir}/{$locale}.json";
    return file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
}

function saveCache(string $locale, array $cache): void
{
    global $cacheDir;
    file_put_contents("{$cacheDir}/{$locale}.json", json_encode($cache, JSON_UNESCAPED_UNICODE));
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

function translateOne(mixed $text, string $googleLocale, string $sourceLocale = 'en'): string
{
    if (! is_string($text)) {
        $text = (string) $text;
    }

    if (shouldSkipTranslation($text)) {
        return $text;
    }

    [$protected, $map] = protectPlaceholders($text);
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' . urlencode($sourceLocale) . '&tl=' . urlencode($googleLocale) . '&dt=t&q=' . urlencode($protected);
    $response = @file_get_contents($url);
    if ($response === false) {
        return $text;
    }

    $json = json_decode($response, true);
    $translated = '';
    if (is_array($json[0] ?? null)) {
        foreach ($json[0] as $part) {
            $translated .= $part[0] ?? '';
        }
    }

    usleep(60000);

    return restorePlaceholders($translated ?: $text, $map);
}

function collectStrings(array $data, array &$bucket): void
{
    foreach ($data as $value) {
        if (is_array($value)) {
            collectStrings($value, $bucket);
            continue;
        }
        if (is_string($value)) {
            $bucket[(string) $value] = true;
        }
    }
}

function applyTranslations(array $data, array $map): array
{
    $result = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $result[$key] = applyTranslations($value, $map);
            continue;
        }
        $result[$key] = is_string($value) && isset($map[$value]) ? $map[$value] : $value;
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

function buildTranslationMap(array $uniqueStrings, string $googleLocale, array &$cache, string $sourceLocale, string $cacheLocale): array
{
    $map = [];
    $pending = [];
    foreach (array_keys($uniqueStrings) as $rawText) {
        $text = (string) $rawText;
        $cacheKey = md5($sourceLocale . '::' . $text);
        if (isset($cache[$cacheKey])) {
            $map[$text] = $cache[$cacheKey];
            continue;
        }
        $pending[] = $text;
    }

    $total = count($pending);
    foreach ($pending as $i => $text) {
        $translated = translateOne($text, $googleLocale, $sourceLocale);
        $cacheKey = md5($sourceLocale . '::' . $text);
        $cache[$cacheKey] = $translated;
        $map[$text] = $translated;
        if (($i + 1) % 25 === 0 || ($i + 1) === $total) {
            echo '    ' . ($i + 1) . "/{$total}\n";
            saveCache($cacheLocale, $cache);
        }
    }

    return $map;
}

foreach ($locales as $locale => $googleLocale) {
    echo "=== {$locale} ===\n";
    $cache = loadCache($locale);
    $targetDir = "{$basePath}/lang/{$locale}";
    if (! is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    foreach ($enFiles as $file) {
        echo "  {$file}\n";
        $source = include "{$basePath}/lang/en/{$file}";
        $unique = [];
        collectStrings($source, $unique);
        $map = buildTranslationMap($unique, $googleLocale, $cache, 'en', $locale);
        saveCache($locale, $cache);
        writeLangFile("{$targetDir}/{$file}", applyTranslations($source, $map));
    }

    foreach ($faExtraFiles as $file) {
        $faPath = "{$basePath}/lang/fa/{$file}";
        if (! file_exists($faPath)) {
            continue;
        }
        echo "  fa/{$file}\n";
        $source = include $faPath;
        $unique = [];
        collectStrings($source, $unique);
        $map = buildTranslationMap($unique, $googleLocale, $cache, 'fa', $locale);
        saveCache($locale, $cache);
        writeLangFile("{$targetDir}/{$file}", applyTranslations($source, $map));
    }
}

echo "Done.\n";
