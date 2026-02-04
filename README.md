# Liberu Real Estate / Estate Agency

[![Install](https://github.com/liberu-real-estate/real-estate-laravel/actions/workflows/install.yml/badge.svg)](https://github.com/liberu-real-estate/real-estate-laravel/actions/workflows/install.yml)
[![Tests](https://github.com/liberu-real-estate/real-estate-laravel/actions/workflows/tests.yml/badge.svg)](https://github.com/liberu-real-estate/real-estate-laravel/actions/workflows/tests.yml)
[![Docker](https://github.com/liberu-real-estate/real-estate-laravel/actions/workflows/main.yml/badge.svg)](https://github.com/liberu-real-estate/real-estate-laravel/actions/workflows/main.yml)

![](https://img.shields.io/badge/PHP-8.4-informational?style=flat&logo=php&color=4f5b93) ![](https://img.shields.io/badge/Laravel-12-informational?style=flat&logo=laravel&color=ef3b2d) ![](https://img.shields.io/badge/Livewire-3.5-informational?style=flat&logo=Livewire&color=fb70a9) ![](https://img.shields.io/badge/Filament-4.0-informational?style=flat&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgeG1sbnM6dj0iaHR0cHM6Ly92ZWN0YS5pby9uYW5vIj48cGF0aCBkPSJNMCAwaDQ4djQ4SDBWMHoiIGZpbGw9IiNmNGIyNWUiLz48cGF0aCBkPSJNMjggN2wtMSA2LTMuNDM3LjgxM0wyMCAxNWwtMSAzaDZ2NWgtN2wtMyAxOEg4Yy41MTUtNS44NTMgMS40NTQtMTEuMzMgMy0xN0g4di01bDUtMSAuMjUtMy4yNUMxNCAxMSAxNCAxMSAxNS40MzggOC41NjMgMTkuNDI5IDYuMTI4IDIzLjQ0MiA2LjY4NyAyOCA3eiIgZmlsbD0iIzI4MjQxZSIvPjxwYXRoIGQ9Ik0zMCAxOGg0YzIuMjMzIDUuMzM0IDIuMjMzIDUuMzM0IDEuMTI1IDguNUwzNCAyOWMtLjE2OCAzLjIwOS0uMTY4IDMuMjA5IDAgNmwtMiAxIDEgM2gtNXYyaC0yYy44NzUtNy42MjUuODc1LTcuNjI1IDItMTFoMnYtMmgtMnYtMmwyLTF2LTQtM3oiIGZpbGw9IiMyYTIwMTIiLz48cGF0aCBkPSJNMzUuNTYzIDYuODEzQzM4IDcgMzggNyAzOSA4Yy4xODggMi40MzguMTg4IDIuNDM4IDAgNWwtMiAyYy0yLjYyNS0uMzc1LTIuNjI1LS4zNzUtNS0xLS42MjUtMi4zNzUtLjYyNS0yLjM3NS0xLTUgMi0yIDItMiA0LjU2My0yLjE4N3oiIGZpbGw9IiM0MDM5MzEiLz48cGF0aCBkPSJNMzAgMThoNGMyLjA1NSA1LjMxOSAyLjA1NSA1LjMxOSAxLjgxMyA4LjMxM0wzNSAyOGwtMyAxdi0ybC00IDF2LTJsMi0xdi00LTN6IiBmaWxsPSIjMzEyODFlIi8+PHBhdGggZD0iTTI5IDI3aDN2MmgydjJoLTJ2MmwtNC0xdi0yaDJsLTEtM3oiIGZpbGw9IiMxNTEzMTAiLz48cGF0aCBkPSJNMzAgMThoNHYzaC0ydjJsLTMgMSAxLTZ6IiBmaWxsPSIjNjA0YjMyIi8+PC9zdmc+)

A lightweight, open-source property management platform built with Laravel.

## TL;DR
- Modern Laravel + Livewire application for managing properties, transactions and users.

## Why this project
- Designed as a modular starting point for real-estate and agency workflows.

## Features
- Property listings, tenants, bookings and bids
- Admin UI powered by Filament
- Livewire-driven interactive interfaces
- Transaction tracking and reporting

## Quick start (local)
Prerequisites: PHP 8.3+, Composer, a database (MySQL / SQLite).

1. Clone and install

   ```powershell
   git clone https://github.com/liberu-real-estate/real-estate-laravel.git ; cd real-estate-laravel
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. Configure database in `.env`, then run migrations and seeders

   ```powershell
   php artisan migrate --seed
   ```

Note: `./setup.sh` automates these steps (it may overwrite `.env` and run seeders).

## Docker
- Build: `docker build -t real-estate-laravel .`
- Run: `docker run -p 8000:8000 real-estate-laravel`

## Laravel Sail
- Start Sail: `./vendor/bin/sail up`

## Demo
- Live demo: https://agent.liberu.co.uk

## Related projects
| Project | Description |
|---|---|
| liberu-accounting | Accounting backend for Liberu projects — https://github.com/liberu-accounting/accounting-laravel |
| liberu-automation | Automation tools and jobs — https://github.com/liberu-automation/automation-laravel |
| liberu-billing | Billing & invoicing — https://github.com/liberu-billing/billing-laravel |
| liberu-boilerplate | Common starter & utilities — https://github.com/liberusoftware/boilerplate |
| liberu-browser-game | Example game project — https://github.com/liberu-browser-game/browser-game-laravel |
| liberu-cms | Simple CMS — https://github.com/liberu-cms/cms-laravel |
| liberu-control-panel | Control panel for services — https://github.com/liberu-control-panel/control-panel-laravel |
| liberu-crm | CRM system — https://github.com/liberu-crm/crm-laravel |
| liberu-ecommerce | E-commerce starter — https://github.com/liberu-ecommerce/ecommerce-laravel |
| liberu-genealogy | Genealogy project — https://github.com/liberu-genealogy/genealogy-laravel |
| liberu-maintenance | Maintenance & scheduling — https://github.com/liberu-maintenance/maintenance-laravel |
| liberu-real-estate | This repository — https://github.com/liberu-real-estate/real-estate-laravel |
| liberu-social-network | Social network example — https://github.com/liberu-social-network/social-network-laravel |

## Contributing
- Please open issues or PRs. If contributing code, follow PSR-12 and include tests where appropriate.
- See `CONTRIBUTING.md` if present.

## License
- MIT — see LICENSE for details.

## Maintainers
- Project managed by Liberu — https://liberu.co.uk

<!-- End of file -->
