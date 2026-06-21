# Ahmed Task Status

Last updated: June 22, 2026

Tracks Ahmed's assigned scope:

- Quote request workflow
- Quote status administration
- Artwork upload and file security
- Artwork Files administration
- Client dashboard
- Order tracking

## Status Legend

- `[x] Implemented` means the feature is complete in the repository.
- `[x] Static QA` means PHP/JavaScript syntax and repository checks passed.
- `[ ] Runtime QA` means the feature still needs an end-to-end LocalWP browser test with WordPress, WooCommerce, and the database running.

## Completion Snapshot

| Area | Implemented | Static QA | Runtime QA | Notes |
| --- | --- | --- | --- | --- |
| Quote request form | [x] | [x] | [ ] | Complete fields, validation, product prefilling, persistence, success/error messages, and admin columns. |
| Quote statuses | [x] | [x] | [ ] | Controlled statuses, admin dropdown, list display, and status filter. |
| Artwork upload | [x] | [x] | [ ] | Required metadata, MIME/extension/size validation, ZIP inspection, and real upload errors. |
| Artwork admin | [x] | [x] | [ ] | Metadata, review statuses, admin columns, filters, and permission-checked downloads. |
| Private artwork storage | [x] | [x] | [ ] | Files move outside the public WordPress directory and are served through an authorized download action. |
| Client dashboard | [x] | [x] | [ ] | Logged-in customers see their WooCommerce orders, quotes, files, metrics, and recent activity. |
| Order tracking | [x] | [x] | [ ] | Order/email verification, production timeline, items, files, shipping details, and privacy-safe errors. |

## Implementation Commits

1. `00e9f3c` — `add/ complete quote request workflow`
2. `b46053f` — `add/ quote status administration`
3. `3057449` — `fix/ secure artwork upload workflow`
4. `5371edb` — `add/ artwork administration and private files`
5. `faf633b` — `add/ customer portal activity data`
6. `d8b7740` — `add/ secure WooCommerce order tracking`

## Quote Workflow

Implemented:

- Full name, email, phone, company, product, quantity, size, material, finish, deadline, delivery method, notes, and optional reference file.
- Product selection prefills from product-page links.
- Required fields and email are validated server-side.
- Quote details are stored as WordPress metadata.
- Quote Requests admin list shows customer, product, quantity, status, and date.
- Quote edit screen includes all saved information.

Statuses:

- New
- Under Review
- Quoted
- Approved
- Rejected
- Converted to Order

## Artwork Workflow

Implemented:

- Customer name, email, order/quote number, project name, notes, and required file.
- Allowed formats: PDF, PNG, JPG/JPEG, TIFF, AI, PSD, EPS, and ZIP.
- Server-side upload-size, extension, format, and upload-error validation.
- ZIP files are checked for unsafe paths, executable/web files, excessive entry counts, and excessive expanded size.
- Failed uploads do not leave false-success artwork records.
- Files are moved to `pixel-private-uploads` outside the public WordPress directory.
- Download links require a nonce and an authorized staff/customer account.

Artwork statuses:

- Uploaded
- Under Review
- Approved
- Needs Revision
- Rejected

## Client Portal

Logged-in customers see:

- Active quote count.
- Active WooCommerce order count.
- Uploaded artwork count.
- Combined recent activity from orders, quotes, and files.
- Order-tracking links.
- Authorized artwork download links.
- Empty states when no customer activity exists.

Logged-out visitors see a clearly labeled portal preview and login link.

## Order Tracking

Implemented:

- Order number and billing email form with nonce protection.
- Logged-in customers can access their own orders without re-entering email.
- Guests must match the order's billing email.
- Generic failure messages avoid revealing whether an order exists.
- Timeline maps WooCommerce and Pixel production statuses.
- Order items, total, associated artwork, delivery address, shipping method, provider, and tracking number display when available.

Pixel production statuses include:

- Artwork Review
- Proof Needed
- In Production
- Quality Check
- Ready for Pickup
- Shipped
- Out for Delivery
- Delivered

## Verification Completed

- All PHP files pass PHP 8.2 syntax checks.
- Both JavaScript files pass `node --check`.
- `git diff --check` passes.
- LocalWP theme and plugin files were synchronized with this branch.
- WordPress API signatures used by private uploads were checked against the local WordPress installation.

## Runtime Verification Still Required

LocalWP was not running during final QA, so the database-backed checks remain:

1. Start the `Pixel` site in LocalWP.
2. Submit a valid quote and confirm it appears under **Quote Requests**.
3. Change each quote status and test the admin status filter.
4. Upload one allowed file and confirm it appears under **Artwork Files**.
5. Try a blocked extension, oversized file, invalid ZIP, and interrupted/empty upload.
6. Confirm the artwork file is absent from public `wp-content/uploads`.
7. Confirm staff can download the file and unrelated customers cannot.
8. Log in as a customer and verify portal metrics and activity.
9. Track an order with the correct email, an incorrect email, and a different logged-in customer.
10. Test quote, upload, portal, and tracking pages at desktop, tablet, and mobile widths.

## Branch State

Branch: `feature/quote-upload-portal`

The implementation is committed locally. Push and Pull Request steps remain intentionally unchecked until Ahmed or the project lead publishes the branch.
