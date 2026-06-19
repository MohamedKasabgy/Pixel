# PROJECT KNOWLEDGE — Pixel Signs & Printing

**Project type:** WordPress commercial printing website + customer portal + order/quote management system  
**Working project name:** Pixel Signs & Printing  
**Primary language:** English only  
**Target platform:** WordPress + WooCommerce + custom theme/plugin layer  
**Deadline:** Professional launch/demo within a maximum of 3 weeks  
**Initial expected traffic:** About 300 visitors/orders-related visits weekly  
**Current launch catalog size:** About 10–15 products/categories initially  
**Visual direction:** Premium, clean, modern, industrial printing brand inspired by the provided screenshots and PrintRunner-style commercial print flows.

---

## 1. Executive Summary

Pixel Signs & Printing is a professional online printing platform. The website should allow customers to browse printing products, configure product options, upload artwork, request quotes, place orders, pay, track order status, and access invoices/files from a client portal.

The project will be built with WordPress because the client needs a professional, manageable website quickly. WordPress should be used as the CMS foundation, with WooCommerce handling catalog/cart/checkout/order basics, and custom theme/plugin work handling the print-specific flow: quote requests, artwork uploads, order statuses, customer portal views, admin/staff workflow, and polished client-facing pages.

The first goal is not to build an over-complex enterprise system. The first goal is to deliver a complete, professional, client-facing platform that looks finished and supports the core business flow. Advanced features can be implemented as placeholders or simplified workflows at first, then upgraded after launch.

---

## 2. Business Goal

Build a commercial printing website for a printing/signage business that can:

- Present the company professionally.
- Showcase printing categories and products.
- Let customers configure product options.
- Let customers upload artwork/files.
- Let customers request custom quotes.
- Let customers place orders and pay online.
- Let customers track order status.
- Let the admin/staff manage products, prices, orders, quotes, files, coupons, and customers.
- Support future expansion into advanced pricing, proof approval, shipping integrations, analytics, and staff operations.

---

## 3. Client Discovery Answers Already Known

### Product scope

- Launch will include around **10 to 15 products** initially.
- There will be a dedicated **Signs / Boards / لوحات** section with step-by-step configuration.
- Products will have multiple options such as:
  - Sizes
  - Colors
  - Materials
  - Finishing
  - Paper types
  - Quantities
  - Coating
  - Printed sides
  - Custom notes
  - Artwork/file upload

### Product customization

- Customers should be able to customize/configure products inside the website.
- Customers should be able to upload files/artwork during quote/order flow.
- Some products may use direct pricing.
- Some products may require a custom quote.

### Customer account and tracking

- Customer login is required.
- Customer portal is required.
- Customer order tracking is required.
- Customer should be able to follow order status.
- Customer should see active quotes, active orders, design files, invoices, and recent activity.

### Roles and permissions

Required roles:

1. **Customer**
   - Register/login.
   - Browse products.
   - Configure products.
   - Upload artwork.
   - Request quote.
   - Place order.
   - Pay.
   - Track orders.
   - View/download invoice.
   - View uploaded/approved files.

2. **Employee / Staff**
   - Review quote requests.
   - Review uploaded artwork.
   - Update production/order statuses.
   - Communicate with customer.
   - Manage assigned tasks.

3. **Admin**
   - Full dashboard access.
   - Manage products.
   - Manage product options/prices.
   - Manage customers.
   - Manage orders.
   - Manage quote requests.
   - Manage artwork/design files.
   - Manage coupons/promotions.
   - Manage pages/content.
   - View reports/analytics.

### Admin management

- Full admin panel required.
- Admin must be able to edit prices and content from the dashboard.
- Products, orders, customers, quotes, uploaded files, coupons, and status updates should be manageable.

### Marketing/commercial features

- Coupons/discounts/offers are required.
- Cart is required.
- Wishlist is required.
- Reviews/ratings are **not required for now**.

### Language and identity

- Website should be English only.
- Brand identity exists: logo, colors, and fonts are available from client.
- Domain and hosting exist / are available.

---

## 4. Visual Identity From Screenshots

The screenshots show a strong visual target. The site should feel modern, premium, clean, and industrial.

### General visual style

- Light gray/off-white background.
- Large white cards with soft borders and shadows.
- Rounded corners.
- Strong bold headings.
- Spacious layouts.
- Minimal visual clutter.
- Premium commercial/industrial print photography.
- Warm orange/brown accent color.
- Dark charcoal footer.
- Clear CTA buttons.
- Professional SaaS/e-commerce dashboard feel.

### Suggested visual tokens based on screenshots

These are approximate and should be replaced with the client’s actual brand values once logo/brand guide is confirmed:

```css
--color-bg: #f5f6f7;
--color-surface: #ffffff;
--color-ink: #17191c;
--color-muted: #666b73;
--color-border: #dedede;
--color-accent: #ff8200;
--color-accent-dark: #a85b00;
--color-accent-soft: #fde4cf;
--color-footer: #292e2d;
--radius-card: 16px;
--radius-button: 10px;
```

### Brand phrases seen in mockups

- “Precision in Every Pixel.”
- “High-fidelity precision in every pixel.”
- “Commercial Print Excellence.”
- “High-fidelity printing for every commercial need.”
- “Industrial scale solutions for every physical touchpoint.”

Final copy should be polished and made original for the actual client. Do not copy PrintRunner copy verbatim.

---

## 5. Screenshots / UI References Provided

The following screens were provided as visual references:

1. **Invoice page**
   - Download PDF button.
   - Print invoice button.
   - Invoice number.
   - Billed-to block.
   - Payment status.
   - Paid watermark.
   - Line items.
   - Subtotal/tax/shipping/total.
   - Amount paid and balance due.

2. **Admin dashboard overview**
   - Sidebar navigation.
   - Dashboard cards: revenue, pending quotes, active orders, new files.
   - Revenue chart.
   - Urgent tasks list.
   - Search bar.
   - Notifications.

3. **Homepage desktop**
   - Navbar with product categories.
   - Hero section with print machinery image.
   - Main headline with orange accent word.
   - Featured products.
   - “How It Works” process cards.
   - Dark footer.

4. **Client portal dashboard**
   - Welcome message.
   - Upload Artwork button.
   - New Quote button.
   - Active quotes card.
   - Orders in production card.
   - Need assistance card.
   - Recent activity table.

5. **Order tracking page**
   - Search by order number.
   - Order status timeline.
   - Associated files.
   - Shipping details.
   - Tracking number placeholder.
   - Destination address.

6. **Products listing page**
   - Filters sidebar.
   - Product grid.
   - Categories/material filters.
   - Product cards with images, descriptions, starting prices.

7. **Product details / configurator page**
   - Product image gallery.
   - Product title and description.
   - Benefits/feature icons.
   - Configuration panel.
   - Card size options.
   - Paper stock dropdown.
   - Printed sides radio buttons.
   - Finish/coating dropdown.
   - Quantity dropdown.
   - Estimated total.
   - Upload artwork.
   - Request custom quote.
   - FAQ section.

8. **Checkout page**
   - Shipping information.
   - Delivery method.
   - Payment method.
   - Order summary.
   - Complete order CTA.

9. **Cart page**
   - Cart items.
   - Quantity controls.
   - Remove action.
   - Order summary.
   - Secure checkout notice.

10. **Mobile homepage**
   - Mobile header.
   - Hero section.
   - Horizontal product cards.
   - “Why Pixel Printing?” section.
   - Email quote CTA.
   - Responsive footer.

---

## 6. Required Public Pages

The public website must visually include:

- Home
- Products / Categories
- Product Details
- Request a Quote
- File Upload
- About
- Contact
- FAQ
- Cart
- Checkout
- Payment
- Payment Success
- Payment Failed
- Invoice
- Shipping Options
- Delivery Tracking
- Pickup Locations
- Delivery Policy
- Privacy Policy
- Terms & Conditions
- Refund Policy

Optional/future page:

- Blog / Guides / File Setup Guide

---

## 7. Required Customer Portal Pages

Customer portal should include:

- Customer Dashboard
- Active Orders
- Order Tracking
- Quote Requests
- Quote Details for Customer
- Design Files / Uploaded Files
- Upload Files by Order Number
- Invoices
- Account Settings
- Wishlist
- Support / Contact Account Manager

---

## 8. Required Admin / Staff Pages

Admin/staff area should include:

- Admin Dashboard Overview
- Orders Management
- Order Details
- Quote Requests Management
- Quote Details / Quote Builder
- Product Management
- Product Options / Pricing Management
- Category Management
- Customer Management
- Design Files / Artwork Review
- Proof Approval Tasks
- Coupons / Discounts
- Reports Page
- Advanced Analytics Dashboard
- Settings

The admin dashboard does not need to be enterprise-level on day one, but it must look professional and support the basic real workflows needed for launch.

---

## 9. Core User Flows

### Flow A — Browse and order standard product

1. Customer opens homepage.
2. Customer browses categories/products.
3. Customer opens product details.
4. Customer configures options.
5. Customer uploads artwork or chooses to upload later.
6. Customer adds to cart.
7. Customer checks out.
8. Customer pays.
9. System creates order.
10. Customer receives confirmation.
11. Customer tracks order from portal.

### Flow B — Request custom quote

1. Customer opens Request Quote.
2. Customer selects product/category.
3. Customer enters size, material, quantity, deadline, notes.
4. Customer uploads artwork/reference file.
5. System creates quote request.
6. Staff/admin reviews request.
7. Staff/admin sends quote price.
8. Customer accepts quote.
9. Quote converts to order.
10. Customer pays.
11. Order enters production.

### Flow C — Upload artwork by order number

1. Customer opens Upload Files by Order Number.
2. Customer enters order number/email.
3. System verifies order.
4. Customer uploads artwork.
5. Staff/admin sees new file.
6. Staff/admin approves/rejects or asks for revision.

### Flow D — Track order

1. Customer enters order number or opens portal.
2. System displays status timeline.
3. Customer sees files, shipping details, and tracking number if available.
4. Status updates as staff/admin changes order stage.

### Flow E — Admin handles quote/order

1. Admin receives new quote/order.
2. Admin reviews details and files.
3. Admin sets price if custom quote.
4. Admin updates status.
5. Admin uploads proof if needed.
6. Customer approves proof.
7. Admin moves order into production.
8. Admin adds shipping/tracking.
9. Customer sees final delivery status.

---

## 10. Suggested Order / Quote Statuses

Recommended statuses:

### Quote statuses

- Quote Requested
- Under Review
- Need More Info
- Quote Ready
- Accepted
- Rejected
- Expired

### Order statuses

- Pending Payment
- Paid
- Artwork Needed
- Artwork Uploaded
- File Review
- Proof Sent
- Proof Approved
- In Production
- Quality Check
- Ready for Pickup
- Shipped
- Out for Delivery
- Delivered
- Completed
- Cancelled
- Refunded

### Staff task priorities

- High
- Medium
- Low

Examples from screenshots:

- Approve Proof: Apex Corp Banner
- Material Shortage: 3mm Dibond
- Review Quote #4920

---

## 11. Product Categories and Initial Catalog Direction

Initial catalog can include 10–15 products across these groups:

### Suggested main categories

- Large Format
- Marketing
- Signage
- Apparel
- Business Cards
- Stickers & Labels
- Packaging
- Posters & Banners

### Suggested products

1. Business Cards
2. Flyers
3. Brochures
4. Stickers
5. Labels
6. Posters
7. Vinyl Banners
8. Retractable Banners
9. Acrylic Lobby Signs
10. Vehicle Wraps / Vehicle Magnets
11. Window Vinyls
12. T-Shirts / Apparel
13. Packaging / Boxes
14. Hang Tags
15. Menus / Restaurant Print Materials

### Special section: Signs / Boards / لوحات

This section should have a step-based product flow. Suggested steps:

1. Choose sign type.
2. Choose size.
3. Choose material.
4. Choose finishing/mounting.
5. Upload artwork.
6. Add notes/deadline.
7. Request quote or add to cart.

Possible materials:

- Acrylic
- Dibond / Aluminum Composite
- Foam Board
- PVC
- Vinyl
- Canvas
- Metal
- Wood / Specialty material if applicable

---

## 12. Product Configurator Requirements

Product details page should support configurable options.

Common option types:

- Radio buttons
- Select dropdowns
- Quantity dropdown
- Size cards
- Material cards
- Finish/coating dropdown
- Upload field
- Notes field
- Estimated total
- “Need Bulk?” link
- “Upload Artwork” button
- “Request Custom Quote” button

Example for Business Cards:

- Card size: Standard / Slim / Square
- Paper stock: 14pt coated cover / premium stock / recycled stock
- Printed sides: Front only / Front & back
- Finish: Smooth matte / glossy / spot UV / soft touch
- Quantity: 250 / 500 / 1000 / custom
- Estimated total
- Shipping estimate

For complex products, do not force exact pricing. Use “Request Custom Quote”.

---

## 13. File Upload Requirements

Customers must be able to upload print files/artwork.

Recommended accepted file types:

- PDF
- AI
- EPS
- PSD
- TIFF
- JPG/JPEG
- PNG
- SVG if safe and sanitized

Recommended behavior:

- Allow upload during product/order/quote flow.
- Allow upload later by order number.
- Store files securely.
- Attach files to order/quote/customer.
- Show file status: Uploaded, Under Review, Approved, Rejected, Revision Needed.
- Allow staff/admin comments.
- Show customer download/preview links where appropriate.
- Do not expose private uploads publicly.

Important security requirements:

- Validate file type and size.
- Sanitize filenames.
- Restrict executable files.
- Store uploads in protected location if possible.
- Require permission checks for downloads.
- Keep customer files tied to the correct account/order.

---

## 14. E-Commerce and Checkout Requirements

WooCommerce should handle the base e-commerce flow.

Required:

- Cart
- Checkout
- Order summary
- Shipping method selection
- Payment method selection
- Coupons/discounts
- Order confirmation
- Payment success page
- Payment failed page
- Invoice generation
- Customer account order history

Payment methods shown in mockups:

- Credit Card
- Bank Transfer

Exact payment gateway must be confirmed based on client country, bank, and hosting. Do not store raw card details in WordPress.

---

## 15. Shipping / Delivery Requirements

Required pages and features:

- Shipping Options
- Delivery Tracking
- Pickup Locations
- Delivery Policy
- Shipping details inside order tracking page
- Tracking number field
- Destination address
- Carrier name

Possible delivery methods:

- Standard Shipping
- Express Shipping
- Local Pickup

Shipping API integration can be a later phase if needed. For MVP, admin can manually enter carrier and tracking number.

---

## 16. Invoice Requirements

Invoice page should match the polished screenshot style.

Required invoice elements:

- Company logo/name/contact details
- Invoice number
- Date issued
- Due date
- Billed-to section
- Payment status badge
- Optional paid watermark
- Line items
- Quantity
- Unit price
- Total per item
- Subtotal
- Tax
- Shipping
- Grand total
- Amount paid
- Balance due
- Payment notes
- Download PDF button
- Print invoice button

---

## 17. Homepage Requirements

Homepage should include:

- Header/nav with product categories.
- Hero section with strong headline and CTA.
- Brand promise: premium/high-fidelity/commercial print quality.
- Featured products/categories.
- How it works process.
- Why choose us / quality cards.
- Trusted companies / customers section.
- Testimonials or short review snippets if client provides them.
- Quote CTA.
- Footer with support/legal/company links.

### PrintRunner reference to adapt, not copy

PrintRunner homepage/reference includes these useful patterns:

- Service links such as Design Services, Mailing Services, Free Sample Kit, Custom Quote, Free File Review, and Blog.
- Account/customer links such as Order Status, Quotes, Saved Designs, Private Gallery, Mailing Lists, and Settings.
- Featured product categories such as stickers, labels, postcards, hats, pouches, menus, window clings, business cards, notepads, envelopes, flyers, brochures, posters, hang tags, vinyl banners, booklets, and packaging materials.
- Trust/benefit blocks such as free shipping over a threshold, exceptional quality, easy online design tool, fast turnarounds, large order volume, years of experience, and great quality.
- “Customers Who Trust PrintRunner” logos include Google, Amazon, Kaiser, Square, COIT, National Notary Association, Bucks, Keller Williams, and Aramark.

For Pixel Signs & Printing, create a similar structure but use the client’s real partnerships/customers/logos only. If the client does not have permission to use specific logos, use “Trusted by local businesses” placeholder logos until approved.

Reference source: PrintRunner homepage, accessed for structure inspiration only.

---

## 18. Design System Notes

### Layout

- Desktop max width around 1180–1280px.
- Use card-based sections.
- Use clear spacing between sections.
- Keep navigation simple.
- Make product cards image-heavy.
- Use dark footer.
- Use responsive mobile-first layouts.

### Buttons

Primary button:

- Orange background.
- White text.
- Rounded corners.
- Slight icon arrow if needed.

Secondary button:

- White/transparent background.
- Border.
- Dark text.

### Cards

- White background.
- Soft border.
- Shadow.
- Rounded corners.

### Dashboard UI

- Sidebar left.
- Active item with orange/brown background.
- KPI cards.
- Tables for recent orders/activity.
- Task cards.
- Status badges.
- Search bar.

---

## 19. WordPress Implementation Direction

### Build approach

Use WordPress with:

- A custom WordPress theme for the Pixel visual design.
- WooCommerce for products, cart, checkout, orders, coupons, and customer accounts.
- A custom plugin for print-specific logic.
- Optional carefully selected plugins for forms, wishlist, invoices, file uploads, and product options if they save time.

### Recommended repo structure

```txt
pixel-signs-printing/
  README.md
  PROJECT_KNOWLEDGE.md
  wp-content/
    themes/
      pixel-signs-theme/
        style.css
        functions.php
        screenshot.png
        assets/
          css/
          js/
          images/
        template-parts/
        woocommerce/
        page-templates/
    plugins/
      pixel-core/
        pixel-core.php
        includes/
          roles.php
          cpt-quotes.php
          upload-handler.php
          order-statuses.php
          shortcodes.php
          admin-pages.php
          security.php
        assets/
          css/
          js/
  docs/
    screenshots/
    proposal-notes.md
    product-catalog-draft.md
```

### Theme responsibilities

- Public pages visual design.
- Header/footer.
- Product listing layout.
- Product details template.
- Checkout/cart styling.
- Customer portal styling.
- Invoice template styling.
- Responsive/mobile UI.

### Plugin responsibilities

- Custom roles/capabilities.
- Quote request custom post type or custom WooCommerce quote layer.
- Upload workflow.
- Custom order statuses.
- Admin dashboard widgets.
- Customer portal shortcodes/blocks.
- File access/security rules.
- Notification hooks.

---

## 20. MVP Priorities

### Priority 1 — Complete visual demo

Must visually exist and look polished:

- Home
- Products/Categories
- Product Details
- Request Quote
- File Upload
- Cart
- Checkout
- Payment Success/Failed
- Invoice
- Customer Dashboard
- Order Tracking
- Quote Details
- Upload Files by Order Number
- About
- Contact
- FAQ
- Legal pages
- Admin dashboard screens

### Priority 2 — Basic functional core

- Products in WooCommerce.
- Product options for main items.
- Cart and checkout.
- Coupons.
- Customer account login.
- Quote request form.
- File upload tied to quote/order.
- Basic order tracking statuses.
- Admin can manage orders/quotes/files.
- Invoice PDF/print.

### Priority 3 — Advanced features later

- Advanced live pricing calculators.
- Shipping carrier API integration.
- Full proof approval workflow.
- Deep analytics/reports.
- CRM automation.
- Email/SMS notification automation.
- Advanced staff task assignment.
- Real-time dashboard charts.
- Design editor/tool.

---

## 21. Out of Scope for Initial Launch

Not required now:

- Product reviews/ratings.
- Complex custom online design editor.
- Full shipping API automation.
- Complex ERP integration.
- Multi-language support.
- Native mobile app.
- Complex marketplace features.
- Advanced CRM.

---

## 22. Acceptance Criteria

The project is acceptable for first launch/demo when:

- Website is English only.
- Design matches the premium Pixel visual direction.
- Homepage looks complete and professional.
- Products page shows 10–15 launch products/categories.
- Product details page has configurable options and upload/quote CTA.
- Customer can register/login.
- Customer can request quote.
- Customer can upload artwork.
- Customer can use cart/checkout.
- Customer can track order status.
- Customer can see invoice.
- Admin can manage products/prices/content.
- Admin can manage orders/quotes/customers/files.
- Coupons/discounts exist.
- Wishlist exists.
- Site is responsive on mobile.
- No broken placeholder links in main navigation.
- Legal/support pages exist even if content is initially simple.
- Security basics are respected for uploads, payments, and customer data.

---

## 23. Open Questions / Missing Details

Need to confirm before final scope and pricing:

1. Exact brand logo files.
2. Exact brand color codes.
3. Exact brand font names/licenses.
4. Final product list for first 10–15 products.
5. Product pricing tables/options.
6. Which products need direct pricing vs custom quote only.
7. Payment gateway/country/currency.
8. Tax/VAT rules.
9. Shipping carriers and zones.
10. Pickup location details.
11. Max upload size.
12. Required file formats.
13. Email templates/notification rules.
14. Staff workflow: who reviews files, who updates statuses.
15. Whether proof approval is required at launch or later.
16. Whether customer must pay before or after proof approval.
17. Legal page content: privacy, refund, terms, delivery policy.
18. Hosting details and WordPress access.
19. Domain/DNS access.
20. Whether client has real partner/customer logos and permission to display them.

---

## 24. Client-Facing Pricing Assumptions

When preparing a quote, estimate based on these assumptions:

- WordPress site, not custom Laravel/Next.js SaaS.
- English-only.
- 10–15 products at launch.
- WooCommerce used for commerce foundation.
- Product option logic included for launch products.
- File upload and quote request included.
- Customer login/portal included.
- Admin management included.
- Full visual page coverage included.
- Advanced integrations may be phase 2.
- Final delivery in maximum 3 weeks requires client to provide branding, product list, pricing, content, domain/hosting access, and legal copy quickly.

Potential pricing factors:

- Number of product templates.
- Complexity of pricing rules.
- File upload size/storage needs.
- Payment gateway setup complexity.
- Shipping integration complexity.
- Admin dashboard depth.
- Invoice/PDF customization.
- Number of legal/content pages.
- Whether content writing is included.
- Whether product data entry is included.
- Whether maintenance/support is included.

---

## 25. AI / Developer Execution Rules

When building this project:

- Use WordPress. Do not build a standalone React/Next app unless explicitly changed.
- Prioritize visual completion and business flow over advanced backend complexity.
- Use WooCommerce for standard e-commerce features whenever possible.
- Put custom business logic in a plugin, not randomly inside theme templates.
- Keep the theme clean and maintainable.
- Do not copy PrintRunner assets or text verbatim.
- Use PrintRunner only as structure/UX inspiration.
- Use client’s real brand assets.
- English only.
- Responsive design is mandatory.
- Do not implement reviews now.
- Do not overbuild advanced analytics in phase 1; placeholder/dashboard summary is acceptable.
- Keep checkout/payment secure; do not store raw credit card data.
- Validate and protect uploads.
- Make admin-editable content/prices wherever possible.
- Before destructive changes, backup or ask approval.
- Keep all project decisions documented in this file or README.

---

## 26. Suggested Initial Repo README Summary

```md
# Pixel Signs & Printing

WordPress + WooCommerce commercial printing website with customer portal, quote requests, artwork uploads, order tracking, invoice pages, and admin/staff management.

## Stack
- WordPress
- WooCommerce
- Custom theme: pixel-signs-theme
- Custom plugin: pixel-core

## Main Features
- Product catalog
- Product configurator
- Artwork upload
- Quote requests
- Cart and checkout
- Customer portal
- Order tracking
- Invoice PDF/print
- Admin dashboard
- Coupons and wishlist

## Language
English only.

## Deadline
Professional launch/demo within 3 weeks.
```

---

## 27. Current Project Summary in One Sentence

Pixel Signs & Printing is an English WordPress/WooCommerce printing platform for 10–15 customizable print products, with quote requests, artwork upload, customer portal, order tracking, invoices, coupons, wishlist, and admin/staff management, delivered with a polished premium design within a maximum of 3 weeks.
