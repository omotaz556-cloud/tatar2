# Run this from the ROOT of your local tatar repo checkout (PowerShell).
# It rewrites tz_html_dir_attrs() / tz_rtl_stylesheet_tag() in-place to a
# hard-disabled, upstream-faithful no-op in EVERY copy it finds -
# GameEngine/config.php, GameEngine/Admin/Mods/constant_format.tpl, and
# any installed_*/data/constant_format.tpl regardless of hash.

$newBlock = @'
if (!function_exists('tz_html_dir_attrs')) {
    function tz_html_dir_attrs($langCode = null) {
        $langCode = $langCode ?? (defined('LANG') ? LANG : 'en');
        return 'lang="' . htmlspecialchars($langCode, ENT_QUOTES) . '" dir="ltr"';
    }
}
if (!function_exists('tz_rtl_stylesheet_tag')) {
    function tz_rtl_stylesheet_tag($langCode = null, $relPath = 'css/') {
        return '';
    }
}
'@

$targets = @(Get-ChildItem -Recurse -File -Include config.php,constant_format.tpl -ErrorAction SilentlyContinue)

foreach ($f in $targets) {
    $content = Get-Content -Raw -LiteralPath $f.FullName
    if ($content -notmatch 'tz_html_dir_attrs') { continue }

    $pattern = '(?s)if \(!function_exists\(''tz_html_dir_attrs''\)\).*?\n\}\s*\nif \(!function_exists\(''tz_rtl_stylesheet_tag''\)\).*?\n\}\s*\n'
    if ($content -match $pattern) {
        $updated = [regex]::Replace($content, $pattern, ($newBlock + "`n"))
        Set-Content -LiteralPath $f.FullName -Value $updated -NoNewline
        Write-Host "PATCHED: $($f.FullName)"
    } else {
        Write-Host "SKIPPED (pattern not found, check manually): $($f.FullName)"
    }
}

Write-Host "`n--- Verification: should return NOTHING below ---"
Get-ChildItem -Recurse -File -Include *.php,*.tpl -ErrorAction SilentlyContinue |
    Select-String -Pattern 'rtl\.css\?v2' |
    ForEach-Object { Write-Host $_ }

Remove-Item -Path (Join-Path (Get-Location) 'css\rtl.css') -Force -ErrorAction SilentlyContinue
Write-Host "css/rtl.css removed if it existed."
