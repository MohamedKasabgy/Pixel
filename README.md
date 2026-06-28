# Pixel Signs & Printing — WordPress Project

A professional WordPress + WooCommerce website for a commercial printing business with product catalog, configurable print products, artwork uploads, quote requests, client portal, order tracking, checkout, invoices, and admin/staff workflows.

## Stack

- WordPress CMS
- WooCommerce for products, cart, checkout, coupons, customers, and base orders
- Custom theme: `pixel-signs-theme`
- Custom plugin: `pixel-core`

## Repo structure

```text
.
├── PROJECT_KNOWLEDGE.md
├── README.md
├── docs/
│   ├── BUILD_PLAN.md
│   ├── PRICING_SCOPE_NOTES.md
│   └── reference-images/
├── data/
│   ├── sample-products.csv
│   └── page-map.md
└── wp-content/
    ├── themes/
    │   └── pixel-signs-theme/
    └── plugins/
        └── pixel-core/
```

## Local setup

1. Install WordPress locally.
2. Install and activate WooCommerce.
3. Copy this repo's `wp-content/themes/pixel-signs-theme` into your WordPress `wp-content/themes` folder.
4. Copy this repo's `wp-content/plugins/pixel-core` into your WordPress `wp-content/plugins` folder.
5. Activate the **Pixel Signs Theme**.
6. Activate the **Pixel Core** plugin.
7. Create pages and assign templates:
   - Home → front page
   - Products → Products page template
   - Request Quote → Quote Request template
   - Upload Artwork → Artwork Upload template
   - Client Dashboard → Client Dashboard template
   - Order Tracking → Order Tracking template
   - Cart / Checkout → WooCommerce pages
8. Import products from `data/sample-products.csv` or create WooCommerce products manually.

> WordPress page/product content lives in the **site database, not in git**. A fresh
> LocalWP install will render the WordPress fallback ("Create a page and assign it as the
> front page...") even when the theme/plugin code is current. Run the setup script below
> to seed pages, the front page, and sample products.

### Automated content setup

Idempotent scripts handle steps 1, 7, and 8 via WP-CLI (the LocalWP site must be running):

- **macOS:** `scripts/setup-local-pages.sh`
- **Windows (PowerShell):** `scripts/setup-local-content.ps1` — also creates the Home/Products
  pages, assigns the static front page, and imports `data/sample-products.csv`.

```powershell
powershell -ExecutionPolicy Bypass -File scripts\setup-local-content.ps1
```

Paths auto-detect for a standard LocalWP install; override with `-SitePath`, `-PhpExe`,
`-WpCli`, or `-PhpIni` if needed. If WooCommerce's "Launch Your Store" coming-soon mode is on
(`woocommerce_coming_soon`), the site is hidden from anonymous visitors — set it to `no` to make
the local site public.

### Keeping LocalWP in sync with the repo

The theme/plugin under `wp-content/` are **separate copies** from the running LocalWP install
(`<Local Sites>/pixel/app/public/wp-content/`), so they drift apart after each `git pull`. To
keep the running site always in sync, replace the LocalWP copies with links to this repo:

```powershell
# from an elevated/Developer-Mode shell; junctions (-ItemType Junction) need no admin
New-Item -ItemType Junction -Path "<Local Sites>\pixel\app\public\wp-content\themes\pixel-signs-theme"  -Target "<repo>\wp-content\themes\pixel-signs-theme"
New-Item -ItemType Junction -Path "<Local Sites>\pixel\app\public\wp-content\plugins\pixel-core"        -Target "<repo>\wp-content\plugins\pixel-core"
```

After linking, every `git pull`/checkout is reflected on `pixel.local` immediately — no re-copy needed.

## MVP priority

1. Visual completion for all core client-facing pages.
2. WooCommerce catalog/cart/checkout setup.
3. Quote request + upload artwork flow.
4. Client portal + order tracking screens.
5. Admin/staff workflow for quote/order/file management.

## Notes

See `PROJECT_KNOWLEDGE.md` for full requirements, scope, visual direction, and feature roadmap.
