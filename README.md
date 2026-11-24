# Scheduling task manager for LaveroAdmin

A web interface for managing task scheduling in Laravel.

Repository: [https://github.com/LaveroAdmin/scheduling](https://github.com/LaveroAdmin/scheduling)

## Installation

```bash
composer require laveroadmin/scheduling

php artisan admin:import scheduling
```

Open `/admin/scheduling` in your browser.

## Usage

Define scheduled tasks in `routes/console.php` (or `app/Console/Kernel.php`):

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('inspire')->everyTenMinutes();
Schedule::command('route:list')->dailyAt('02:00');
```

The tasks will be displayed in the scheduling panel.

## Debugging

If the console shows errors like `sh: command not found`, specify your PHP binary path in `.env`:
```env
PHP_BINARY=/path/to/your/php/binary
```

## License

Licensed under [The MIT License (MIT)](LICENSE).

## Thanks

- Special thanks to [open-admin](https://github.com/open-admin-org/open-admin) for open-admin development.
- Special thanks to [z-song](https://github.com/z-song) for original development.
