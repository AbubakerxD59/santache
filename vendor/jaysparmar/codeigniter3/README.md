# CodeIgniter 3 Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jaysparmar/codeigniter3.svg?style=flat-square)](https://packagist.org/packages/jaysparmar/codeigniter3)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A package to work with CodeIgniter 3 framework.

## Installation

You can install the package via composer:

```bash
composer require jaysparmar/codeigniter3
```

## Usage

This package provides a modern way to bootstrap and work with CodeIgniter 3 framework. It includes environment management, path configuration, and CLI support.

### Basic Usage

```php
<?php
require_once 'vendor/autoload.php';

use CodeIgniter3\Bootstrap;

// Initialize CodeIgniter 3 environment
Bootstrap::init();

// Your CodeIgniter 3 application code here
// The framework is now properly initialized with:
// - Environment variables loaded from .env file
// - Error reporting configured based on environment
// - System paths properly set up
// - Constants defined
```

### CLI Usage

For command-line applications:

```php
<?php
require_once 'vendor/autoload.php';

use CodeIgniter3\Bootstrap;

// Initialize CodeIgniter 3 for CLI
Bootstrap::initCLI();

// Your CLI application code here
```

### Environment Configuration

Create a `.env` file in your project root:

```env
ENVIRONMENT=development
# or ENVIRONMENT=production
# or ENVIRONMENT=testing
```

The package will automatically load environment variables and configure error reporting accordingly.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
