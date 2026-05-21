#!/bin/bash
# deploy.sh - Deploy changes to GoDaddy hosting
#
# Usage: ./scripts/deploy.sh [site]
# Examples:
#   ./scripts/deploy.sh              # Deploy all sites
#   ./scripts/deploy.sh mibritech    # Deploy only mibritech.com

set -e

# Configuration
REMOTE_USER="betweentables"
REMOTE_HOST="godaddy"  # SSH config alias in ~/.ssh/config — resolves HostName + IdentityFile
REMOTE_PATH="/home/betweentables/public_html"
SSH_KEY="$HOME/.ssh/godaddy_id_rsa"

# Ensure SSH key is loaded
if ! ssh-add -l 2>/dev/null | grep -q "$(basename "$SSH_KEY")"; then
    echo "Adding $SSH_KEY to ssh-agent (passphrase required)..."
    ssh-add "$SSH_KEY"
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
LOCAL_PATH="$PROJECT_DIR/public_html"

# Sites to deploy
SITES=("mibritetech.com" "michaelteller.com" "rachaelslocum.com" "betweentables.com" "northaustinliving.com")

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

deploy_site() {
    local site=$1
    echo -e "${YELLOW}Deploying $site...${NC}"

    if [ "$site" == "betweentables.com" ]; then
        rsync -avz --delete \
            --exclude='.git' \
            --exclude='.env' \
            --exclude='vendor' \
            "$LOCAL_PATH/" \
            "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/"
    else
        rsync -avz --delete \
            --exclude='.git' \
            --exclude='.env' \
            --exclude='vendor' \
            --exclude='data/*.json' \
            "$LOCAL_PATH/$site/" \
            "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/$site/"
    fi

    echo -e "${GREEN}$site deployed successfully${NC}"
}

# Handle arguments
if [ -n "$1" ]; then
    case $1 in
        mibritech|mibritetech)
            deploy_site "mibritetech.com"
            ;;
        michael|michaelteller)
            deploy_site "michaelteller.com"
            ;;
        rachael|rachaelslocum)
            deploy_site "rachaelslocum.com"
            ;;
        between|betweentables)
            deploy_site "betweentables.com"
            ;;
        north|northaustinliving)
            deploy_site "northaustinliving.com"
            ;;
        *)
            echo "Unknown site: $1"
            echo "Available: mibritech, michael, rachael, between, north"
            exit 1
            ;;
    esac
else
    for site in "${SITES[@]}"; do
        deploy_site "$site"
    done
fi

echo -e "${GREEN}Deployment complete!${NC}"
