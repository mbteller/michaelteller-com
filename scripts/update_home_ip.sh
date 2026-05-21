#!/bin/bash
# update_home_ip.sh
# Run this script on your home server via cron to update your IP on mibritech.com
#
# Cron example (every 6 hours):
# 0 */6 * * * /path/to/update_home_ip.sh >> /var/log/ip_update.log 2>&1

# Configuration - UPDATE THESE
API_URL="https://mibritech.com/api/ip/update"
API_KEY="your-api-key-here"  # Must match IP_TRACKER_API_KEY in .env
HOSTNAME=$(hostname)
SSH_PORT=22

# Get public IP
PUBLIC_IP=$(curl -s https://api.ipify.org)

if [ -z "$PUBLIC_IP" ]; then
    echo "[$(date)] ERROR: Could not determine public IP"
    exit 1
fi

# Update the IP on mibritech.com
RESPONSE=$(curl -s -X POST "$API_URL" \
    -H "Content-Type: application/json" \
    -d "{
        \"api_key\": \"$API_KEY\",
        \"ip\": \"$PUBLIC_IP\",
        \"hostname\": \"$HOSTNAME\",
        \"ssh_port\": $SSH_PORT
    }")

echo "[$(date)] IP: $PUBLIC_IP | Response: $RESPONSE"
