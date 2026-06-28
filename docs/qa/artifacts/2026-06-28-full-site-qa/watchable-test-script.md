# Watchable Test Script — Pixel Full Site QA (2026-06-28)

This is the exact human‑watchable route used during the guided pass, so anyone can replay it in a real browser. Run it at **desktop 1920×1080** first, then repeat the key steps on a phone width (use Chrome DevTools device mode — see note at the end).

**Pre‑reqs:** LocalWP `Pixel` site running; WooCommerce **Coming Soon mode OFF**; at least 1 product in the catalog. Base URL: `http://pixel.local`.

---

## A. Public storefront (desktop)

1. **Home** — `http://pixel.local/`
   - Confirm: announcement bar, sticky header (brand/search/utility nav), hero, Print Categories (6), Trusted‑by, Why Choose Us, How It Works, "Ready to Print?" CTA, full footer.
   - Watch for: browser tab title (currently "Pixel"), footer social links (go to /contact/), Packaging card link.
2. **Products** — `/products/`
   - Confirm: 12 product cards, filter sidebar, "View Details / Request Quote / Upload Artwork" buttons, no horizontal scroll.
   - Watch for: gray placeholder card images.
3. **Product detail** — `/product/business-cards/`
   - Confirm: gallery, badges, configurator (Size, Material, Finish, Print Quantity, Turnaround, Artwork Status + Special Instructions), price, qty stepper, Add to Cart / Request Quote / Upload Artwork, related products, FAQ.

## B. Configure → Cart → Checkout (the buy flow)

4. **Add to Cart with NO options** → expect required‑field validation (blocks submit). ✅
5. **Select all 6 options** (e.g. Standard / Premium Cardstock / Matte / 500 / Standard 3‑5 days / Ready to Print) → **Add to Cart**.
   - Watch for: only the header "Cart (1)" updates — no on‑page confirmation.
6. **Cart** — `/cart/`
   - Confirm: line item shows all configured specs as meta + Project Name; "added to cart" notice; Order Summary.
   - Watch for: **Print Quantity 500 but total still $19.99**; no editable qty; no coupon field.
7. **Checkout** — `/checkout/`
   - Confirm: coupon toggle, billing address fields.
   - **STOP — do not pay.** Observe: **"no available payment methods"** and **Shipping & Handling: –**, plus the **header text overlap** ("2. Enter Your Address").

## C. Quote + Artwork (print workflow)

8. **Request a Quote** — `/request-quote/?product=business-cards`
   - Confirm: product is pre‑selected; fill Name/Email/Phone/Quantity + check terms → **Submit Quote Request** → success message. (Creates a Quote Request in admin.)
9. **Upload Artwork** — `/upload-artwork/`
   - Fill Name/Email/Order#/Project. Upload a **blocked file** (`docs/qa/fixtures/blocked-script.php`) → expect **"Unsupported file type…"** rejection.
   - Then upload a **valid PDF** (`docs/qa/fixtures/valid-artwork.pdf`) → expect **"Artwork uploaded…"** success. (File is stored outside the public web root.)

## D. Tracking + Portal

10. **Order Tracking** — `/order-tracking/`
    - Invalid: order `999999` + `nobody@example.com` → generic "could not find" (privacy‑safe).
    - Valid: a real order # + its billing email → production timeline, items, shipping details.
11. **Login** — `/my-account/` → sign in as the test customer.
    - Watch for: only a Sign‑in form is shown (no visible Register).
12. **Client Dashboard** — `/client-dashboard/`
    - Confirm: "Welcome, <name>", Active Quotes / Active Orders / Uploaded Files counts, recent activity table with a Track action.
13. **Security check** — while logged in as the customer, visit `/wp-admin/` → expect redirect to `/my-account/`.

## E. Admin (staff)

14. Log in as an administrator → `/wp-admin/`.
15. **Quote Requests** — `wp-admin/edit.php?post_type=pixel_quote` → confirm custom columns (Customer/Email/Product/Quantity/Status/Date) + status filter; the submitted quote appears.
16. **Artwork Files** — `wp-admin/edit.php?post_type=pixel_artwork` → confirm columns + status filter; the uploaded file appears.
    - Watch for: the **File column shows "—"** in the list.

## F. Content pages

17. `/about/`, `/contact/`, `/faq/` render; **Contact form** present but `action="#"` (non‑functional); **FAQ** uses native `<details>` accordion (works).
18. Footer legal links — note **Privacy Policy → /privacy-policy/** (WP boilerplate) vs the branded `/privacy/` template; default **Sample Page** still published.

---

## Mobile note

Phone‑width (390/360px) could **not** be reproduced in the headed automation browser (it clamps to ~585px). To replay mobile: open Chrome DevTools → device toolbar → iPhone 12 / 360px, and repeat sections **A, B, and D**, watching for horizontal scroll, tap‑target size, and the checkout header overlap.

## Fixtures used (`docs/qa/fixtures/`)

`valid-artwork.pdf`, `valid-artwork.png`, `valid-artwork.jpg`, `blocked-script.php`, `blocked-binary.exe`, `blocked-code.js`, `oversized-6mb.txt`.
