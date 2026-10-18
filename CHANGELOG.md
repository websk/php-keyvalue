# Changelog

All notable changes to this project are documented in this file.

## [3.0.0] - 2026-10-18

- Added verified compatibility with PHP 8.5 while retaining PHP 8.3 and 8.4 support.
- Upgraded to Slim 4.15, PHP-DI 7, and the WebSK 3.x dependency stack.
- Replaced annotation injection with PHP attributes and adapted service providers to mutable PHP-DI containers.
- Added strict configuration, database result, cache value, service, and entity type validation.
- Fixed entity and optional-value cache invalidation during save, rename, and delete operations.
- Added PHPUnit unit and MySQL integration tests plus PHPStan level-max analysis.
- Added strict Composer checks, archive exclusions, clean consumer verification, and the MIT license.
- Made Auth optional for consumers and added an injectable current-user identifier for change logging.

## [2.1.0] - 2025-03-30

- Updated dependency injection integration.

## [2.0.0] - 2025-03-26

- Raised the supported PHP version to PHP 8.3.
- Updated the WebSK dependency stack.

## [1.4.0] - 2022-02-13

- Updated Composer dependencies.

## [1.3.1] - 2021-07-22

- Migrated request and response handling to PSR interfaces.

## [1.3.0] - 2021-05-17

- Added Auth database migration integration.

## [1.2.2] - 2021-05-17

- Tagged the same Auth database migration state as 1.3.0.

## [1.2.1] - 2021-02-07

- Updated the package for PHP 7.4.

## [1.1.0] - 2019-09-20

- Updated CRUD integration.

## [1.0.4] - 2019-07-06

- Updated the Auth dependency.

## [1.0.3] - 2019-03-17

- Updated Composer dependencies.

## [1.0.2] - 2019-03-01

- Applied initial fixes.

## [1.0.1] - 2019-02-27

- Added the KeyValue facade.

## [1.0.0] - 2019-02-27

- Initial release.
