# Platform

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oengine/platform.svg?style=flat-square)](https://packagist.org/packages/oengine/platform)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/oengine/platform/run-tests?label=tests)](https://github.com/oengine/platform/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/oengine/platform/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/oengine/platform/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/oengine/platform.svg?style=flat-square)](https://packagist.org/packages/oengine/platform)

OEngine\Platform is a PHP library for platforming module,plugin,theme that can be easily configured and extended.

## Requirements

PHP 7.1+

## Installation

You can install package via composer

```bash
$ composer require oengine/platform
```

## Usage
Make module:

```bash
$ php artisan platform:make Demo3 -a true -t module -f true
```

Make plugin:

```bash
$ php artisan platform:make Demo3 -a true -t plugin -f true
```

Make theme:

```bash
$ php artisan platform:make Demo3 -a true -t theme -f true
```
## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](./LICENSE.md)
