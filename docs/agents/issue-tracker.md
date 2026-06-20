# Issue Tracker: GitHub

Issues, PRDs, and implementation tickets for this repo live in GitHub Issues for:

```text
MohamedKasabgy/Pixel
```

Use GitHub Issues when a skill says to publish work to the issue tracker, create implementation tickets, triage work, or fetch a relevant ticket.

## Conventions

- Create issues with clear MVP-scoped titles.
- Prefer one independently testable slice per issue.
- Link issue bodies back to `PROJECT_KNOWLEDGE.md`, `PIXEL_TASKS.md`, or the relevant template/plugin file when useful.
- Use labels from `docs/agents/triage-labels.md`.
- For code work, open a branch and PR instead of working directly on `main`.

## Useful Commands

```bash
gh issue create --repo MohamedKasabgy/Pixel --title "..." --body "..."
gh issue view <number> --repo MohamedKasabgy/Pixel --comments
gh issue list --repo MohamedKasabgy/Pixel --state open
gh issue comment <number> --repo MohamedKasabgy/Pixel --body "..."
gh issue edit <number> --repo MohamedKasabgy/Pixel --add-label "ready-for-agent"
gh issue close <number> --repo MohamedKasabgy/Pixel --comment "..."
```

If the GitHub CLI is unavailable, use the connected GitHub app/tooling when available.
