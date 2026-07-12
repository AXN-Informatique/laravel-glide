# Laravel Glide

- On-demand image manipulation (league/glide) with multi-server support and signed URLs: `Glide::url($path, $params)`, `Glide::imageResponse()`, `Glide::server('name')`; generate the signing key with `php artisan glide:key-generate`.
- Configuration is split between `config/glide.php` (general) and `config/glide_servers/*.php` (per server).
- See the package's `docs/` directory for details.
