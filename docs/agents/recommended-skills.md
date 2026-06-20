# Recommended Agent Skills For Pixel

This guide records the skills that are most useful for this repo and the work patterns Abdullah has used before.

For team setup, see `docs/agents/install-skills.md`.

## Always Start Here

- `using-superpowers` - Check relevant skills before acting.
- `zoom-out` - Build a repo map before planning unfamiliar work.

## Planning And Task Breakdown

- `brainstorming` - Use before creative/UI/feature work so the design is clear before implementation.
- `writing-plans` - Turn approved requirements into a task-by-task implementation plan.
- `to-issues` - Convert a plan into GitHub Issues.
- `to-prd` - Turn a larger project direction into a PRD issue.
- `triage` - Move incoming work through `needs-triage`, `needs-info`, `ready-for-agent`, `ready-for-human`, or `wontfix`.

## Implementation Workflow

- `using-git-worktrees` - Use before larger feature work if the current workspace is busy.
- `test-driven-development` or `tdd` - Use for risky logic changes, bug fixes, upload/security handling, and WooCommerce/order workflow behavior.
- `subagent-driven-development` - Use when executing a detailed plan with independent tasks.
- `executing-plans` - Use when implementing a written plan inline.
- `finishing-a-development-branch` - Use once implementation and verification are done.

## Debugging And Verification

- `diagnose` or `systematic-debugging` - Use for broken pages, form failures, upload issues, WooCommerce errors, or LocalWP problems.
- `verification-before-completion` - Use before claiming work is finished.
- `requesting-code-review` - Use after meaningful changes, especially plugin/security/workflow changes.
- `receiving-code-review` - Use before applying review feedback.

## UI And Demo Quality

- `emil-design-eng` - Use for UI polish, interaction details, layout quality, and demo-ready surfaces.
- `browser:control-in-app-browser` - Use to open and visually verify LocalWP pages, responsive views, and frontend behavior.
- `prototype` - Use when trying several UI directions before committing to one.

## WordPress/WooCommerce-Specific Guidance

No dedicated WordPress skill is currently installed, so agents should follow `PROJECT_KNOWLEDGE.md` and the repo structure:

- Theme work belongs in `wp-content/themes/pixel-signs-theme`.
- Plugin/business workflow work belongs in `wp-content/plugins/pixel-core`.
- Use WordPress nonces and capability checks for forms/admin actions.
- Validate uploaded files and restrict artwork file types.
- Do not store card data; rely on WooCommerce payment gateways.
- Keep MVP work simple and demo-ready.

## Skills Used In Abdullah's Previous Projects

Useful prior patterns from Abdullah's other repos:

- Release-readiness/security audit style from Mueenak.
- TDD and Playwright verification from Mueenak and Behavior Compass.
- Phase logs and implementation-plan discipline from Behavior Compass.
- Project-specific contract checks from Behavior Compass.

For Pixel, reuse those patterns lightly. Do not copy old app-specific constraints into this repo unless they apply to WordPress/WooCommerce printing work.
