# Vivu Planner — Deploy-runbook (planner.vivu.no)

Tre steder du gjør ting. Hver kommando er merket med hvor den kjøres:

- 🟦 **DIN MASKIN** – din lokale Terminal (macOS)
- 🟩 **CLOUDWAYS PANEL** – i nettleseren (platform.cloudways.com)
- 🟧 **CLOUDWAYS SSH** – terminal inne på serveren

> 🔒 **Sikkerhet:** DB-passord, SSH-nøkler og API-tokens legges **kun** rett inn i Cloudways /
> serverens `.env` / GitHub Secrets. Del dem aldri i chat.

---

## 0. Forutsetninger (engangs på Cloudways)
- App-type: **Custom PHP** på Vivu-serveren
- **PHP 8.3** (Settings & Packages → PHP version)
- Domene **planner.vivu.no** + SSL (pågår)
- Database opprettes automatisk – noter navn/bruker/passord under **Access Details**

---

## 1. 🟦 Push koden (din maskin)
```bash
cd "/Users/rogerhenriksen/Claude/Projects/FLIK - Årshjul/planner"
git push -u origin main
# Hvis avvist fordi repoet har en start-commit:
#   git push -u origin main --force
```

## 2. 🟩 Cloudways: app-innstillinger
1. **Webroot** → `public_html/public` (Application Settings → Webroot). *Viktigste steg.*
2. **Domain Management** → legg til `planner.vivu.no` som primært domene, aktiver **SSL**.
3. **Deployment via Git**:
   - Remote: `git@github.com:lipstickdesign/planner.git`
   - Branch: `main`, Deploy path: `public_html`
   - Cloudways viser en **deploy-nøkkel** → lim den inn i GitHub: repo → *Settings → Deploy keys → Add*.
   - Trykk **Pull/Deploy** (henter filene inn i `public_html`).

## 3. 🟧 Cloudways SSH: første gangs oppsett
Logg inn (Server → Master Credentials gir SSH), så:
```bash
cd ~/applications/APPNAVN/public_html      # bytt APPNAVN
cp .env.example .env
nano .env
#   APP_ENV=production
#   APP_DEBUG=false
#   APP_URL=https://planner.vivu.no
#   DB_DATABASE / DB_USERNAME / DB_PASSWORD = fra Access Details
php artisan key:generate
php artisan migrate --seed --force         # --seed KUN denne første gangen (legger inn FLIK-årshjulet)
php artisan config:cache route:cache view:cache
php artisan storage:link
```

## 4. Verifiser
Åpne **https://planner.vivu.no/up** → skal vise Laravels helsesjekk «OK».
(Sprint 1 har ingen synlig frontend ennå; `/up` bekrefter at appen + databasen kjører.)

---

## 5. Hver gang Claude har endret kode (rutine)
1. 🟦 `git push`
2. 🟩 Cloudways → **Deploy/Pull** (eller automatisk, se under)
3. 🟧 `bash deploy.sh`  *(composer + migrasjoner + cache – seeder IKKE på nytt)*

## 6. Skalerbart: automatisk deploy (anbefalt når dere er klare)
**Alternativ A – Cloudways auto-deploy:** slå på *Auto Deploy on Git Push* i Deployment-fanen.
Da henter Cloudways koden automatisk ved hver `git push`. Kjør `bash deploy.sh` via en cron eller manuelt.

**Alternativ B – GitHub Actions (full automatikk):** gi nytt navn til
`.github/workflows/deploy.yml.example` → `deploy.yml`, og legg inn fire GitHub Secrets
(*Settings → Secrets and variables → Actions*):
`CLOUDWAYS_SSH_HOST`, `CLOUDWAYS_SSH_USER`, `CLOUDWAYS_SSH_KEY`, `CLOUDWAYS_APP_PATH`.
Da kjører `git pull` + `deploy.sh` på serveren automatisk ved hver push til `main`.

---

## Hva Claude trenger fra deg
| # | Hva | Hvorfor | Hemmelig? |
|---|-----|---------|-----------|
| 1 | **App-navn/sti** på Cloudways (f.eks. `~/applications/abcd1234/public_html`) | Så jeg skriver eksakte kommandoer/stier | Nei |
| 2 | Bekreftelse på at **push + deploy** funker (gjerne utskrift/feilmelding) | Så jeg kan feilsøke | Nei |
| 3 | **Google Drive-tilgang** til årshjul-mappen | Importere ekte tekster/bilder | Nei |
| 4 | DB-creds, SSH-nøkkel, tokens | Legges av deg i `.env` / Cloudways / GitHub Secrets | **JA – aldri i chat** |

Arbeidsdeling: **jeg** skriver kode + oppskrifter og committer lokalt → **du** pusher og kjører de merkede kommandoene. Når auto-deploy (pkt. 6) står, blir din jobb stort sett bare `git push`.
