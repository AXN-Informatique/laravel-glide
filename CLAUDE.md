# Laravel Glide

Package Laravel intégrant [Glide](https://glide.thephpleague.com/) pour la manipulation d'images à la volée (redimensionnement, recadrage, etc.) avec support multi-serveurs et URLs signées.

## Architecture

- `ServiceProvider` — Enregistre le singleton `glide`, publie la config, enregistre la commande Artisan
- `ServerManager` — Gère plusieurs serveurs Glide configurés, résout les disks filesystem
- `GlideServer` — Wrapper autour de `League\Glide\Server` (réponses image, base64, URLs signées)
- `Facades\Glide` — Facade Laravel
- `Responses\LaravelResponseFactory` — Adapter pour les réponses Laravel
- `Console\Commands\GlideKeyGenerate` — Commande `glide:key-generate` pour générer la clé de signature

## Configuration

- `config/glide.php` — Config principale (serveur par défaut, liste des serveurs)
- `config/glide_servers/*.php` — Config par serveur (source, cache, driver, signatures, presets)

## Commandes

```bash
# Linting
./vendor/bin/pint

# Refactoring automatisé
./vendor/bin/rector
```

## Pas de tests

Le package n'a pas de suite de tests.

## Documentation

- `docs/` — Documentation Savane (synchronisée et affichée dans le front-office)

## Laravel Boost Assets

The package provides Laravel Boost integration assets in `resources/boost/`:
- **Guidelines** (`guidelines/core.blade.php`): Package overview for AI assistants

**Important:** These files must be kept up to date when components, configuration keys, or usage patterns change.
