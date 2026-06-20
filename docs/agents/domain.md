# Domain Docs

Pixel is a single-context WordPress/WooCommerce commercial printing project.

## Source Of Truth

Read these before planning architecture, UI scope, product flows, or backend workflow changes:

1. `PROJECT_KNOWLEDGE.md`
2. `AGENTS.md`
3. `PIXEL_TASKS.md`
4. `docs/BUILD_PLAN.md`
5. `README.md`

## Domain Vocabulary

Use the project's own terms:

- Pixel Signs & Printing
- commercial printing
- WooCommerce product
- print product configurator
- quote request
- artwork upload
- client portal
- order tracking
- invoice
- staff/admin workflow
- `pixel-signs-theme`
- `pixel-core`

Avoid drifting into unrelated SaaS, marketplace, or custom app language. This is a WordPress + WooCommerce MVP, not a standalone React/Next/Laravel app.

## Architecture Decisions

There is no `docs/adr/` folder yet. If future decisions become important, add ADRs under:

```text
docs/adr/
```

Until ADRs exist, `PROJECT_KNOWLEDGE.md` is the decision record.

## Consumer Rules For Agents

- Read `PROJECT_KNOWLEDGE.md` before architecture decisions.
- Keep frontend English-only.
- Use WooCommerce for products, cart, checkout, customers, coupons, and base orders.
- Keep print-specific workflow logic in `wp-content/plugins/pixel-core`.
- Keep visual UI, page templates, and WooCommerce presentation in `wp-content/themes/pixel-signs-theme`.
- Prioritize visual completeness and client demo quality before advanced integrations.
- Treat advanced integrations as phase 2 unless the current task explicitly requires them.
