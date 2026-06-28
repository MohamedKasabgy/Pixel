# Manual QA Findings

Collected from manual testing on `pixel.local`.

## QA-001: Product Cards Show "Request Quote" As Broken Pricing Text

**Page:** `/products/`  
**Severity:** P2  
**Area:** Product listing cards

### Problem

For quote-only products, the pricing area renders `Request Quote` as large orange text inside the price row. It looks like a broken price value and competes visually with the actual `Request Quote` CTA button below.

### Proposed Solution

Update the product card pricing display logic so products without numeric prices render a compact, intentional label such as `Custom quote`, `Quote required`, or `Pricing by quote`. Style this as a small badge or muted price-row label, not as oversized heading text.

## QA-002: Product Filter Checkbox Layout And Click Target Are Wrong

**Page:** `/products/`  
**Severity:** P2  
**Area:** Product category filters

### Problem

The filter checkbox appears above or offset from the category label. The selected checkbox can visually float above the wrong text, making the filter feel broken. Clicking the checkbox itself does not reliably apply the filter; only clicking the label works.

### Proposed Solution

Restructure each filter item so the input and label are correctly associated with `id` and `for`, or wrap the input and text in one clickable `<label>`. Adjust CSS so the checkbox sits directly under or beside its matching category label in a consistent layout. Both the checkbox and label should activate the filter.

## QA-003: Product Card Content And Actions Are Vertically Inconsistent

**Page:** `/products/`  
**Severity:** P2  
**Area:** Product listing cards

### Problem

The pricing rows and action buttons start at different vertical positions across product cards. Cards with longer titles or descriptions push actions lower, so the product grid looks uneven and less polished.

### Proposed Solution

Use a consistent card layout with CSS grid or flex. Reserve fixed space for the image area, category label, title, and description. Align price and action sections using grid rows or `margin-top: auto`. Clamp descriptions to a consistent number of lines if needed, and keep cards equal height within each row.

## QA-004: Empty Cart Page Is Cramped And Visually Unfinished

**Page:** `/cart/`  
**Severity:** P2  
**Area:** Cart empty state

### Problem

The cart title, empty-cart notice, and `Return to shop` button are crowded together inside a narrow bordered area. There is not enough vertical or horizontal whitespace, and the empty state feels unfinished.

### Proposed Solution

Restyle the WooCommerce empty cart state as a dedicated empty-cart panel with generous padding, clear spacing between the heading/message/action, and a stronger CTA. Add more breathing room before the newsletter/footer section.

## QA-005: Order Tracking And Active Orders Are Disconnected From Customer Data

**Page:** `/order-tracking/` and client portal active order flow  
**Severity:** P1  
**Area:** Customer portal, active orders, order tracking

### Problem

The dashboard shows an active order for the logged-in user, but the Active Orders / Order Tracking page does not show that same order. Instead, the page displays hardcoded demo content such as `Demo Order #PX-9021`, with items and files that are not from the current customer.

This makes the portal feel disconnected and creates a possible privacy/security concern because customers appear to see order/file details that are not theirs, even if currently placeholder data.

### Proposed Solution

Replace hardcoded order tracking/demo data with current-user WooCommerce/customer data. Active Orders, Dashboard, and Order Tracking should share the same source of truth. If no customer order is available, show an empty state or ask for an order number. Demo preview content should only appear in a clearly labeled admin/demo mode, not inside a real customer account flow.

## QA-006: Product Cards Lack A Primary Add To Cart CTA

**Page:** `/products/`  
**Severity:** P1/P2  
**Area:** Product listing cards

### Problem

Product cards only show `View Details`, `Request Quote`, and `Upload Artwork`. There is no direct `Add to Cart` CTA, even for products with visible starting prices like Brochures.

### Proposed Solution

Add a clear purchase path for standard priced products. Use `Add to Cart` for simple purchasable products or `Configure & Add to Cart` for configurable print products that require options before purchase. Keep `Request Quote` for custom or quote-only products.

## QA-007: "Upload Artwork" And "Request Quote" Purpose Is Unclear

**Page:** `/products/`  
**Severity:** P2  
**Area:** Product card CTAs and customer flow

### Problem

Product cards show both `Request Quote` and `Upload Artwork`, but it is not clear when a customer should choose each. The two actions feel like competing paths rather than distinct steps in the print-order workflow.

### Proposed Solution

Clarify the CTA hierarchy and labels. A suggested pattern:

- Primary: `Configure & Add to Cart`
- Secondary: `Request Quote`
- Tertiary/link: `Upload artwork for existing order`

Consider moving `Upload Artwork` out of every product card and placing it in the portal, header, order detail, or checkout flow where its purpose is clearer.

## QA-008: WooCommerce Browse Products Sends Users To A Different Catalog

**Page:** `/my-account/orders/` -> `Browse products`  
**Destination:** `/shop/`  
**Severity:** P1  
**Area:** WooCommerce account empty-orders state / catalog routing

### Problem

When a customer has no orders, the WooCommerce `Browse products` button sends them to `/shop/`. That page uses the default WooCommerce shop layout, placeholder product images, and a different UI from the polished Pixel `/products/` catalog used by the landing page and header.

### Proposed Solution

Override the WooCommerce empty-orders `Browse products` URL to point to `/products/`, or configure the WooCommerce shop page to use the Pixel products template. If `/products/` is the intended canonical catalog, consider redirecting `/shop/` to `/products/`.

## QA-009: Mega-Menu Category Links Lead To Empty Pages

**Page/Area:** Header mega menu / category dropdown  
**Severity:** P2, P1 if widespread  
**Area:** Navigation

### Problem

Some mega-menu links lead to empty pages. Customers can click a promoted product/category link expecting products, but land on a page with no useful content.

### Proposed Solution

Audit all header mega-menu links. Each visible link should either point to a populated product/category page, apply the correct filter on `/products/`, or be hidden until content exists. For MVP, route category links to `/products/?category=<slug>` if the custom products page is the intended catalog experience.

## QA-010: Account Addresses Page Layout Is Awkward And Unpolished

**Page:** `/my-account/edit-address/`  
**Severity:** P2  
**Area:** WooCommerce account addresses UI

### Problem

Billing and Shipping address cards are placed at odd, uneven positions inside a large bordered container. The shipping card sits lower/left while billing sits upper/right, leaving strange empty space. Buttons are cramped and wrap awkwardly, such as `Add Billing address` and `Add Shipping address`.

### Proposed Solution

Override the WooCommerce address layout styles for the Pixel account area. Use a responsive two-column grid on desktop and a single-column stack on mobile. Give both cards equal width, equal visual weight, consistent padding, and button text that fits cleanly, such as `Add billing address`.

