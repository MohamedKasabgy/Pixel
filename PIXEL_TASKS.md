# Pixel WordPress Project — Detailed Task Checklist

Project: **Pixel**  
Stack: **WordPress + WooCommerce + Custom Theme + Custom Plugin**  
Goal: Build a professional client-facing demo for a custom printing / signs business within 3 weeks.

Repository:  
`https://github.com/MohamedKasabgy/Pixel`

---

## How Everyone Should Work

Do **not** work directly on `main`.

Before starting any task:

```bash
git pull origin main
```

Create a feature branch:

```bash
git checkout -b feature/task-name
```

Before pushing:

```bash
git status
```

After finishing a task:

```bash
git add .
git commit -m "Clear commit message"
git push -u origin feature/task-name
```

Then open a Pull Request into `main`.

---

## Local Setup Checklist

Each team member must complete this first.

- [ ] Clone the repo.

```bash
git clone https://github.com/MohamedKasabgy/Pixel.git
cd Pixel
```

- [ ] Install and open LocalWP.
- [ ] Create a new WordPress site named `Pixel`.
- [ ] Copy the theme into the LocalWP WordPress site.

```bash
rsync -av wp-content/themes/pixel-signs-theme "$HOME/Local Sites/pixel/app/public/wp-content/themes/"
```

- [ ] Copy the plugin into the LocalWP WordPress site.

```bash
rsync -av wp-content/plugins/pixel-core "$HOME/Local Sites/pixel/app/public/wp-content/plugins/"
```

- [ ] Activate the theme:
  - Appearance > Themes > Activate **Pixel Signs Theme**
- [ ] Activate the plugin:
  - Plugins > Activate **Pixel Core**
- [ ] Install and activate WooCommerce.
- [ ] Create the required pages using LocalWP > Site Shell.

```bash
wp post create --post_type=page --post_title="Products" --post_name="products" --post_status=publish --page_template="page-templates/products.php"

wp post create --post_type=page --post_title="Request Quote" --post_name="request-quote" --post_status=publish --page_template="page-templates/request-quote.php"

wp post create --post_type=page --post_title="Upload Artwork" --post_name="upload-artwork" --post_status=publish --page_template="page-templates/upload-artwork.php"

wp post create --post_type=page --post_title="Client Dashboard" --post_name="client-dashboard" --post_status=publish --page_template="page-templates/client-dashboard.php"

wp post create --post_type=page --post_title="Order Tracking" --post_name="order-tracking" --post_status=publish --page_template="page-templates/order-tracking.php"
```

- [ ] Confirm these pages work:
  - `/products`
  - `/request-quote`
  - `/upload-artwork`
  - `/client-dashboard`
  - `/order-tracking`

---

# Mohamed — Products + WooCommerce + Project Lead

Branch:

```bash
git checkout -b feature/products-woocommerce
```

## 1. Products Page Filtering

Main file:

```text
wp-content/themes/pixel-signs-theme/page-templates/products.php
```

Checklist:

- [x] Open `products.php`.
- [x] Read the `category` query value from the URL using `$_GET['category']`.
- [x] If no category is provided, show all products or featured products.
- [x] Create a category data structure in the code for:
  - [x] `large-format`
  - [x] `marketing`
  - [x] `signage`
  - [x] `apparel`
  - [x] `business-cards`
- [x] For each category, define:
  - [x] Page title
  - [x] Page description
  - [x] Product list or product filtering logic
- [x] Make `/products/?category=large-format` show Large Format content.
- [x] Make `/products/?category=marketing` show Marketing content.
- [x] Make `/products/?category=signage` show Signage content.
- [x] Make `/products/?category=apparel` show Apparel content.
- [x] Make `/products/?category=business-cards` show Business Cards content.
- [x] Make sure the product cards change based on the selected category.
- [x] Test every header category link.
- [x] Make sure an unknown category does not break the page.

Acceptance criteria:

- [x] The URL changes correctly.
- [x] The page title changes correctly.
- [x] The page description changes correctly.
- [x] The displayed products change correctly.
- [x] Unknown categories fallback safely.

---

## 2. Sample Products

Create 10–15 demo products.

Checklist:

- [x] Vinyl Banners
- [x] Business Cards
- [x] Flyers
- [x] Brochures
- [x] Posters
- [x] Stickers
- [x] Yard Signs
- [x] Foam Boards
- [x] Roll Up Banners
- [x] T-Shirts
- [x] Packaging Boxes
- [x] Labels
- [x] Menus
- [x] Postcards
- [x] Custom Quote Product

Each product should include:

- [x] Product name
- [x] Category
- [x] Short description
- [x] Starting price or “Request Quote”
- [x] Image or visual placeholder
- [x] View Details button
- [x] Request Quote button
- [x] Upload Artwork button

Acceptance criteria:

- [x] Each category has at least 2 products.
- [x] Products are understandable to the client.
- [x] Buttons go to the correct pages.

Known follow-up: product card action buttons are functional but need visual styling during UI polish.

---

## 3. Product Details Page

Main file:

```text
wp-content/themes/pixel-signs-theme/single-product.php
```

Checklist:

- [x] Improve the product details layout.
- [x] Show product image.
- [x] Show product title.
- [x] Show short description.
- [x] Add visible product options:
  - [x] Size
  - [x] Material
  - [x] Finish
  - [x] Quantity
  - [x] Turnaround Time
- [x] Add Add to Cart button.
- [x] Add Request Quote button.
- [x] Add Upload Artwork button.
- [x] Test desktop layout.
- [x] Test mobile layout.

Acceptance criteria:

- [x] The product page is clear.
- [x] Product options are visible.
- [x] Buttons work or go to the right pages.

Known follow-up: product details page is functional, but final visual polish can be improved later during UI polish.


---

## 4. WooCommerce Basic Setup

Checklist:

- [x] Open WooCommerce inside WordPress admin.
- [x] Create WooCommerce product categories:
  - [x] Large Format
  - [x] Marketing
  - [x] Signage
  - [x] Apparel
  - [x] Business Cards
- [x] Create demo WooCommerce products.
- [x] Assign each product to the correct category.
- [x] Confirm Cart opens.
- [x] Confirm Checkout opens.
- [x] Confirm Add to Cart works for at least one product.

Acceptance criteria:

- [x] WooCommerce products exist.
- [x] WooCommerce categories exist.
- [x] Cart and Checkout pages work.

Known follow-up:
- WooCommerce demo products and categories were created in the LocalWP database for the demo. They are not stored in Git unless we later add an import/seed file.

---

## 5. Mohamed Final Checks

Final checklist:

- [x] Run `git status`.
- [x] Confirm final work is merged into `main`.
- [x] Run PHP syntax check from LocalWP Site Shell.
- [x] Test Home.
- [x] Test Products.
- [x] Test every category link.
- [x] Test Product Details.
- [x] Confirm Cart opens.
- [x] Confirm Checkout opens.
- [x] Confirm Add to Cart works.
- [x] Confirm Request Quote links work.
- [x] Confirm Upload Artwork links work.
- [x] Commit with a clear message.
- [x] Push final work to `main`.

Final check note:
- Mohamed final checks completed.
- Git is clean on `main`.
- Product, cart, checkout, quote, and upload demo flow were reviewed locally.
- PHP lint was run from LocalWP Site Shell because PHP CLI is not available in the normal macOS shell.
- Mohamed’s section is ready for team handoff.

---

# Abdullah — Homepage + UI + Content Pages

Branch:

```bash
git checkout -b feature/homepage-ui-content
```

Final check note:
- Mohamed final checks completed.
- Git is clean on `main`.
- Product, cart, checkout, quote, and upload demo flow were reviewed locally.
- PHP lint was run from LocalWP Site Shell because PHP CLI is not available in the normal macOS shell.

## 1. Homepage Hero

Main files:

```text
wp-content/themes/pixel-signs-theme/front-page.php
wp-content/themes/pixel-signs-theme/assets/css/main.css
```

Checklist:
// NOTE: follow-up: checkbox isn't working, but the links works fine.
- Category checkbox inputs are currently visual only.
- Filtering works through the category links.
- Checkbox click behavior can be improved later during UI polish.

- [x] Open `front-page.php`.
- [x] Improve the first homepage section.
- [x] Add a clear hero headline.
- [x] Add a short hero description.
- [x] Add Request a Quote button.
- [x] Add Browse Products button.
- [x] Add Upload Artwork button.
- [x] Replace the image placeholder with a real image or better visual block.
- [x] Make sure all CTA buttons go to the correct pages.

Suggested headlines:

```text
Custom Printing Made Simple
Signs, Marketing Materials & Custom Prints
High-Quality Printing for Growing Businesses
```

Acceptance criteria:

- [x] The homepage gives a professional first impression.
- [x] The client understands the business from the first section.
- [x] CTA buttons are clear and working.

---

## 2. Product Categories Section

Checklist:

- [x] Add or improve the homepage categories section.
- [x] Add Large Format card.
- [x] Add Marketing card.
- [x] Add Signage card.
- [x] Add Apparel card.
- [x] Add Business Cards card.
- [x] Add Packaging card if suitable.
- [x] Each card includes:
  - [x] Image or clean placeholder
  - [x] Title
  - [x] Short description
  - [x] Link to the correct category

Acceptance criteria:

- [x] Every card links to the correct category.
- [x] Cards look consistent.
- [x] The section is clear to the client.

---

## 3. Partners / Brands Section

Checklist:

- [x] Add a partners or brands section to the homepage.
- [x] Add a heading such as `Trusted by growing businesses`.
- [x] Add 5–8 logo placeholders.
- [x] Make the logos look organized and professional.
- [x] Make the section responsive.

Acceptance criteria:

- [x] The section builds trust.
- [x] The design is clean and professional.

---

## 4. Why Choose Us Section

Checklist:

- [x] Add a Why Choose Us section.
- [x] Add Fast turnaround.
- [x] Add Custom quotes.
- [x] Add Free file review.
- [x] Add Premium print quality.
- [x] Add Local delivery / pickup.
- [x] Add Bulk order support.
- [x] Each feature has a title and short description.

Acceptance criteria:

- [x] The section explains why clients should choose the company.
- [x] The section works on mobile.

---

## 5. Replace Visual Placeholders

Checklist:

- [x] Search for placeholder text like `Industrial press image`.
- [x] Replace placeholders with images or better visual blocks.
- [x] Make sure images do not break the layout.
- [x] Use images related to printing, signs, production, packaging, or marketing materials.
- [x] Avoid low-quality random images.

Acceptance criteria:

- [x] The homepage does not show ugly gray placeholder boxes.
- [x] Visuals look demo-ready.

---

## 6. Header + Footer Polish

Checklist:

- [x] Improve the header if needed.
- [x] Make sure header links are clear.
- [x] Make sure the header works on mobile.
- [x] Improve the footer.
- [x] Add footer links:
  - [x] Products
  - [x] Request Quote
  - [x] Upload Artwork
  - [x] Contact
  - [x] FAQ
  - [x] Privacy Policy
  - [x] Terms
- [x] Add placeholder contact information.

Acceptance criteria:

- [x] Header and footer are consistent.
- [x] Main links exist.
- [x] Mobile layout does not break.

---

## 7. Content Pages

Create or improve these pages:

- [x] About
- [x] Contact
- [x] FAQ
- [x] Privacy Policy
- [x] Terms & Conditions
- [x] Refund Policy
- [x] Shipping / Delivery Policy
- [x] Pickup Locations

Each page should include:

- [x] Clear title
- [x] Professional starter content
- [x] Simple consistent layout
- [x] Footer link if important

Contact page should include:

- [x] Contact form
- [x] Email
- [x] Phone
- [x] Location
- [x] Working hours
- [x] Map placeholder

FAQ page should include:

- [x] How do I request a quote?
- [x] Can I upload my own design?
- [x] What file types do you accept?
- [x] How long does printing take?
- [x] Can I track my order?
- [x] Do you offer delivery?
- [x] Can I reorder previous prints?

Acceptance criteria:

- [x] Pages exist.
- [x] Pages are not empty.
- [x] Pages look suitable for a demo.

---

## 8. Responsive Design

Checklist:

- [x] Test homepage on desktop. *(Playwright 1280x900 — pass.)*
- [x] Test homepage on tablet. *(Playwright 768x1024 — pass.)*
- [x] Test homepage on mobile. *(Playwright 375x812 — pass.)*
- [x] Make cards stack properly on mobile.
- [x] Make sure text does not overflow.
- [x] Make buttons easy to tap.
- [x] Make sure the header does not cover content.

Acceptance criteria:

- [x] The site looks acceptable on mobile.
- [x] There is no obvious horizontal overflow.
- [x] Buttons and links are usable.

Responsive QA result (Playwright, 2026-06-22): Home, About, Contact, and FAQ pass at desktop (1280x900), tablet (768x1024), and mobile (375x812) — `scrollWidth - innerWidth <= 0` on every page/viewport, with no card/text/footer clipping. The mobile nav opens/closes and exposes Login, Cart, and Request Quote; the partners grid is 4-up/3-up/2-up. Legal/support pages (`/privacy/`, `/terms/`, `/refund-policy/`, `/shipping-policy/`, `/pickup-locations/`) render and are not 404. The eight Abdullah static pages were created in the LocalWP database (IDs 15-22) with their templates assigned — a DB-only change; no repo code was modified. Screenshots saved under `.playwright-mcp/` remain untracked. Optional cosmetic note (not a blocker): the Home hero `.quality-badge` overhangs the mobile viewport left edge by ~8px without causing horizontal scroll. Legal/contact placeholder copy and real image/logo replacement remain launch follow-ups; the Packaging card stays linked to `/products/` until Mohamed adds the real filter.

---

## 9. Abdullah Final Checks

Before opening a Pull Request:

- [x] Run `git status`.
- [x] Confirm you are not on `main`. *(Verified on `feature/abdullah-responsive-qa`.)*
- [x] Run PHP syntax check. *(LocalWP PHP lint passed for 23 files.)*

```bash
find wp-content -name "*.php" -print0 | xargs -0 -n1 php -l
```

- [x] Test Home.
- [x] Test About.
- [x] Test Contact.
- [x] Test FAQ.
- [x] Test mobile view. *(Playwright responsive QA 2026-06-22 — pass.)*
- [x] Commit with a clear message. *(928aad0 `docs: record Abdullah responsive QA completion`.)*
- [x] Push the branch. *(`feature/abdullah-responsive-qa` pushed to origin.)*
- [x] Open a Pull Request. *(Opened manually on GitHub because GitHub CLI was not installed.)*

---

# Ahmed — Quote + Upload + Client Portal

Branch:

```bash
git checkout -b feature/quote-upload-portal
```

Implementation status as of June 22, 2026:

- The assigned quote, upload, artwork admin, client portal, and tracking features are implemented on `feature/quote-upload-portal`.
- PHP/JavaScript syntax and repository checks pass.
- LocalWP runtime/browser verification, branch push, and Pull Request are still required before the Definition of Done checkboxes can be marked complete.
- See `docs/AHMED_TASK_STATUS.md` for the detailed implementation and verification record.

Implementation update as of June 24, 2026:

- Commercial header, mega menus, homepage platform sections, footer, richer quote form fields, upload artwork page polish, client portal tabs, demo order tracking, expanded catalog categories, and product detail polish were implemented in code.
- PHP lint passed for all theme/plugin PHP files using the LocalWP PHP binary, and `git diff --check` passed.
- `pixel.local` was not running during this pass, so browser/runtime QA and form submission testing are still required before checking off the remaining Ahmed or Shared QA items.

## 1. Request Quote Page

Main file:

```text
wp-content/themes/pixel-signs-theme/page-templates/request-quote.php
```

Checklist:

- [ ] Open the Request Quote page.
- [ ] Improve the form layout.
- [ ] Add Full name field.
- [ ] Add Email field.
- [ ] Add Phone field.
- [ ] Add Company name field.
- [ ] Add Product type field.
- [ ] Add Quantity field.
- [ ] Add Size field.
- [ ] Add Material field.
- [ ] Add Finish field.
- [ ] Add Deadline field.
- [ ] Add Delivery method field.
- [ ] Add Notes field.
- [ ] Add optional File upload field if suitable.
- [ ] Add clear submit button.

Acceptance criteria:

- [ ] The form is clear.
- [ ] Important fields exist.
- [ ] The page looks suitable for the client demo.

---

## 2. Save Quote Requests

Expected file:

```text
wp-content/plugins/pixel-core/includes/class-pixel-core.php
```

Checklist:

- [ ] Confirm the form submits data.
- [ ] Confirm data is saved in WordPress.
- [ ] Confirm Quote Requests appear in WordPress admin.
- [ ] Each quote request should show:
  - [ ] Customer name
  - [ ] Email
  - [ ] Phone
  - [ ] Product type
  - [ ] Quantity
  - [ ] Status
  - [ ] Date
  - [ ] Notes
- [ ] Add success message after submission.
- [ ] Add error message if submission fails.

Acceptance criteria:

- [ ] A new quote request can be submitted.
- [ ] The quote request appears in admin.
- [ ] The user sees a success message.

---

## 3. Quote Status

Checklist:

- [ ] Add status to quote requests.
- [ ] Required statuses:
  - [ ] New
  - [ ] Under Review
  - [ ] Quoted
  - [ ] Approved
  - [ ] Rejected
  - [ ] Converted to Order
- [ ] Make sure the status appears in admin.
- [ ] If simple, allow admin to change status.

Acceptance criteria:

- [ ] Every quote has a clear status.
- [ ] Status is visible in admin.

---

## 4. Upload Artwork Page

Main file:

```text
wp-content/themes/pixel-signs-theme/page-templates/upload-artwork.php
```

Checklist:

- [ ] Improve Upload Artwork page layout.
- [ ] Add Name field.
- [ ] Add Email field.
- [ ] Add Order number field.
- [ ] Add Product / project name field.
- [ ] Add Notes field.
- [ ] Add File upload field.
- [ ] Add clear submit button.

Allowed file types:

- [ ] PDF
- [ ] PNG
- [ ] JPG
- [ ] JPEG
- [ ] AI
- [ ] PSD
- [ ] EPS
- [ ] ZIP

Acceptance criteria:

- [ ] The form is clear.
- [ ] The user can upload a file.
- [ ] The user sees a success message after upload.

---

## 5. Artwork Admin

Checklist:

- [ ] Confirm uploaded files appear under Artwork Files.
- [ ] Each artwork file should show:
  - [ ] Name
  - [ ] Email
  - [ ] Order number
  - [ ] Project name
  - [ ] File link
  - [ ] Status
  - [ ] Date
- [ ] Add artwork review statuses:
  - [ ] Uploaded
  - [ ] Under Review
  - [ ] Approved
  - [ ] Needs Revision
  - [ ] Rejected

Acceptance criteria:

- [ ] Uploaded files appear in WordPress admin.
- [ ] Each uploaded file has a link.
- [ ] Each uploaded file has a clear status.

---

## 6. Client Dashboard

Main file:

```text
wp-content/themes/pixel-signs-theme/page-templates/client-dashboard.php
```

Checklist:

- [ ] Improve Client Dashboard layout.
- [ ] Add Welcome card.
- [ ] Add Active orders section.
- [ ] Add Recent quotes section.
- [ ] Add Uploaded files section.
- [ ] Add Request Quote button.
- [ ] Add Upload Artwork button.
- [ ] Add Track Order button.
- [ ] Use demo data if full data connection is not ready.

Acceptance criteria:

- [ ] The page clearly explains the client portal idea.
- [ ] The page has convincing demo data.
- [ ] Buttons go to the correct pages.

---

## 7. Order Tracking

Main file:

```text
wp-content/themes/pixel-signs-theme/page-templates/order-tracking.php
```

Checklist:

- [ ] Improve Order Tracking page layout.
- [ ] Add search input for order number.
- [ ] Add search button.
- [ ] Add demo order result.
- [ ] Add order timeline.
- [ ] Add statuses:
  - [ ] Quote Requested
  - [ ] Under Review
  - [ ] Approved
  - [ ] In Production
  - [ ] Quality Check
  - [ ] Ready for Pickup
  - [ ] Out for Delivery
  - [ ] Completed
- [ ] Make the page clear for users.

Acceptance criteria:

- [ ] The customer can understand the order status.
- [ ] Timeline is clear.
- [ ] Search looks functional, even if demo-based.

---

## 8. Ahmed Final Checks

Before opening a Pull Request:

- [ ] Run `git status`.
- [ ] Confirm you are not on `main`.
- [ ] Run PHP syntax check.

```bash
find wp-content -name "*.php" -print0 | xargs -0 -n1 php -l
```

- [ ] Test Request Quote.
- [ ] Test Upload Artwork.
- [ ] Test Client Dashboard.
- [ ] Test Order Tracking.
- [ ] Confirm admin pages open.
- [ ] Commit with a clear message.
- [ ] Push the branch.
- [ ] Open a Pull Request.

---

# Shared QA Checklist

After all work is merged:

- [ ] Home opens.
- [ ] Products opens.
- [ ] Product category links work.
- [ ] Product Details opens.
- [ ] Request Quote opens.
- [ ] Quote request saves.
- [ ] Upload Artwork opens.
- [ ] File upload works.
- [ ] Client Dashboard opens.
- [ ] Order Tracking opens.
- [ ] Cart opens.
- [ ] Checkout opens.
- [ ] About opens.
- [ ] Contact opens.
- [ ] FAQ opens.
- [ ] Policy pages open.
- [ ] Site is acceptable on mobile.
- [ ] No PHP syntax errors.
- [ ] No obvious broken layout.
- [ ] Every Pull Request has a clear description.

---

# Priority Order

Start in this order:

1. Mohamed:
   - Products filtering

2. Abdullah:
   - Homepage hero + visual placeholders

3. Ahmed:
   - Request Quote form

Then:

4. Mohamed:
   - Sample products + product details

5. Abdullah:
   - Content pages + responsive design

6. Ahmed:
   - Upload artwork + client dashboard + order tracking

---

# Definition of Done

A checkbox can only be marked as done when:

- [ ] It has been implemented.
- [ ] It has been tested locally.
- [ ] It did not break another page.
- [ ] It has a clear commit.
- [ ] It is pushed to a Pull Request.
