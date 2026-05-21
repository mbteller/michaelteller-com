# GoDaddy Hosting Project

Local development environment and deployment tools for sites hosted on GoDaddy.

## Sites

| Domain | Type | Description |
|--------|------|-------------|
| betweentables.com | WordPress | Main hosting account (root) |
| mibritetech.com | Slim PHP | Portfolio site + home server IP tracker |
| michaelteller.com | Placeholder | Personal site (TBD) |
| northaustinliving.com | WordPress | Local living blog |
| rachaelslocum.com | Placeholder | (TBD) |

## Directory Structure

```
godaddy_hosting/
├── docker-compose.yml      # Local dev environment
├── .env                    # Local environment variables (git-ignored)
├── .gitignore
├── i2369960_wp1.sql        # betweentables.com database export
├── i2369960_wp2.sql        # northaustinliving.com database export
├── docker/
│   ├── apache/vhosts.conf  # Virtual host configs for all sites
│   ├── mysql/init.sql      # Database initialization
│   └── php/php.ini         # PHP configuration
├── scripts/
│   ├── deploy.sh           # Deploy to GoDaddy
│   └── update_home_ip.sh   # Home server IP updater (for cron)
└── public_html/            # Cloned from GoDaddy server
    ├── [WordPress files]   # betweentables.com root
    ├── mibritetech.com/    # Slim framework portfolio
    ├── michaelteller.com/
    ├── northaustinliving.com/
    └── rachaelslocum.com/
```

## Local Development Setup

### Prerequisites
- Docker and Docker Compose
- Composer (for Slim dependencies)

### 1. Start the containers

```bash
cd godaddy_hosting
docker-compose up -d
```

This starts:
- **Web server**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3307

### 2. Add local hosts (optional, for subdomain testing)

Add to `/etc/hosts`:
```
127.0.0.1 betweentables.local
127.0.0.1 mibritech.local
127.0.0.1 michaelteller.local
127.0.0.1 northaustinliving.local
127.0.0.1 rachaelslocum.local
```

Then access via http://mibritech.local:8080 etc.

### 3. Install Slim dependencies (mibritetech.com)

```bash
cd public_html/mibritetech.com
composer install
cp .env.example .env
# Edit .env to set IP_TRACKER_API_KEY
```

### 4. WordPress local config

The WordPress sites need their `wp-config.php` updated for local dev:
- Change `DB_HOST` to `db` (Docker service name)
- Change `DB_USER` password to `localdev`

Or create `wp-config-local.php` includes.

## Deployment

### Deploy to GoDaddy

First, update `scripts/deploy.sh` with your GoDaddy SSH credentials:
```bash
REMOTE_USER="your-username"
REMOTE_HOST="your-host.ssh.phx3.nearlyfreespeech.net"  # or similar
REMOTE_PATH="/home/username/public_html"
```

Then deploy:
```bash
# Deploy all sites
./scripts/deploy.sh

# Deploy single site
./scripts/deploy.sh mibritech
```

### Git workflow (recommended)

1. Make changes locally
2. Test in Docker environment
3. Commit to git
4. Push to home server
5. Deploy script runs (manually or via post-receive hook)

## MiBriTech.com - IP Tracker

The mibritetech.com site includes an API for tracking home server IP:

### API Endpoints

**GET /api/ip** - Get stored home server IP
```json
{"success": true, "ip": "xxx.xxx.xxx.xxx", "updated_at": "2025-01-31T..."}
```

**POST /api/ip/update** - Update IP (requires API key)
```json
{
  "api_key": "your-secret-key",
  "ip": "xxx.xxx.xxx.xxx",
  "hostname": "homeserver",
  "ssh_port": 22
}
```

### Home Server Cron Setup

Copy `scripts/update_home_ip.sh` to your home server and set up cron:

```bash
# Edit the script with your API key
nano update_home_ip.sh

# Add to crontab (every 6 hours)
crontab -e
0 */6 * * * /path/to/update_home_ip.sh >> /var/log/ip_update.log 2>&1
```

## Credentials

### Local Development
- MySQL root: `localdev`
- All DB users: `localdev`
- phpMyAdmin: root / localdev

### Production
- Stored separately (not in git)
- SSH key with passphrase configured

## Notes

- SQL dumps are large (~270MB total) - consider git-lfs or excluding from repo
- WordPress uploads are excluded from git and deploys
- wp-config.php files excluded from deploys to preserve production credentials
