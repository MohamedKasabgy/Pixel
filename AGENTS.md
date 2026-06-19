# Agent Instructions — Pixel Signs & Printing

You are working on a WordPress + WooCommerce commercial printing project.

## Product direction

Build a polished English-only printing website inspired by the supplied reference screenshots: premium, clean, industrial, orange/brown accent, white/gray cards, strong typography, client portal, quote flow, product configurators, and professional checkout/order tracking screens.

## Tech constraints

- Use WordPress as CMS.
- Use WooCommerce for products, cart, checkout, customers, coupons, and base orders.
- Use custom plugin `pixel-core` for quote requests, artwork upload, custom order statuses, staff roles, client portal shortcodes, and print-specific workflows.
- Use custom theme `pixel-signs-theme` for all visual UI.
- Keep frontend English-only.
- Avoid over-engineering in the first 3-week MVP.

## Work rules

- Read `PROJECT_KNOWLEDGE.md` before making architecture decisions.
- Prioritize visual completeness and client demo quality first.
- Advanced integrations can be placeholders if they block MVP speed.
- Do not store payment card data; rely on WooCommerce payment gateways.
- Uploaded files must be validated and restricted to safe artwork file types.
- Use WordPress nonces and capability checks for forms/admin actions.
- Keep code easy to hand off to another WordPress developer.

## MVP acceptance

- Home, product listing, product detail/configuration, quote request, artwork upload, cart, checkout, invoice, client dashboard, order tracking, static policy/support pages, and admin/staff flow must visually exist.
- Customer can request quote and upload artwork.
- Customer can place a WooCommerce order.
- Customer can see order status from portal/tracking screen.
