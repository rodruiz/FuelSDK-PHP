# Unreleased

These changes are maintained on the fork's `php8` branch and are installable through Composer as `salesforce-mc/fuel-sdk-php:dev-php8`. The fork retains the original Composer package name and is not published as a separate Packagist package.

### Fixed

* Made `ET_Client::__doRequest()` compatible with the optional `uriParserClass` parameter added to `SoapClient::__doRequest()` in PHP 8.5.

### Changed

* Added support for PHP 8.1 through PHP 8.4.
* Standardized all source, test, and sample PHP files on PSR-12 formatting.
* Added PHP-CS-Fixer configuration and Composer commands for applying and checking code style.
* Removed the legacy handwritten autoloader; Composer is now the only supported installation and autoloading path.
* Updated all bundled samples to load Composer directly instead of depending on the PHPUnit bootstrap file.
* Updated PHPUnit to 12.5 and migrated test initialization to PHPUnit's `setUp()` lifecycle method.
* Updated `firebase/php-jwt` to 7.x and migrated JWT decoding to its key-based API.
* Updated the WS-Security dependencies and corrected the PHP 8-compatible `SoapClient::__doRequest()` signature.
* Removed obsolete PHPDocumentor and Dompdf development dependencies from the test dependency graph.
* Regenerated the Composer lockfile with maintained, PHP 8-compatible dependencies.
* Preserved the original `salesforce-mc/fuel-sdk-php` Composer identity and `FuelSdk` PHP namespace for application compatibility.
* Updated package metadata, repository links, installation instructions, and the SDK user-agent identifier for the `php8` branch.

### Tests

* Added credential-free cache tests to the default PHPUnit suite.
* Marketing Cloud integration tests now report themselves as skipped when `config.php` is absent instead of failing during client construction.
* Documented installation, unit-test, integration-test, test-discovery, and troubleshooting commands in the README.

# 1.1.0 (2017-10-19)
* namespace integration in all source, test and sample code
* composer autoload issue fix
* newly supported objects:
    - Result Message
    - Data Extract
    - Triggered Send Summary

# 1.0.0 (2017-07-18)

### New Features 

* **mcrypt :** mcrypt dependency removed.
* **proxy :** added proxy server support.
* **jwt :** jwt.php is removed from project source structure and added as dependency.
* **soap-wsse :** soap-wsse.php is removed from project source structure and added as dependency.
* **code refactor :** code refactored to individual class files. (under src/ directory)
* **unit test :** added unit test cases (happy path for now) using phpunit testing framework. (under tests/ directory)
* **API docs :** added API documentation using phpdocumentor framework. (under docs/ directory)
* **auto loader :** integrated auto loader (spl_autoload_register) for all source code under src/, tests/, objsamples/ directory.
