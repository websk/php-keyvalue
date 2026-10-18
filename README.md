# WebSK php-keyvalue

Key/value settings storage for WebSK applications, with CRUD administration, caching, and change logging.

## Requirements

- PHP 8.3 or later, including PHP 8.5
- `ext-mbstring`
- `ext-pdo`
- `ext-pdo_mysql`
- MySQL or MariaDB
- A cache backend supported by `websk/php-cache`

## Installation

Install the package with Composer:

```shell
composer require websk/php-keyvalue
```

Register `CacheServiceProvider`, `LoggerServiceProvider`, and `KeyValueServiceProvider` in the application's mutable container. The demo application in `src/WebSK/KeyValue/Demo` shows the complete PHP-DI 7 setup.

The package does not require an authentication library. By default, change-log entries have no user identifier. Applications using `websk/php-auth` can supply it while registering the provider:

```php
use WebSK\Auth\Auth;
use WebSK\KeyValue\KeyValueServiceProvider;
use WebSK\Utils\FullObjectId;

KeyValueServiceProvider::register(
    $mutable_container,
    static fn(): ?string => FullObjectId::getFullObjectId(Auth::getCurrentUserObj())
);
```

The demo keeps `websk/php-auth` as a development dependency and uses this integration.

Read a setting through the service:

```php
use WebSK\KeyValue\KeyValueService;

$value = $container->get(KeyValueService::class)
    ->getOptionalValueForKey('feature.enabled', 'no');
```

Store or update a setting:

```php
$container->get(KeyValueService::class)
    ->setValueForKey('feature.enabled', 'yes', 'Feature switch');
```

## Configuration

See `config/config.default.php` for the complete example. At minimum, configure the KeyValue database and a cache engine:

```php
'db' => [
    'db_keyvalue' => [
        'host' => 'localhost',
        'db_name' => 'keyvalue',
        'user' => 'root',
        'password' => '',
        'dump_file_path' => \WebSK\KeyValue\KeyValueServiceProvider::DUMP_FILE_PATH,
    ],
],
'keyvalue' => [
    'layout_admin' => '/absolute/path/to/layout.main.tpl.php',
    'admin_main_page_url' => '/admin',
],
```

Create the schema with the WebSK DB migration command:

```shell
php vendor/bin/websk_db_migration.php migrations:migration_auto
```

You can also apply `dumps/db_keyvalue.sql` to a new database directly.

## Demo

1. Copy `config/config.default.php` to `config/config.php`.
2. Adjust the database, cache, and absolute layout paths.
3. Run the database migrations.
4. Start the built-in server from the project root:

```shell
php -S localhost:8000 -t public
```

Open <http://localhost:8000>.

## Development

Run the default verification suite:

```shell
composer check
```

MySQL integration tests are opt-in:

```shell
MYSQL_TEST_HOST=127.0.0.1 \
MYSQL_TEST_DB=keyvalue_test \
MYSQL_TEST_USER=root \
MYSQL_TEST_PASSWORD=secret \
composer test:mysql
```

The MySQL suite recreates the `key_value` table in the configured test database.

## License

MIT. See [LICENSE](LICENSE).
