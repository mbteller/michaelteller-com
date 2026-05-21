# GoDaddy Hosting — Claude Context

## Active project: michaelteller.com portfolio

Building a static personal portfolio site. See README.md for full project structure.

### Decisions already made
- **Stack:** Pure static HTML/CSS/JS, no build toolchain. Template from html5up.net (suggested: Read Only, Prologue, or Astral).
- **Deployment:** rsync over SSH via `scripts/deploy.sh` — fill in REMOTE_USER / REMOTE_HOST / REMOTE_PATH with GoDaddy SSH credentials.
- **DNS:** Point michaelteller.com A record directly to GoDaddy hosting IP. Virtual hosting handles the rest via the public_html/michaelteller.com/ directory.
- **Starting fresh:** public_html/michaelteller.com/ is being rebuilt from scratch.
- **Content source:** ~/Projects/job_hunt/ has resume and related content to pull from.

### Next steps
1. Fill SSH credentials into scripts/deploy.sh
2. Test: `ssh user@host`
3. Pick and drop in an html5up.net template into public_html/michaelteller.com/
4. Customize with job_hunt content
5. Deploy: `./scripts/deploy.sh michael`

### Other sites (lower priority)
- mibritech.com — tech/brand site, Slim PHP, has IP tracker API
- betweentables.com — WordPress, root hosting domain
- northaustinliving.com — WordPress blog
- rachaelslocum.com — placeholder
