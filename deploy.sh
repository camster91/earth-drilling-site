#!/bin/bash
# Earth Drilling Auto-Deploy Script
# Usage: ./deploy.sh [all|ca|us|root|plugins|themes|mu-plugins]

set -e
SSH_KEY="${HOME}/.ssh/id_ed25519_cameron"
USER="cameron"
HOST="72.167.35.242"
ROOT="/var/www/earthdrilling.com"

GREEN='\033[0;32m'
NC='\033[0m'

if [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
    echo "Earth Drilling Deploy"
    echo "Usage: ./deploy.sh [all|ca|us|root|plugins|themes|mu-plugins]"
    echo ""
    echo "Targets:"
    echo "  all        - Deploy everything"
    echo "  ca         - Deploy CA theme + mu-plugins"
    echo "  us         - Deploy US theme + mu-plugins"
    echo "  root       - Deploy root redirect plugin"
    echo "  plugins    - Deploy all plugins"
    echo "  themes     - Deploy both themes"
    echo "  mu-plugins - Deploy mu-plugins to CA + US"
    exit 0
fi

TARGET="${1:-all}"

deploy() {
    local src="$1"
    local dest="$2"
    echo -e "${GREEN}→${NC} Deploying: $(basename $src)"
    rsync -az --delete -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=no" \
        --exclude='build/' \
        --exclude='node_modules/' \
        --exclude='package-lock.json' \
        --exclude='yarn.lock' \
        "$src/" "${USER}@${HOST}:${dest}/"
}

if [ "$TARGET" = "root" ] || [ "$TARGET" = "plugins" ] || [ "$TARGET" = "all" ]; then
    deploy "plugins/country-redirect" "${ROOT}/public/wp-content/plugins/earthdrilling-country-redirect"
fi

if [ "$TARGET" = "ca" ] || [ "$TARGET" = "themes" ] || [ "$TARGET" = "all" ]; then
    deploy "themes/earthdrilling" "${ROOT}/ca/public/wp-content/themes/earthdrilling"
fi

if [ "$TARGET" = "us" ] || [ "$TARGET" = "themes" ] || [ "$TARGET" = "all" ]; then
    deploy "themes/harrisexploration" "${ROOT}/us/public/wp-content/themes/harrisexploration"
fi

if [ "$TARGET" = "ca" ] || [ "$TARGET" = "mu-plugins" ] || [ "$TARGET" = "all" ]; then
    rsync -az -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=no" \
        "mu-plugins/ca/" "${USER}@${HOST}:${ROOT}/ca/public/wp-content/mu-plugins/"
fi

if [ "$TARGET" = "us" ] || [ "$TARGET" = "mu-plugins" ] || [ "$TARGET" = "all" ]; then
    rsync -az -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=no" \
        "mu-plugins/us/" "${USER}@${HOST}:${ROOT}/us/public/wp-content/mu-plugins/"
fi

echo -e "${GREEN}✓${NC} Deploy complete: $TARGET"
