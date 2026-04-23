#!/bin/bash
# Earth Drilling Auto-Deploy Script
# Usage: ./deploy.sh [all|ca|us|root|plugins|themes]

set -e
SSH_KEY="${HOME}/.ssh/id_ed25519_cameron"
USER="cameron"
HOST="72.167.35.242"
ROOT="/var/www/earthdrilling.com"

# Color output
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

# Help
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

deploy_plugin() {
    local src="$1"
    local dest="$2"
    echo -e "${GREEN}→${NC} Deploying plugin: $(basename $src)"
    rsync -az --delete -e "ssh -i $SSH_KEY" "$src/" "${USER}@${HOST}:${dest}/"
}

deploy_theme() {
    local src="$1"
    local dest="$2"
    echo -e "${GREEN}→${NC} Deploying theme: $(basename $src)"
    rsync -az --delete -e "ssh -i $SSH_KEY" \
        --exclude='build/' \
        --exclude='node_modules/' \
        --exclude='package-lock.json' \
        --exclude='yarn.lock' \
        "$src/" "${USER}@${HOST}:${dest}/"
}

deploy_mu() {
    local src="$1"
    local dest="$2"
    echo -e "${GREEN}→${NC} Deploying mu-plugins"
    rsync -az -e "ssh -i $SSH_KEY" "$src/" "${USER}@${HOST}:${dest}/"
}

case "$TARGET" in
    root|all)
        deploy_plugin "plugins/country-redirect" "${ROOT}/public/wp-content/plugins/earthdrilling-country-redirect"
        ;;&
    ca|all)
        deploy_theme "themes/earthdrilling" "${ROOT}/ca/public/wp-content/themes/earthdrilling"
        deploy_mu "mu-plugins/ca" "${ROOT}/ca/public/wp-content/mu-plugins"
        ;;&
    us|all)
        deploy_theme "themes/harrisexploration" "${ROOT}/us/public/wp-content/themes/harrisexploration"
        deploy_mu "mu-plugins/us" "${ROOT}/us/public/wp-content/mu-plugins"
        ;;&
    plugins|all)
        deploy_plugin "plugins/country-redirect" "${ROOT}/public/wp-content/plugins/earthdrilling-country-redirect"
        ;;&
    themes|all)
        deploy_theme "themes/earthdrilling" "${ROOT}/ca/public/wp-content/themes/earthdrilling"
        deploy_theme "themes/harrisexploration" "${ROOT}/us/public/wp-content/themes/harrisexploration"
        ;;&
    mu-plugins|all)
        deploy_mu "mu-plugins/ca" "${ROOT}/ca/public/wp-content/mu-plugins"
        deploy_mu "mu-plugins/us" "${ROOT}/us/public/wp-content/mu-plugins"
        ;;&
esac

echo -e "${GREEN}✓${NC} Deploy complete: $TARGET"
echo "Clearing caches..."
ssh -i "$SSH_KEY" "${USER}@${HOST}" "sudo wp cache flush --allow-root 2>/dev/null || true"
echo -e "${GREEN}✓${NC} Done"
