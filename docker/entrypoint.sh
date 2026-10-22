#!/bin/sh
set -eu
test -s /run/secrets/library.htpasswd || { echo 'Missing library authentication file' >&2; exit 1; }
test -s /run/secrets/library-db-password || { echo 'Missing database password' >&2; exit 1; }
php -r '
$config = ["driver" => "mysql", "host" => getenv("DB_HOST") ?: "db", "user" => getenv("DB_USER") ?: "library", "password" => trim(file_get_contents("/run/secrets/library-db-password")), "schema" => getenv("DB_NAME") ?: "LarssonLibrary", "charset" => "utf8mb4"];
$lines = [];
foreach ($config as $key => $value) { $lines[] = $key . "=" . json_encode($value, JSON_UNESCAPED_SLASHES); }
file_put_contents("/opt/larsson/conf/database/conf.d/LarssonLibrary.ini", implode("\n", $lines) . "\n");
'
exec docker-php-entrypoint "$@"
