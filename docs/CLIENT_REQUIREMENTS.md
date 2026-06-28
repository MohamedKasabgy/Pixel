# Client Requirements — Pixel Signs & Printing

> **Purpose:** A single, consolidated, planning-ready view of what the client actually asked for. Distilled from `PROJECT_KNOWLEDGE.md`, `README.md`, `AGENTS.md`, and `docs/PRICING_SCOPE_NOTES.md`. Use this as the source of truth when planning the next phase.
>
> **Legend:** ✅ Confirmed requirement · 🕒 Confirmed but deferred to a later phase · ❓ Pending client input (see §11)

---

## 1. Project Overview

Pixel Signs & Printing is a professional **WordPress + WooCommerce** website for a commercial printing / signage business. Customers browse and configure print products, upload artwork, request custom quotes, place and pay for orders, track production status, and access invoices/files through a client portal. Staff and admins manage products, prices, quotes, files, orders, coupons, and customers.

| Attribute | Value |
| --- | --- |
| Project type | Commercial printing website + customer portal + order/quote management |
| Language | English only ✅ |
| Platform | WordPress + WooCommerce + custom theme (`pixel-signs-theme`) + custom plugin (`pixel-core`) |
| Timeline | Professional launch/demo within **max 3 weeks** |
| Expected traffic | ~300 visitors/order-related visits per week initially |
| Launch catalog | ~10–15 products initially |
| Visual direction | Premium, clean, modern, industrial print brand; orange/brown accent, white/gray cards, dark footer |

**Guiding principle:** Deliver a complete, professional, client-facing platform that looks finished and supports the core business flow first. Advanced features may ship as placeholders/simplified workflows, then be upgraded after launch.

---

## 2. Business Goals (✅ Confirmed)

The site must let the business:

- Present the company professionally.
- Showcase printing categories and products.
- Let customers configure product options.
- Let customers upload artwork/files.
- Let customers request custom quotes.
- Let customers place orders and pay online.
- Let customers track order status.
- Let admin/staff manage products, prices, orders, quotes, files, coupons, and customers.
- Support future expansion (advanced pricing, proof approval, shipping integrations, analytics, staff ops).

---

## 3. User Roles & Permissions (✅ Confirmed)

| Role | Capabilities |
| --- | --- |
| **Customer** | Register/login, browse, configure products, upload artwork, request quote, place order, pay, track orders, view/download invoices, view uploaded/approved files. |
| **Employee / Staff** | Review quote requests, review uploaded artwork, update production/order statuses, communicate with customers, manage assigned tasks. |
| **Admin** | Full dashboard: manage products, options/prices, customers, orders, quotes, artwork/design files, coupons/promotions, pages/content; view reports/analytics. |

> Customer login and a customer portal are **required**.

---

## 4. Functional Requirements

### 4.1 Product Catalog & Configuration
- **FR-1** ✅ Browse products by category. Suggested categories: Large Format, Marketing, Signage, Apparel, Business Cards, Stickers & Labels, Packaging, Posters & Banners.
- **FR-2** ✅ Product detail page with a **configurator**: sizes, colors, materials, finishing, paper types, quantities, coating, printed sides, custom notes, artwork upload.
- **FR-3** ✅ Option control types: radio buttons, dropdowns, quantity selector, size/material cards, finish/coating dropdown, upload field, notes field, estimated total.
- **FR-4** ✅ Two pricing modes: **direct pricing** (priced products) and **request-a-quote** (complex products). Complex products use "Request Custom Quote" instead of forced exact pricing.
- **FR-5** ✅ Special **Signs / Boards** section with a step-based flow: sign type → size → material → finishing/mounting → upload artwork → notes/deadline → quote or add-to-cart. Materials: Acrylic, Dibond/Aluminum, Foam Board, PVC, Vinyl, Canvas, Metal, Wood.

### 4.2 Quotes
- **FR-6** ✅ Request-a-quote form: product/category, size, material, quantity, deadline, notes, artwork/reference file upload.
- **FR-7** ✅ Quote requests saved and visible in admin with customer details and status.
- **FR-8** ✅ Quote lifecycle / statuses (see §7). Staff reviews → sends price → customer accepts → quote converts to order → pay → production.

### 4.3 Artwork / File Upload
- **FR-9** ✅ Upload artwork during product/order/quote flow **and** later by order number.
- **FR-10** ✅ Accepted file types: PDF, AI, EPS, PSD, TIFF, JPG/JPEG, PNG, SVG (sanitized), ZIP.
- **FR-11** ✅ File status workflow: Uploaded, Under Review, Approved, Rejected, Revision Needed; staff/admin comments.
- **FR-12** ✅ Files attached to the correct order/quote/customer; private uploads never exposed publicly.

### 4.4 E-Commerce (WooCommerce)
- **FR-13** ✅ Cart, checkout, order summary, shipping method selection, payment method selection, order confirmation, customer order history.
- **FR-14** ✅ Coupons / discounts / offers.
- **FR-15** ✅ Wishlist.
- **FR-16** ✅ Payment success page + payment failed page.
- **FR-17** ❓ Payment gateway (depends on client country/bank/hosting). Mockups show Credit Card + Bank Transfer. **Do not store raw card data.**

### 4.5 Customer Portal & Order Tracking
- **FR-18** ✅ Customer dashboard showing active quotes, active orders, design files, invoices, recent activity.
- **FR-19** ✅ Order tracking with a status timeline, associated files, shipping details, tracking number, destination address.
- **FR-20** ✅ Portal pages: Dashboard, Active Orders, Order Tracking, Quote Requests, Quote Details, Design/Uploaded Files, Upload Files by Order Number, Invoices, Account Settings, Wishlist, Support.

### 4.6 Invoices
- **FR-21** ✅ Invoice view matching the polished mockup: company details, invoice #, dates, billed-to, payment status badge, line items, subtotal/tax/shipping/total, amount paid, balance due.
- **FR-22** ✅ Download-PDF and Print-invoice actions.

### 4.7 Shipping / Delivery
- **FR-23** ✅ Pages: Shipping Options, Delivery Tracking, Pickup Locations, Delivery Policy.
- **FR-24** ✅ Delivery methods: Standard, Express, Local Pickup.
- **FR-25** ✅ MVP: admin manually enters carrier + tracking number. 🕒 Carrier API integration is a later phase.

### 4.8 Admin / Staff Management
- **FR-26** ✅ Admin dashboard overview with KPI cards (revenue, pending quotes, active orders, new files), urgent tasks, recent activity.
- **FR-27** ✅ Manage orders, quotes, products, product options/pricing, categories, customers, artwork review, coupons.
- **FR-28** ✅ Admin can edit prices and content from the dashboard.
- **FR-29** 🕒 Proof approval tasks, reports, advanced analytics — professional-looking but may be simplified at launch.

---

## 5. Required Pages

### Public (✅)
Home · Products/Categories · Product Details · Request a Quote · Upload Artwork · About · Contact · FAQ · Cart · Checkout · Payment · Payment Success · Payment Failed · Invoice · Shipping Options · Delivery Tracking · Pickup Locations · Delivery Policy · Privacy Policy · Terms & Conditions · Refund Policy
*(Optional/future: Blog / File Setup Guide.)*

### Customer Portal (✅)
Customer Dashboard · Active Orders · Order Tracking · Quote Requests · Quote Details · Design/Uploaded Files · Upload Files by Order Number · Invoices · Account Settings · Wishlist · Support.

### Admin / Staff (✅)
Dashboard Overview · Orders Management · Order Details · Quote Requests Management · Quote Builder · Product Management · Pricing Management · Category Management · Customer Management · Artwork Review · Proof Approval Tasks · Coupons · Reports · Analytics · Settings.

---

## 6. Non-Functional Requirements

- **NFR-1** ✅ **Responsive** — mobile-first; no horizontal overflow; usable tap targets.
- **NFR-2** ✅ **Visual quality** — premium/clean/industrial; orange accent (~`#ff8200`), white cards w/ soft shadow, dark footer; desktop max width ~1180–1280px. ❓ Replace placeholder tokens with the client's real brand colors/logo/fonts.
- **NFR-3** ✅ **Language** — English only.
- **NFR-4** ✅ **Security** — validate/restrict uploads, sanitize filenames, restrict executables, permission-checked downloads, WP nonces + capability checks on forms/admin actions, no raw card data stored.
- **NFR-5** ✅ **Maintainability** — custom business logic lives in the plugin (not theme templates); code kept handoff-friendly for another WP developer.
- **NFR-6** ✅ **Architecture** — WordPress + WooCommerce; do **not** build a standalone React/Next app.

---

## 7. Order & Quote Statuses (✅ Confirmed direction)

**Quote:** Quote Requested · Under Review · Need More Info · Quote Ready · Accepted · Rejected · Expired.

**Order:** Pending Payment · Paid · Artwork Needed · Artwork Uploaded · File Review · Proof Sent · Proof Approved · In Production · Quality Check · Ready for Pickup · Shipped · Out for Delivery · Delivered · Completed · Cancelled · Refunded.

**Staff task priority:** High · Medium · Low.

---

## 8. Out of Scope for Launch (✅ Confirmed exclusions)

Product reviews/ratings · online design editor · full shipping-carrier API automation · ERP integration · multi-language · native mobile app · marketplace features · advanced CRM.

---

## 9. Phased Roadmap (✅ Confirmed direction)

| Phase | Theme | Includes |
| --- | --- | --- |
| **Phase 1 — Professional MVP** | Visual site + core flow | All visual pages, WooCommerce catalog/cart/checkout, quote request, artwork upload, customer portal + tracking, admin basics, coupons, wishlist, invoice view. |
| **Phase 2 — Advanced Operations** | 🕒 | Dynamic pricing matrix, proof approvals, production queue, shipping-carrier API, staff department permissions, analytics dashboards. |
| **Phase 3 — Growth** | 🕒 | Multilingual, CRM integrations, marketing automation, advanced B2B/company pricing, email/SMS automation. |

---

## 10. Acceptance Criteria (Phase 1 launch/demo)

The project is acceptable for first launch/demo when:

- [ ] English only; design matches the premium Pixel direction.
- [ ] Homepage looks complete and professional.
- [ ] Products page shows 10–15 launch products/categories.
- [ ] Product detail page has configurable options + upload/quote CTAs.
- [ ] Customer can register/login.
- [ ] Customer can request a quote.
- [ ] Customer can upload artwork.
- [ ] Customer can use cart/checkout.
- [ ] Customer can track order status.
- [ ] Customer can see an invoice.
- [ ] Admin can manage products/prices/content.
- [ ] Admin can manage orders/quotes/customers/files.
- [ ] Coupons/discounts exist; wishlist exists.
- [ ] Site is responsive on mobile.
- [ ] No broken placeholder links in main navigation.
- [ ] Legal/support pages exist (content may be initially simple).
- [ ] Security basics respected for uploads, payments, customer data.

---

## 11. Open Questions — Pending Client Input (❓)

These block final scope/pricing and several launch follow-ups:

1. Brand logo files, exact color codes, font names/licenses.
2. Final list of the first 10–15 products.
3. Pricing tables/options per product; which products are direct-priced vs quote-only.
4. Payment gateway / country / currency.
5. Tax/VAT rules.
6. Shipping carriers and zones; pickup location details.
7. Max upload size; final required file formats.
8. Email templates / notification rules.
9. Staff workflow: who reviews files, who updates statuses.
10. Whether proof approval is required at launch or later.
11. Whether the customer pays before or after proof approval.
12. Final legal content (privacy, refund, terms, delivery policy).
13. Hosting/WordPress access; domain/DNS access.
14. Real partner/customer logos + permission to display them.
15. Pricing model: live-formula calculation vs WooCommerce variable products for MVP (current recommendation: variable products for MVP; dynamic pricing matrix in Phase 2).
16. Whether wishlist is MVP or Phase 2 *(listed as both — to reconcile)*.

---

## 12. Notes for Next-Phase Planning

- Current build state: theme design system + plugin scaffolding exist; **product category filtering** is the main fully-built feature. Most screens still render hardcoded/demo data.
- The portal (`[pixel_client_portal]`) and tracker (`[pixel_order_tracker]`) currently output static demo data — wiring them to real per-user orders/quotes is a major Phase-1-completion item.
- `single-product.php` and `products.php` are currently hardcoded, not WooCommerce-driven — connecting them to real WooCommerce products is the largest gap to closing the catalog → cart flow.
