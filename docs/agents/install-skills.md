# Install Agent Skills

This file tells Pixel team members how to install or enable the skills referenced by `docs/agents/recommended-skills.md`.

The repo does not vendor the skill source folders. Skills should be installed into each developer's agent environment, not into the WordPress theme/plugin code.

## Quick Setup For Team Members

1. Install or open your agent tool: Codex, Claude Code, Cursor, or another skills-aware agent.
2. Clone/open this repo.
3. Ask your agent:

```text
Read AGENTS.md and docs/agents/*.md.
Install or enable the skills listed in docs/agents/install-skills.md.
Then restart the agent so new skills are discovered.
```

4. Restart the agent after installing skills.

## Required Repo Knowledge

These are not external skills. They are repo files that every agent must read:

- `AGENTS.md`
- `PROJECT_KNOWLEDGE.md`
- `PIXEL_TASKS.md`
- `docs/BUILD_PLAN.md`
- `docs/agents/domain.md`
- `docs/agents/recommended-skills.md`

## Codex / Superpowers Skills

These are commonly bundled or already installed in Codex/Superpowers-style environments. If missing, install the Superpowers skill pack used by your agent environment.

| Skill | Why Pixel uses it |
| --- | --- |
| `using-superpowers` | Force skill discovery before work starts. |
| `brainstorming` | Design before UI/features are implemented. |
| `writing-plans` | Turn specs into task-by-task implementation plans. |
| `using-git-worktrees` | Isolate larger feature work. |
| `test-driven-development` | Use for risky logic, uploads, security, and workflow behavior. |
| `systematic-debugging` | Root-cause broken pages/forms/LocalWP issues. |
| `subagent-driven-development` | Execute larger plans with independent tasks. |
| `executing-plans` | Execute a written plan inline. |
| `requesting-code-review` | Review meaningful changes before PR/merge. |
| `receiving-code-review` | Apply review feedback carefully. |
| `verification-before-completion` | Verify before claiming completion. |
| `finishing-a-development-branch` | Finish, integrate, or PR a feature branch. |
| `dispatching-parallel-agents` | Split independent work across agents. |
| `writing-skills` | Maintain skills if the team later creates Pixel-specific ones. |

If your agent supports direct GitHub skill installation, install the equivalent Superpowers skills from that agent's official source. If your agent already lists these skills, do not reinstall them.

## User-Installed Skills From Abdullah's Environment

These have known source repositories and paths from Abdullah's installed skill lock file. Use these if your agent has a GitHub skill installer.

| Skill | Source repo | Skill path |
| --- | --- | --- |
| `find-skills` | `vercel-labs/skills` | `skills/find-skills/SKILL.md` |
| `emil-design-eng` | `emilkowalski/skill` | `skills/emil-design-eng/SKILL.md` |
| `diagnose` | `mattpocock/skills` | `skills/engineering/diagnose/SKILL.md` |
| `grill-with-docs` | `mattpocock/skills` | `skills/engineering/grill-with-docs/SKILL.md` |
| `improve-codebase-architecture` | `mattpocock/skills` | `skills/engineering/improve-codebase-architecture/SKILL.md` |
| `prototype` | `mattpocock/skills` | `skills/engineering/prototype/SKILL.md` |
| `setup-matt-pocock-skills` | `mattpocock/skills` | `skills/engineering/setup-matt-pocock-skills/SKILL.md` |
| `tdd` | `mattpocock/skills` | `skills/engineering/tdd/SKILL.md` |
| `to-issues` | `mattpocock/skills` | `skills/engineering/to-issues/SKILL.md` |
| `to-prd` | `mattpocock/skills` | `skills/engineering/to-prd/SKILL.md` |
| `triage` | `mattpocock/skills` | `skills/engineering/triage/SKILL.md` |
| `zoom-out` | `mattpocock/skills` | `skills/engineering/zoom-out/SKILL.md` |
| `grill-me` | `mattpocock/skills` | `skills/productivity/grill-me/SKILL.md` |
| `handoff` | `mattpocock/skills` | `skills/productivity/handoff/SKILL.md` |
| `write-a-skill` | `mattpocock/skills` | `skills/productivity/write-a-skill/SKILL.md` |

Optional, not normally needed for Pixel unless the stack changes:

| Skill | Source repo | Skill path |
| --- | --- | --- |
| `supabase` | `supabase/agent-skills` | `skills/supabase/SKILL.md` |
| `supabase-postgres-best-practices` | `supabase/agent-skills` | `skills/supabase-postgres-best-practices/SKILL.md` |

## Codex Skill Installer Prompt

In Codex, paste this if the skills are missing:

```text
Use the skill-installer capability to install these GitHub skills:

- vercel-labs/skills: skills/find-skills/SKILL.md
- emilkowalski/skill: skills/emil-design-eng/SKILL.md
- mattpocock/skills:
  - skills/engineering/diagnose/SKILL.md
  - skills/engineering/grill-with-docs/SKILL.md
  - skills/engineering/improve-codebase-architecture/SKILL.md
  - skills/engineering/prototype/SKILL.md
  - skills/engineering/setup-matt-pocock-skills/SKILL.md
  - skills/engineering/tdd/SKILL.md
  - skills/engineering/to-issues/SKILL.md
  - skills/engineering/to-prd/SKILL.md
  - skills/engineering/triage/SKILL.md
  - skills/engineering/zoom-out/SKILL.md
  - skills/productivity/grill-me/SKILL.md
  - skills/productivity/handoff/SKILL.md
  - skills/productivity/write-a-skill/SKILL.md

After installation, tell me to restart Codex.
```

## Browser And Plugin Skills

Some skills come from enabled plugins, not from the generic skills installer.

| Skill or plugin | Setup |
| --- | --- |
| `browser:control-in-app-browser` | Enable the Browser plugin in Codex. Use it for LocalWP visual checks and screenshots. |
| GitHub connector skills | Enable the GitHub app/connector in Codex if the agent needs to inspect PRs/issues or create PRs. |
| Documents/PDF/Presentations/Spreadsheets | Optional. Not needed for normal WordPress development unless creating client docs/decks/files. |

## Verify Installation

After installing and restarting the agent, ask:

```text
List the available skills relevant to this repo and confirm you can see:
using-superpowers, brainstorming, writing-plans, zoom-out, tdd, diagnose,
triage, to-issues, verification-before-completion, emil-design-eng,
and browser:control-in-app-browser if the Browser plugin is enabled.
```

If a skill is unavailable, continue with the best available equivalent and note the missing skill in the task handoff.
