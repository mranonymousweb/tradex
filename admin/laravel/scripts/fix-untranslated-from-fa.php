<?php

declare(strict_types=1);

/**
 * Re-translate locale strings that still match English copy.
 * Reference: Persian (fa). Usage: php scripts/fix-untranslated-from-fa.php
 */

$basePath = dirname(__DIR__);
$targetLocales = ['ar', 'fr', 'de', 'es', 'ru', 'zh', 'tr', 'en'];
$googleLocales = [
    'en' => 'en', 'ar' => 'ar', 'fr' => 'fr', 'de' => 'de',
    'es' => 'es', 'ru' => 'ru', 'zh' => 'zh-CN', 'tr' => 'tr',
];

$masterFiles = array_values(array_filter(scandir("{$basePath}/lang/fa"), fn ($f) => str_ends_with($f, '.php')));
$cacheDir = $basePath . '/storage/app/lang-cache';
if (! is_dir($cacheDir)) mkdir($cacheDir, 0755, true);

function loadCache(string $locale): array
{
    global $cacheDir;
    $file = "{$cacheDir}/fix-{$locale}.json";
    return file_exists($file) ? (json_decode(file_get_contents($file), true) ?: []) : [];
}
function saveCache(string $locale, array $cache): void
{
    global $cacheDir;
    file_put_contents("{$cacheDir}/fix-{$locale}.json", json_encode($cache, JSON_UNESCAPED_UNICODE));
}
function protectPlaceholders(string $text): array
{
    $map = []; $i = 0;
    $protected = preg_replace_callback('/:\w+|%\w|%s|\{[^}]+\}/u', function ($m) use (&$map, &$i) {
        $token = "<xph{$i}xph>"; $map[$token] = $m[0]; $i++; return $token;
    }, $text);
    return [$protected ?? $text, $map];
}
function restorePlaceholders(string $text, array $map): string
{
    foreach ($map as $t => $o) $text = str_replace($t, $o, $text);
    return $text;
}
function shouldSkip(mixed $text): bool
{
    if (! is_string($text)) return true;
    $t = trim($text);
    return $t === '' || preg_match('/^[\d\s\.\,\:\;\-\+\*\/\(\)\[\]{}#@]+$/u', $t) || preg_match('/^https?:\/\//i', $t);
}
function translateFromFa(string $text, string $googleLocale, array &$cache): string
{
    if (shouldSkip($text)) return $text;
    $ck = md5('fa::'.$googleLocale.'::'.$text);
    if (isset($cache[$ck])) return $cache[$ck];
    [$p, $map] = protectPlaceholders($text);
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=fa&tl='.urlencode($googleLocale).'&dt=t&q='.urlencode($p);
    $response = @file_get_contents($url);
    if ($response === false) { $cache[$ck] = $text; return $text; }
    $json = json_decode($response, true);
    $out = '';
    if (is_array($json[0] ?? null)) foreach ($json[0] as $part) $out .= $part[0] ?? '';
    $out = restorePlaceholders($out ?: $text, $map);
    $cache[$ck] = $out;
    usleep(40000);
    return $out;
}
function exportPhpArray(array $array, int $indent = 1): string
{
    $pad = str_repeat('    ', $indent);
    $lines = ["[\n"];
    foreach ($array as $key => $value) {
        $keyExport = is_int($key) ? (string)$key : "'".str_replace("'", "\\'", (string)$key)."'";
        $lines[] = is_array($value)
            ? "{$pad}{$keyExport} => ".exportPhpArray($value, $indent+1).",\n"
            : "{$pad}{$keyExport} => ".var_export($value, true).",\n";
    }
    $lines[] = str_repeat('    ', max(0, $indent-1)).']';
    return implode('', $lines);
}
function writeLangFile(string $path, array $data): void
{
    file_put_contents($path, "<?php\n\nreturn ".exportPhpArray($data).";\n");
}

function fixTree(array $fa, array $en, array $target, string $locale, string $googleLocale, array &$cache, int &$fixed): array
{
    $result = [];
    foreach ($fa as $key => $faValue) {
        if (is_array($faValue)) {
            $result[$key] = fixTree(
                $faValue,
                is_array($en[$key] ?? null) ? $en[$key] : [],
                is_array($target[$key] ?? null) ? $target[$key] : [],
                $locale, $googleLocale, $cache, $fixed
            );
            continue;
        }
        $targetValue = $target[$key] ?? null;
        $enValue = $en[$key] ?? null;
        if ($locale === 'fa') {
            $result[$key] = $faValue;
        } elseif (is_string($targetValue) && is_string($enValue) && $targetValue === $enValue && is_string($faValue)) {
            $result[$key] = translateFromFa($faValue, $googleLocale, $cache);
            $fixed++;
        } elseif ($targetValue === null || $targetValue === '') {
            $result[$key] = is_string($faValue) ? translateFromFa($faValue, $googleLocale, $cache) : $faValue;
            $fixed++;
        } else {
            $result[$key] = $targetValue;
        }
    }
    return $result;
}

foreach ($targetLocales as $locale) {
    echo "=== {$locale} ===\n";
    $cache = loadCache($locale);
    $googleLocale = $googleLocales[$locale];
    $fixed = 0;
    foreach ($masterFiles as $file) {
        $fa = include "{$basePath}/lang/fa/{$file}";
        $en = include "{$basePath}/lang/en/{$file}";
        $targetPath = "{$basePath}/lang/{$locale}/{$file}";
        $target = file_exists($targetPath) ? include $targetPath : [];
        $result = fixTree($fa, $en, $target, $locale, $googleLocale, $cache, $fixed);
        writeLangFile($targetPath, $result);
        saveCache($locale, $cache);
        echo "  {$file}\n";
    }
    echo "  fixed: {$fixed}\n";
}
echo "Done.\n";
