# MNE Website

Static/PHP marketing website served with Apache + PHP.

## Project Structure

```
website/    # Site source (PHP, HTML, CSS, JS, assets)
docs/       # Project documents
utils/      # Misc utilities
```

## Development with Docker

Prerequisites: [Docker](https://docs.docker.com/get-docker/) and Docker Compose (bundled with Docker Desktop).

### Start the dev server

```bash
docker compose up --build
```

Then open http://localhost:8082

### Stop the dev server

```bash
docker compose down
```

### Notes

- The `website/` directory is mounted into the container, so PHP/CSS/JS changes are reflected immediately on refresh — no rebuild needed.
- PHP errors are displayed in the browser for easier debugging (dev-only config in `Dockerfile`).
- To change the port, edit the `ports` mapping in `docker-compose.yml` (e.g. `"9090:80"`).

### Run without Docker (alternative)

If you have PHP installed locally:

```bash
php -S localhost:8082 -t website
```
