@php
/** @var \Laravel\Boost\Install\GuidelineAssist $assist */
@endphp
# Laravel Glide

- Laravel Glide provides on-demand image manipulation using [Glide](https://glide.thephpleague.com/) with multi-server support and signed URLs.
- Use `Glide::url($path, $params)` to generate signed image URLs and `Glide::imageResponse($path, $params)` to return image responses.
- Use `Glide::server('name')` to target a specific server; the default server is used when none is specified.
- Generate the signing key with `{{ $assist->artisanCommand('glide:key-generate') }}`.
- Configuration is split: `config/glide.php` for general settings, `config/glide_servers/*.php` for per-server settings.
