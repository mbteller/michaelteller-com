# michaelteller-com

Personal portfolio site and GoDaddy hosting infrastructure.

## Live Sites

| Domain | Type | Status |
|--------|------|--------|
| michaelteller.com | Static HTML | Live — personal portfolio |
| mibritetech.com | Slim PHP | Placeholder (IP tracker API in source) |
| betweentables.com | Static | Placeholder |
| northaustinliving.com | Static | Placeholder |
| rachaelslocum.com | Static | Placeholder |

## Stack

- **Portfolio:** Pure static HTML/CSS/JS — HTML5 UP Astral template
- **SSL/CDN:** Cloudflare (free tier, flexible SSL)
- **Hosting:** GoDaddy shared hosting
- **Deployment:** rsync over SSH via `scripts/deploy.sh`

## Deployment

```bash
# Deploy portfolio site
./scripts/deploy.sh michael

# Deploy other sites
./scripts/deploy.sh mibritech
./scripts/deploy.sh rachael
./scripts/deploy.sh north
./scripts/deploy.sh between

# Deploy all
./scripts/deploy.sh
```

SSH config alias `godaddy` must be set up in `~/.ssh/config` pointing to the hosting server.

## Local Development

Docker environment available for PHP sites:

```bash
docker-compose up -d
# Web: http://localhost:8080
# phpMyAdmin: http://localhost:8081
```
