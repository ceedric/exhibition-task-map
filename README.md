# Exhibition Task Map

A single-file, no-build-step tool for planning and tracking everything that goes into mounting an art exhibition — the art itself, production/install, admin, people, promotion, events, the artist's own capacity, and contingency planning. Built for a solo show, but works for any small-to-mid-size exhibition or collaboration.

**[Live demo](#)** — replace this with your GitHub Pages URL once it's published (Settings → Pages → deploy from main).

## Quick start

1. Download `index.html` (and, optionally, `api.php` — see Sync below).
2. Open `index.html` in a browser, or host it anywhere (GitHub Pages, Netlify, your own server). No build step, no dependencies to install.
3. It ships with ~20 example tasks across all 8 categories, so you can see how subtasks, notes, statuses, deadlines and date ranges work. Delete them and add your own as you go — or hold the "reset" button to clear everything and start from a true blank slate.

## What it does

- **Two views**: a collapsible tree grouped by category, and a calendar grouped by deadline (with real multi-day date ranges for things like a rotating cast of participants).
- **Per-task fields**: assignee, paid/unpaid, lead time, hands-on time, energy cost, status (not started / in motion / waiting on someone / good enough / not necessary), deadline, an optional end date for date ranges, free-text notes, and pinning.
- Any task whose title contains a `?` automatically sorts to the top, alongside pinned tasks — a way to flag open questions without a separate field.
- **Lightweight attribution**: type your name once and it's remembered on this device, tagging who last edited each task — no accounts, no password.
- **Print views**: a checkbox-style checklist (grouped by status, hiding anything marked "good enough" or "not necessary") and a traditional month-grid calendar print.
- **Export/import**: copy the whole board as a compressed text code, or as a shareable link, to move it between devices or hand it to a collaborator.

## Sync across devices (optional)

The tool works entirely standalone — nothing below is required. If you want it to stay in sync automatically across your own devices (phone, laptop, someone else's wifi), it can optionally talk to a tiny PHP backend:

1. You need PHP hosting (shared hosting, most cPanel-type hosts, etc.) — GitHub Pages can't run this part, since it only serves static files.
2. Upload `api.php` into the same folder as `index.html`.
3. Create a `history/` folder next to it, writable by PHP (the script will also try to create it itself).
4. That's it — the app auto-detects `api.php` at `./api.php` relative to itself. If it's not reachable (e.g. on GitHub Pages), the app just quietly stays local-only: no errors, no broken features, you only lose the auto-sync/history buttons' usefulness.

There's **no password** on the sync endpoint — anyone with the URL can read and write, the same trust model as sharing an export code by hand. Don't put anything on it you wouldn't want someone with the link to see or change. The "history / revert" button lists recent snapshots so a bad sync or accidental overwrite can always be rolled back.

## Customizing for your own show

- Edit the `CATS` array near the top of the script to rename or re-theme the 8 categories (their colors live in the `:root` CSS variables).
- Edit `seedTasks()` to replace the starter examples with your own tasks, or just delete them in the app itself.
- The roster starts as just `['Me']` — add collaborator names as you need them; the app will remember them in the assignee dropdown once you've typed them in once.
- The "Stuck on this? → Bubblemap" link points at a companion decision-making tool; feel free to remove that link or point it at your own resource if it's not useful to you.

## License

GNU GPL-2.0 — see `LICENSE`.
