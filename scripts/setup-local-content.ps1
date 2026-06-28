<#
.SYNOPSIS
    Pixel Signs & Printing - Local content setup for Windows (LocalWP).

.DESCRIPTION
    Windows/PowerShell counterpart to scripts/setup-local-pages.sh. WordPress page
    and product content lives in the site database, not in git, so a fresh LocalWP
    install renders the WordPress fallback ("Create a page and assign it as the front
    page...") even when the theme/plugin code is up to date.

    This script (idempotent, safe to re-run) talks to the LocalWP site via the
    bundled WP-CLI + PHP and:
      - sets clean permalinks
      - creates/updates every page the theme links to, with the right template
      - creates a Home page and assigns it as the static front page
      - creates the Products catalog page (slug /products/)
      - imports the sample WooCommerce products from data/sample-products.csv

    Paths auto-detect for a standard LocalWP install. Override with parameters if needed.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File scripts\setup-local-content.ps1

.EXAMPLE
    powershell -File scripts\setup-local-content.ps1 -SitePath "C:\Users\me\Local Sites\pixel\app\public"
#>
[CmdletBinding()]
param(
    [string]$SitePath,
    [string]$PhpExe,
    [string]$WpCli,
    [string]$PhpIni,
    [string]$ProductsCsv,
    [int]$AdminUserId = 1
)

$ErrorActionPreference = 'Stop'

function Resolve-First {
    param([string[]]$Candidates)
    foreach ($c in $Candidates) {
        if ($c -and (Test-Path $c)) { return (Get-Item $c).FullName }
    }
    return $null
}

# --- Auto-discover LocalWP toolchain ---------------------------------------
if (-not $SitePath) { $SitePath = "$env:USERPROFILE\Local Sites\pixel\app\public" }

if (-not $PhpExe) {
    $PhpExe = (Get-ChildItem "$env:APPDATA\Local\lightning-services\php-*\bin\win64\php.exe" -ErrorAction SilentlyContinue |
        Sort-Object FullName -Descending | Select-Object -First 1).FullName
}

if (-not $WpCli) {
    foreach ($root in @("C:\Program Files (x86)\Local", "C:\Program Files\Local", "$env:LOCALAPPDATA\Programs\Local")) {
        if (Test-Path $root) {
            $hit = Get-ChildItem $root -Recurse -Filter "wp-cli.phar" -ErrorAction SilentlyContinue | Select-Object -First 1
            if ($hit) { $WpCli = $hit.FullName; break }
        }
    }
}

if (-not $PhpIni) {
    $PhpIni = (Get-ChildItem "$env:APPDATA\Local\run\*\conf\php\php.ini" -ErrorAction SilentlyContinue | Select-Object -First 1).FullName
}

if (-not $ProductsCsv) { $ProductsCsv = Join-Path $PSScriptRoot "..\data\sample-products.csv" }

foreach ($pair in @(@('SitePath', $SitePath), @('PhpExe', $PhpExe), @('WpCli', $WpCli), @('PhpIni', $PhpIni))) {
    if (-not $pair[1] -or -not (Test-Path $pair[1])) {
        throw "Could not resolve $($pair[0]). Pass it explicitly, e.g. -$($pair[0]) <path>."
    }
}

# LocalWP's PHP ships php_imagick.dll enabled but the DLL is absent on the CLI path,
# which spams a harmless startup warning. Use a cleaned copy of the site php.ini.
$CleanIni = Join-Path $env:TEMP "pixel-wpcli-php.ini"
(Get-Content $PhpIni) -replace '^\s*extension\s*=\s*php_imagick\.dll', ';imagick disabled for cli' |
    Set-Content $CleanIni -Encoding ascii

function wpc { & $PhpExe -c $CleanIni $WpCli --path=$SitePath @args }

Write-Host "Pixel local content setup" -ForegroundColor Cyan
Write-Host "  Site : $SitePath"
Write-Host "  PHP  : $PhpExe"
Write-Host "  WP   : $WpCli"

$siteUrl = ("$(wpc option get siteurl)").Trim()
if (-not $siteUrl) { throw "WP-CLI could not reach the site DB. Is the LocalWP site running?" }
Write-Host "  URL  : $siteUrl" -ForegroundColor Green

# --- Permalinks -------------------------------------------------------------
Write-Host "`nConfiguring permalinks..."
wpc rewrite structure '/%postname%/' | Out-Null

# --- Pages ------------------------------------------------------------------
function Ensure-Page {
    param([string]$Title, [string]$Slug, [string]$Template)
    $id = ("$(wpc post list --post_type=page --name=$Slug --post_status=any --format=ids)").Trim()
    if ($id) {
        wpc post update $id --post_status=publish | Out-Null
        $verb = 'updated'
    }
    else {
        $id = ("$(wpc post create --post_type=page --post_title=$Title --post_name=$Slug --post_status=publish --porcelain)").Trim()
        $verb = 'created'
    }
    if ($Template) { wpc post meta update $id _wp_page_template $Template | Out-Null }
    Write-Host (" - {0,-7} {1,-20} /{2}/  (id {3})" -f $verb, "'$Title'", $Slug, $id)
    return $id
}

Write-Host "`nEnsuring pages..."
# Home is created first so we can assign it as the static front page.
$homeId = Ensure-Page -Title "Home" -Slug "home" -Template ""

$pages = @(
    @{ t = "Products"; s = "products"; tpl = "page-templates/products.php" },
    @{ t = "Request Quote"; s = "request-quote"; tpl = "page-templates/request-quote.php" },
    @{ t = "Upload Artwork"; s = "upload-artwork"; tpl = "page-templates/upload-artwork.php" },
    @{ t = "Client Dashboard"; s = "client-dashboard"; tpl = "page-templates/client-dashboard.php" },
    @{ t = "Order Tracking"; s = "order-tracking"; tpl = "page-templates/order-tracking.php" },
    @{ t = "About Us"; s = "about"; tpl = "page-templates/about.php" },
    @{ t = "Contact Us"; s = "contact"; tpl = "page-templates/contact.php" },
    @{ t = "FAQ"; s = "faq"; tpl = "page-templates/faq.php" },
    @{ t = "Privacy Policy"; s = "privacy-policy"; tpl = "page-templates/privacy-policy.php" },
    @{ t = "Terms & Conditions"; s = "terms-conditions"; tpl = "page-templates/terms.php" },
    @{ t = "Refund Policy"; s = "refund-policy"; tpl = "page-templates/refund-policy.php" },
    @{ t = "Shipping & Delivery"; s = "shipping-delivery-policy"; tpl = "page-templates/shipping-delivery.php" },
    @{ t = "Pickup Locations"; s = "pickup-locations"; tpl = "page-templates/pickup-locations.php" }
)
foreach ($p in $pages) { Ensure-Page -Title $p.t -Slug $p.s -Template $p.tpl | Out-Null }

# --- Static front page ------------------------------------------------------
Write-Host "`nAssigning static front page..."
wpc option update show_on_front page | Out-Null
wpc option update page_on_front $homeId | Out-Null
Write-Host " - show_on_front=page, page_on_front=$homeId (Home)"

# --- WooCommerce product categories ----------------------------------------
# Slugs match the header mega-menu category filters (?category=...).
$catMap = [ordered]@{
    'Marketing Materials' = 'marketing'
    'Packaging'           = 'packaging'
    'Large Format'        = 'large-format'
    'Signage'             = 'signage'
    'Apparel'             = 'apparel'
}

Write-Host "`nEnsuring product categories..."
$existingCats = @{}
$catCsv = wpc wc product_cat list --user=$AdminUserId "--fields=slug" --format=csv
foreach ($line in ($catCsv | Select-Object -Skip 1)) {
    $s = $line.Trim().Trim('"')
    if ($s) { $existingCats[$s] = $true }
}
foreach ($name in $catMap.Keys) {
    $slug = $catMap[$name]
    if (-not $existingCats.ContainsKey($slug)) {
        wpc wc product_cat create --user=$AdminUserId --name=$name --slug=$slug --porcelain | Out-Null
        Write-Host " - created category '$name' ($slug)"
    }
    else {
        Write-Host " - category '$name' ($slug) already exists"
    }
}

# --- Products ---------------------------------------------------------------
function To-Slug {
    param([string]$Text)
    return (($Text.ToLower() -replace '[^a-z0-9]+', '-').Trim('-'))
}

function Ensure-Product {
    param([string]$Name, [string]$Description, [string]$Price, [string]$CategorySlug, [bool]$Featured)
    $slug = To-Slug $Name
    $productId = ("$(wpc post list --post_type=product --name=$slug --post_status=any --format=ids)").Trim()

    $pargs = @("--name=$Name", "--type=simple", "--status=publish", "--short_description=$Description", "--user=$AdminUserId")
    $hasPrice = $false
    if ($Price -and [double]$Price -gt 0) { $pargs += "--regular_price=$Price"; $hasPrice = $true }

    if ($productId) {
        wpc wc product update $productId @pargs | Out-Null
        $verb = 'updated'
    }
    else {
        $productId = ("$(wpc wc product create @pargs --porcelain)").Trim()
        $verb = 'created'
    }
    if ($CategorySlug) { wpc post term set $productId product_cat $CategorySlug | Out-Null }
    if ($Featured) { wpc post term add $productId product_visibility featured | Out-Null }

    $tag = if ($hasPrice) { "`$$Price" } else { "quote" }
    Write-Host (" - {0,-7} {1,-22} {2,-8} [{3}]" -f $verb, "'$Name'", $tag, $CategorySlug)
}

Write-Host "`nImporting products from $([System.IO.Path]::GetFullPath($ProductsCsv))..."
$rows = Import-Csv $ProductsCsv
$index = 0
foreach ($row in $rows) {
    if (-not $row.Name) { continue }
    $catSlug = $catMap[$row.Category]
    if (-not $catSlug) { $catSlug = 'uncategorized' }
    Ensure-Product -Name $row.Name -Description $row.'Short Description' -Price $row.'Starting Price' -CategorySlug $catSlug -Featured ($index -lt 6)
    $index++
}

# --- Flush ------------------------------------------------------------------
Write-Host "`nFlushing rewrite rules..."
wpc rewrite flush --hard | Out-Null

Write-Host "`nDone. Visit $siteUrl" -ForegroundColor Green
