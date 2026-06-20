# Triage Labels

The project uses five simple labels for agent-friendly issue triage.

| Canonical role | GitHub label | Meaning |
| --- | --- | --- |
| `needs-triage` | `needs-triage` | Maintainer needs to inspect and classify the issue. |
| `needs-info` | `needs-info` | Waiting on the reporter/client/team for missing details. |
| `ready-for-agent` | `ready-for-agent` | Clear enough for an agent to implement without more human context. |
| `ready-for-human` | `ready-for-human` | Clear, but should be handled by a human developer/designer/admin. |
| `wontfix` | `wontfix` | The team decided not to action this item. |

When a skill refers to an "AFK-ready" or "agent-ready" issue, apply `ready-for-agent`.

When a skill refers to a human-only or human-review task, apply `ready-for-human`.
