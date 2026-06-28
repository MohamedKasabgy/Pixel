# Pixel Signs & Printing — Full Site QA Report

**Date:** 2026-06-28
**Tester:** Claude Code (acting as senior QA / WordPress-WooCommerce tester / design QA)
**Branch:** `qa/full-site-qa-2026-06-28`
**Build:** WordPress 7.0 · WooCommerce 10.8.1 · theme `pixel-signs-theme` · plugin `pixel-core`

---

## 1. Executive Summary

The site is **substantially built and, in large parts, genuinely impressive** for a 3‑week MVP. The custom print workflow — **product configurator, quote requests, secure artwork upload, order tracking, and a real per‑user client portal** — is implemented well and largely functional, with good server‑side validation, nonces, and private file storage outside the web root.

However, the **commercial checkout cannot complete an order**: no WooCommerce payment gateway is enabled and no shipping methods are configured. This breaks the single most important commerce acceptance criterion ("Customer can place a WooCommerce order") and blocks the payment‑success/invoice screens from being reachable. There are also several polish and content‑hygiene issues that matter for a client demo.

**Overall verdict:** Strong custom feature work; **not demo‑ready for the buy flow until payment + shipping are configured**. The fixes for the top issues are mostly **configuration**, not deep code work.

### Findings by severity

| Severity | Count | Meaning |
| --- | --- | --- |
| **P0** | 1 | Blocks checkout/login/quote/upload or exposes data |
| **P1** | 1 | Breaks a core flow or looks broken in demo |
| **P2** | 8 | Important UX / responsive / validation / design |
| **P3** | 12 | Minor polish / copy / low‑risk |
| **BLOCKED** | 3 | Could not be tested (see §7) |
| **Total** | 25 | |

### Top 5 most important problems

1. **P0 — Checkout has no payment method.** `/checkout/` shows *"Sorry, it seems that there are no available payment methods."* `wp-cli` confirms BACS, Cheque, and COD all disabled. No order can be placed. *(PX‑001)*
2. **P1 — No shipping zones/methods configured.** Checkout shows *Shipping & Handling: –*; there are no Standard/Express/Local Pickup options. *(PX‑002)*
3. **P2 — Pricing ignores configured quantity/options.** Selecting *Print Quantity = 500* still totals **$19.99**; the cart records the spec but price is a flat "starting price." Misleading for a print‑commerce demo. *(PX‑003)*
4. **P2 — Contact form is non‑functional.** The message form posts to `action="#"` with no nonce/handler — submitting does nothing. *(PX‑005)*
5. **P2 — Checkout header text overlap** ("2. Enter Your Address" overlaps the intro line) — looks broken on the most trust‑sensitive page. *(PX‑004)*

### What works well (notable strengths)

- **Artwork upload is secure and correct:** blocked `.php` rejected server‑side with a clear message (*"Unsupported file type…"*), valid PDF accepted, and the file is stored in `app/pixel-private-uploads/` **outside the public web root**. Nonces present.
- **Quote flow works end‑to‑end:** product prefill via `?product=`, required‑field validation, a real `pixel_quote` record created, success message shown, and a well‑built admin list (Customer/Email/Product/Quantity/Status + status filter).
- **Order tracking works:** privacy‑safe generic error for bad lookups (requires order # **and** email), and a polished production timeline for valid orders (Order Placed → Artwork Review → … → Completed) with items, files, and shipping details.
- **Client portal shows real per‑user data** (Active Orders/Quotes/Files counts + recent activity), not static demo data.
- **Security basics hold:** customer hitting `/wp-admin/` is redirected to `/my-account/`; forms use nonces.
- **Homepage and product/category/cart layouts are clean, premium, responsive** (no horizontal overflow at any tested width), with disciplined orange accent use.

---

## 2. Tested Environment

| Item | Value |
| --- | --- |
| URL | `http://pixel.local` (LocalWP, nginx :10004) |
| WordPress / WooCommerce | 7.0 / 10.8.1 |
| Theme / Plugin | `pixel-signs-theme` / `pixel-core` (both active) |
| Coming Soon mode | OFF (was ON at start of session; corrected before testing) |
| Catalog | 12 WooCommerce products |
| Currency / Base | USD / US:NY |
| Payment gateways | BACS, Cheque, COD all **present but disabled** |
| Shipping | only default catch‑all zone, **no methods** |

## 3. Viewports tested

| Viewport | Status |
| --- | --- |
| Desktop 1920×1080 | ✅ Primary pass |
| ~585×844 (mobile/tablet breakpoint) | ✅ Achievable minimum; mobile nav + stacking engaged, no overflow |
| 390×844 (phone) | ⛔ BLOCKED — headed Chrome clamps min window width (~585px viewport). See PX‑023 |
| 360×800 (narrow phone) | ⛔ BLOCKED — same limitation |

> Note: a previous Playwright run (Abdullah, 2026‑06‑21) validated 375×812 with no overflow; the responsive CSS is known‑good at that width. This run could not independently re‑confirm 390/360 due to the headed‑browser window‑width floor.

## 4. Credentials / access used

- **Admin:** verified via local **wp-cli** for data, and a **temporary `qa-admin`** administrator was created to view the custom admin screens in‑browser. *(Passwords are not recorded in this report.)*
- **Customer:** test customer `qacustomer` / `qa-customer@example.com` created (registration UI was hidden in a dropdown; account created via wp-cli for reliability).
- **Payment:** no sandbox gateway enabled → checkout completion intentionally **not attempted**; no card data entered.

### ⚠️ QA test data to clean up (created during this run)

| Type | Identifier | Note |
| --- | --- | --- |
| User | `qa-admin` (ID 3, administrator) | **Delete after review** |
| User | `qacustomer` (ID 2, customer) | Optional to keep/delete |
| Order | #59 (Business Cards, processing) | Seeded to test tracking/portal |
| Quote | ID 56 ("…Business Cards – QA Tester") | From quote‑form test |
| Artwork | ID 57 (+ `app/pixel-private-uploads/valid-artwork.pdf`) | From upload test |

Removal (run from LocalWP shell or with the QA wp-cli wrapper):
```
wp user delete 3 --yes
wp post delete 56 57 59 --force
# optional: wp user delete 2 --yes ; remove app/pixel-private-uploads/valid-artwork.pdf
```

## 5. Pages & flows tested

**Public:** Home, Products listing, Product detail/configurator (Business Cards), Request a Quote, Upload Artwork, Contact, FAQ, footer/legal links, page inventory (wp-cli).
**Commerce:** product configurator (6 required options), add‑to‑cart (validation + success), Cart (line‑item meta, totals, coupon/qty presence), Checkout (fields, gateways, coupon, shipping).
**Quote/Artwork:** quote submit (valid + prefill), artwork upload (blocked `.php` rejected, valid PDF accepted, private storage verified).
**Portal/Auth:** customer login, Client Dashboard (real data), My Account, `/wp-admin/` privilege check.
**Tracking:** invalid lookup (privacy‑safe), valid lookup (#59) with timeline.
**Admin:** Dashboard, Quote Requests list, Artwork Files list (custom columns + status filters).

## 6. Flows confirmed functional

| Flow | Result |
| --- | --- |
| Browse → product detail → configure → add to cart | ✅ (options carry to cart as line‑item meta) |
| Add‑to‑cart validation (empty required options) | ✅ blocked (HTML5 required) |
| Request a Quote (valid + product prefill) | ✅ creates `pixel_quote`, success msg |
| Artwork upload — blocked extension | ✅ rejected server‑side, clear message |
| Artwork upload — valid file | ✅ accepted, **stored privately** off web‑root |
| Order tracking — invalid | ✅ privacy‑safe generic error |
| Order tracking — valid | ✅ timeline + items + shipping |
| Customer login → Client Portal | ✅ real per‑user metrics/activity |
| Customer → `/wp-admin/` | ✅ redirected (no admin access) |
| Admin quote/artwork screens | ✅ custom columns + status filters |
| **Checkout → place order** | ❌ **blocked (no payment method)** |

## 7. Blocked areas

- **Checkout completion, Payment Success, Payment Failed, Invoice** — unreachable without an enabled gateway (PX‑001/PX‑024). These screens were **not** visually verified end‑to‑end.
- **True phone widths (390/360px)** — headed‑browser window‑width floor (PX‑023).
- **Wishlist** — listed as a requirement but no wishlist UI was found in nav/account during the crawl; presence unconfirmed (PX‑025).

## 8. Findings table

See `issues.csv` for the full structured list (id, severity, steps, evidence, owner). Summary:

| ID | Sev | Page | Title |
| --- | --- | --- | --- |
| PX‑001 | P0 | /checkout/ | No enabled payment gateway — order can't be placed |
| PX‑002 | P1 | /checkout/ | No shipping zones/methods configured |
| PX‑003 | P2 | product/cart | Price ignores configured quantity/options (flat $19.99) |
| PX‑004 | P2 | /checkout/ | Header text overlap ("2. Enter Your Address") |
| PX‑005 | P2 | /contact/ | Contact form non‑functional (`action="#"`, no nonce) |
| PX‑006 | P2 | /cart/ | No editable quantity control in cart |
| PX‑007 | P3 | /cart/ | No coupon field on cart page (present at checkout) |
| PX‑008 | P2 | footer | Privacy link → WP boilerplate, not branded `/privacy/` |
| PX‑009 | P2 | /my-account/ | No visible Register option (only in dropdown) |
| PX‑010 | P2 | admin Artwork | File column empty in list view |
| PX‑011 | P2 | products/product | Gray placeholder product images (not image‑led) |
| PX‑012 | P3 | product | No on‑page add‑to‑cart confirmation |
| PX‑013 | P3 | home | Browser title is just "Pixel" |
| PX‑014 | P3 | footer | Social links are placeholders (→/contact/) |
| PX‑015 | P3 | Pages | Duplicate/orphan + default Sample Page |
| PX‑016 | P3 | page 18 | Double‑encoded `&amp;` in title |
| PX‑017 | P3 | footer | Newsletter input has no `name` |
| PX‑018 | P3 | front‑end | jQuery Migrate loaded (dev mode) |
| PX‑019 | P3 | legal | Starter/placeholder legal copy |
| PX‑020 | P3 | home | Packaging card has no category filter |
| PX‑021 | P3 | /upload-artwork/ | Order number required for all uploads |
| PX‑022 | P3 | portal/tracking | Order label inconsistency (#ORD‑59 vs Order #59) |
| PX‑023 | BLK | all | 390/360 phone widths untestable (headed browser) |
| PX‑024 | BLK | payment/invoice | Unreachable (blocked by PX‑001) |
| PX‑025 | BLK | wishlist | Feature not located |

## 9. Design QA (emil-design-eng lens)

| Before | After | Why |
| --- | --- | --- |
| Add‑to‑cart only bumps the header "Cart (n)" silently | Show an inline confirmation/toast ("Added — View cart") scaling in from the button, `ease-out` ~180ms | Feedback: the UI must visibly acknowledge the action; silent state changes read as broken |
| Checkout heading overlaps intro copy | Fix stacking/margins so the step label and intro never collide | Overlapping text on the payment page destroys trust at the worst moment |
| Product cards/gallery show flat gray placeholders | Real product photography with consistent aspect ratio; until then, a branded gradient + product name | Product cards must be image‑led and scannable; gray boxes read as unfinished |
| Configurator shows "$19.99" as the live total regardless of qty | Label as "From $19.99" (or compute the real total) | Showing a fixed total next to a 500‑qty selection implies a price that isn't real |
| Buttons (Add to Cart, CTAs) — no observed `:active`/press state | Add `transform: scale(0.97)` on `:active`, `transition: transform 160ms ease-out` | Pressable elements should feel responsive to press |
| Native validation popups only ("Please select an item") | Inline, styled field errors under each required select | Native bubbles look unbranded on a premium configurator |
| Footer social + newsletter are placeholders/no‑op | Wire to real profiles + a working newsletter endpoint, or hide | Dead controls erode credibility in a demo |

## 10. Mobile‑specific findings

- At the achievable **~585px** width, **all tested pages had no horizontal overflow** (`scrollWidth − innerWidth ≈ −23`), the **hamburger menu engaged**, and category/why‑choose‑us cards stacked cleanly.
- The header collapses to **brand + search + Menu toggle** on mobile (good — matches the earlier overflow fix).
- **Could not test 390/360px** in this run (PX‑023). Recommend a headless Playwright device profile (`iPhone 12`) or Chrome DevTools device mode to confirm the narrowest phones, since the buy flow and configurator are the highest‑risk at small widths.

## 11. Recommended fix order

**Before any client demo of the buy flow (config, ~hours):**
1. PX‑001 — enable at least one payment gateway (COD/BACS for demo, or a sandbox gateway).
2. PX‑002 — add a shipping zone + methods (Standard/Express/Local Pickup).
3. Re‑run checkout → order‑received → invoice end‑to‑end (unblocks PX‑024).

**Demo polish (P2, theme/plugin):**
4. PX‑004 checkout header overlap · PX‑005 contact form handler · PX‑003 quantity/option pricing (or "From $" labeling) · PX‑006 cart quantity · PX‑008 privacy link · PX‑009 register entry point · PX‑010 artwork file link · PX‑011 product imagery.

**Content/hygiene (P3):**
5. PX‑013 title · PX‑014 social · PX‑015 duplicate/sample pages · PX‑016 encoded title · PX‑017 newsletter name · PX‑018 jQuery Migrate · PX‑019 legal copy · PX‑020 packaging filter · PX‑021 upload order‑number · PX‑022 order label.

**Verification gaps:**
6. PX‑023 phone widths (device emulation) · PX‑025 confirm/implement wishlist.

---

*Evidence: `screenshots/` (12 images). Test data created during this run is listed in §4 for cleanup. No source code was modified — QA‑only run.*
