# Earth Drilling — WordPress Multi-Site Platform

**Live Sites:**
- 🇨🇦 CA: https://earthdrilling.com/ca/
- 🇺🇸 US: https://earthdrilling.com/us/
- 🌐 Root: https://earthdrilling.com/ (country redirect popup)

**Repository:** https://github.com/camster91/earth-drilling-site
**Server:** `cameron@72.167.35.242` (AlmaLinux 9, Apache, PHP 8.3)

---

## Architecture

| Component | Path on Server | Path in Repo |
|-----------|---------------|--------------|
| Root redirect plugin | `/var/www/earthdrilling.com/public/wp-content/plugins/earthdrilling-country-redirect/` | `plugins/country-redirect/` |
| CA theme (`earthdrilling`) | `/var/www/earthdrilling.com/ca/public/wp-content/themes/earthdrilling/` | `themes/earthdrilling/` |
| US theme (`harrisexploration`) | `/var/www/earthdrilling.com/us/public/wp-content/themes/harrisexploration/` | `themes/harrisexploration/` |
| CA mu-plugins | `/var/www/earthdrilling.com/ca/public/wp-content/mu-plugins/` | `mu-plugins/ca/` |
| US mu-plugins | `/var/www/earthdrilling.com/us/public/wp-content/mu-plugins/` | `mu-plugins/us/` |

---

## Manual Deploy (from local)

```bash
cd ~/earthdrilling
./deploy.sh all         # Deploy everything
./deploy.sh ca          # CA theme + mu-plugins
./deploy.sh us          # US theme + mu-plugins
./deploy.sh root        # Root redirect plugin
./deploy.sh mu-plugins  # Mu-plugins to both sites
```

## Auto-Deploy (GitHub Actions)

On every push to `master`, GitHub Actions automatically deploys via rsync + SSH.

**Setup already done:**
- SSH key stored as `DEPLOY_KEY` secret in GitHub
- Workflow at `.github/workflows/deploy.yml`

---

## Color Preview (CA Site)

- Preview: `https://earthdrilling.com/ca/?ed_colors=gold`
- Permanent: `wp option update ed_colors_permanent 1 --allow-root`
- Disable: `wp option delete ed_colors_permanent --allow-root`

---

## Pending Tasks

| # | Task | Priority |
|---|------|----------|
| 1 | US site color changes (green → gold) | 🔴 High |
| 2 | Verify "Earth Drilling Updates" heading white | 🟡 Medium |
| 3 | Footer intro/logo layout tweaks | 🟡 Medium |
| 4 | Inner listing page card styling | 🟢 Low |
| 5 | News section / blog template | 🟢 Low |

---

## SSH Access

```bash
ssh -i ~/.ssh/id_ed25519_cameron cameron@72.167.35.242
```

**Key file:** `~/.ssh/id_ed25519_cameron`
