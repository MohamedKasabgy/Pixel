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

## MVP priority

1. Visual completion for all core client-facing pages.
2. WooCommerce catalog/cart/checkout setup.
3. Quote request + upload artwork flow.
4. Client portal + order tracking screens.
5. Admin/staff workflow for quote/order/file management.

## Notes

See `PROJECT_KNOWLEDGE.md` for full requirements, scope, visual direction, and feature roadmap.
