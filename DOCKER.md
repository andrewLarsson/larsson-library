# Docker deployment

This repository owns the library application's Dockerfile and Compose project.
The frontend remains in `andrewLarsson/larsson-library-ui` and is supplied as the
named `ui` build context. The image combines that pinned UI with this backend's
API. It uses PHP 8.5 and MariaDB 11.4, preserving the original latin1 table storage
and case-sensitive table names while using UTF8 for PHP/JSON connections.

The database and web container communicate over a private backend network. The
database has no published port. Only the web container joins the frontend network
used by the shared HTTPS proxy. Existing Basic Authentication remains mandatory.

Set `APP_VOLUME`, `PHP_IMAGE`, `MARIADB_IMAGE`, and `LIBRARY_UI_CONTEXT` in an
external environment file. Use exact image digests and UI Git commits. The UI
context accepts either a checkout path or `https://github.com/...git#COMMIT`.
`LIBRARY_BUILD_CONTEXT` can similarly target this repository at a commit.

Required external files:

- `$APP_VOLUME/secrets/library.htpasswd`: original Apache password hashes, mode
  0640, owner root and group 33.
- `$APP_VOLUME/secrets/library-db-password`: generated database account password,
  mode 0600, owner root.
- `$APP_VOLUME/secrets/library-db-root-password`: separate generated root password,
  mode 0600, owner root.
- `$APP_VOLUME/data/library/mysql`: persistent database data directory.

Create external network `apps-library-frontend`, then run:

```sh
docker compose --env-file /path/to/deployment.env build
docker compose --env-file /path/to/deployment.env up -d --wait
```

Import a logical SQL export, never an old MySQL 5.6 data directory. The historical
schema file in this repository is not a backup of live application data.

`tests/integration.py` validates all five resources, related checkouts, UTF8/latin1
round trips, and cleanup. Use an isolated test copy and a temporary Basic Auth
credential JSON file outside Git. Never run mutation tests against live data.

The AngularJS frontend is legacy software. This migration does not claim to
replace its framework or resolve all pre-existing frontend vulnerabilities.
