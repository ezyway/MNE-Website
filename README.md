# Maruti Nandan Exports — Website

Marketing website for [Maruti Nandan Exports](https://marutinandanexports.com), a Jamnagar-based
Indian agro-export house dealing in whole spices, ground spices, grains & rice, pulses, dry fruits,
and gourmet makhana.

Built as a **framework-free PHP site** — plain PHP templates rendered by Apache, with hand-written
CSS and vanilla JavaScript. There is no build step, bundler, or package manager: edit files and
refresh.

---

## Tech Stack

| Layer     | Technology                                                            |
| --------- | --------------------------------------------------------------------- |
| Backend   | PHP 8.3 (no framework)                                                 |
| Server    | Apache (`mod_php`) — dockerized or run with PHP's built-in server      |
| Frontend  | Semantic HTML, vanilla CSS, vanilla JS (no dependencies)               |
| Fonts     | Google Fonts: Fraunces (display) + Plus Jakarta Sans (body)            |
| Dev env   | Docker Compose with a live bind mount                                  |

## Project Structure

```
.
├── docker-compose.yml        # Dev environment (Apache + PHP on :8082)
├── Dockerfile                # Dev image (php:8.3-apache, errors shown)
├── README.md
├── docs/                     # Business documents (agreements, plans) — not code
├── utils/                    # Misc utilities (e.g. image conversion)
└── website/                  # ⬅ Web document root (deploy this directory)
    ├── index.php             # Home
    ├── about.php             # About Us
    ├── products.php          # Products catalog (all 6 categories, ?category=)
    ├── contact.php           # Contact + quotation form (+ ?item= deep links)
    ├── nav.php               # Shared header/nav (included by every page)
    ├── footer.html           # Shared footer, floating WhatsApp, cookie banner
    ├── lang_dropdown.html    # Google Translate language list
    ├── config.php            # ⬅ Shared constants (phones, WhatsApp, email, URLs)
    ├── template.php          # Blank scaffold for creating new pages
    ├── manifest.json         # PWA manifest
    ├── robots.txt            # Crawler rules (points to sitemap.xml)
    ├── sitemap.xml           # All public pages + priorities
    ├── assets/               # Images (logo, hero floats, products, icons)
    ├── scripts/              # One vanilla JS file per page + reveal.js
    └── styles/               # global.css (design system) + one CSS file per page
```

> The `.agents/`, `.claude/`, and `skills-lock.json` entries at the root are coding-agent
> scaffolding and are unrelated to the site itself.

## Getting Started (Docker)

Prerequisite: [Docker](https://docs.docker.com/get-docker/) (Docker Compose ships with
Docker Desktop).

```bash
# Start the dev server (first run builds the image)
docker compose up --build

# Open the site
open http://localhost:8082
```

- `website/` is bind-mounted into the container, so **PHP/CSS/JS changes apply on refresh** —
  no rebuild or restart needed.
- PHP errors are displayed in the browser (dev-only config in `Dockerfile`).
- Change the host port by editing the `ports` mapping in `docker-compose.yml`
  (e.g. `"9090:80"`).

```bash
# Stop the containers
docker compose down
```

### Without Docker

If PHP is installed locally:

```bash
php -S localhost:8082 -t website
```

## How Pages Are Wired

Every page follows the same pattern (see `template.php`):

1. Load `global.css`, the page's own stylesheet, and the page's own script.
2. Include `nav.php` (header) near the top and `footer.html` (footer + floating
   WhatsApp/cookie banner) at the end.
3. Give the first content section `id="main-content"` (target of the skip link).

Styling conventions:

- **Design tokens** (colors, spacing, radii, shadows, fonts, durations) live as CSS custom
  properties in `styles/global.css` — never hardcode hex values in page stylesheets.
- **Page-specific CSS** goes in the matching `styles/<page>.css`; shared primitives
  (`glass-panel`, buttons, breadcrumbs, ambient background, focus states) live in `global.css`
  and should not be duplicated per page.
- Shared interactive behaviors (scroll reveal, ambient floating cutouts, physics engine)
  are duplicated per-page in the page's own JS; they all respect `prefers-reduced-motion`.

### Content / Contact Details

All company contact details are centralized in **`website/config.php`** (phones, WhatsApp
number, trade email, site name/URL). Pages and includes reference these constants — update the
file in one place rather than editing markup.

## Code Checks

The project has no test suite or build step. Minimal sanity checks before pushing changes:

```bash
# PHP syntax check every page you touched
php -l website/products.php

# JS syntax check every script you touched
node --check website/scripts/nav.js
```

## Deployment

- **Source of truth:** the `main` branch.
- The **`website/` directory is the web root** — deploy its contents to the host (the
  `Dockerfile`/`docker-compose.yml` are dev-only conveniences).
- Production URL and domain-specific SEO files are already wired: `robots.txt` and
  `sitemap.xml` reference `https://marutinandanexports.com`; if the domain ever changes,
  update `SITE_URL` in `website/config.php`, plus `robots.txt` and `sitemap.xml`.
- After deploying asset changes (CSS/JS), hard-refresh or bump the asset query strings so
  visitors don't receive stale cached files.

## Notes

- The quote/inquiry flow intentionally funnels buyers to `contact.php` — product cards, CTA
  banners, and the nav all point there, and `?item=<name>&category=<key>` pre-fills the form.
- A floating WhatsApp button is shown on every page (see `footer.html` + `scripts/footer.js`).
