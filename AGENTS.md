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
- For agent workflow setup, read the `## Agent skills` section below and the files under `docs/agents/`.
- Prioritize visual completeness and client demo quality first.
- Advanced integrations can be placeholders if they block MVP speed.
- Do not store payment card data; rely on WooCommerce payment gateways.
- Uploaded files must be validated and restricted to safe artwork file types.
- Use WordPress nonces and capability checks for forms/admin actions.
- Keep code easy to hand off to another WordPress developer.
- Keep docs up to date when changes affect setup, scope, workflows, page maps, QA, or team handoff. If a doc update would help the team, make it as part of the same task.

## MVP acceptance

- Home, product listing, product detail/configuration, quote request, artwork upload, cart, checkout, invoice, client dashboard, order tracking, static policy/support pages, and admin/staff flow must visually exist.
- Customer can request quote and upload artwork.
- Customer can place a WooCommerce order.
- Customer can see order status from portal/tracking screen.

## Agent skills

### Issue tracker

Issues and PRDs are tracked in GitHub Issues for `MohamedKasabgy/Pixel`. See `docs/agents/issue-tracker.md`.

### Triage labels

Use the default five-label triage vocabulary: `needs-triage`, `needs-info`, `ready-for-agent`, `ready-for-human`, and `wontfix`. See `docs/agents/triage-labels.md`.

### Domain docs

This is a single-context repo. `PROJECT_KNOWLEDGE.md` is the source of truth for domain language, scope, visual direction, and MVP priorities. See `docs/agents/domain.md`.

### Recommended skills for this repo

Use the Pixel skill guide before planning or implementation work. See `docs/agents/recommended-skills.md`. Team members can install the recommended/user skills from `docs/agents/install-skills.md`.
