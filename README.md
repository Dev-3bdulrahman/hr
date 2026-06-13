# 👥 HR — Laravel ERP Module

[![Latest Version](https://img.shields.io/packagist/v/dev-3bdulrahman/hr.svg?style=flat-square)](https://packagist.org/packages/dev-3bdulrahman/hr)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue?style=flat-square)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%2B%20%7C%2012%2B-red?style=flat-square)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

A comprehensive **Human Resources** module for Laravel ERP systems. Manage employees, track attendance, handle leave requests, and run payroll — with full API and Livewire admin interface.

---

## Features

- Employee Management & Profiles
- Attendance Tracking
- Leave Requests & Approvals
- Payroll Processing
- REST API endpoints
- Arabic & English translations

## Requirements

| Dependency | Version |
|---|---|
| PHP | ^8.2 \| ^8.3 |
| Laravel | ^11.0 \| ^12.0 |

## Installation

```bash
composer require dev-3bdulrahman/hr
```

Publish and run migrations:

```bash
php artisan vendor:publish --provider="Dev3bdulrahman\Hr\Providers\HrServiceProvider"
php artisan migrate
```

## Service Provider

Auto-discovered via Laravel package discovery. Manual registration in `bootstrap/providers.php`:

```php
Dev3bdulrahman\Hr\Providers\HrServiceProvider::class,
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## License

MIT License © [Abdulrahman](https://3bdulrahman.com)
