# FyreLang

**FyreLang** is a free, open-source language library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/lang
```

In PHP:

```php
use Fyre\Lang\Lang;
```


## Basic Usage

- `$config` is a [*Config*](https://github.com/elusivecodes/FyreConfig).

```php
$lang = new Lang($config);
```

The default locale will be resolved from the "*App.locale*" key in the *Config*.

**Autoloading**

It is recommended to bind the *Lang* to the [*Container*](https://github.com/elusivecodes/FyreContainer) as a singleton.

```php
$container->singleton(Lang::class);
```

Any dependencies will be injected automatically when loading from the [*Container*](https://github.com/elusivecodes/FyreContainer).

```php
$lang = $container->use(Lang::class);
```


## Methods

**Add Path**

Add a language path.

- `$path` is a string representing the path to add.
- `$prepend` is a boolean indicating whether to prepend the file path, and will default to *false*.

```php
$lang->addPath($path, $prepend);
```

**Clear**

Clear all language data.

```php
$lang->clear();
```

**Get**

Get a language value.

- `$key` is a string representing the key to lookup.
- `$data` is an array containing data to insert into the language string.

```php
$lang = $lang->get($key, $data);
```

See the [*MessageFormatter::formatMessage*](https://www.php.net/manual/en/messageformatter.formatmessage.php) method for details about message formatting.

**Get Default Locale**

Get the default locale.

```php
$defaultLocale = $lang->getDefaultLocale();
```

**Get Locale**

Get the current locale.

```php
$locale = $lang->getLocale();
```

**Get Paths**

Get the paths.

```php
$paths = $lang->getPaths();
```

**Remove Path**

Remove a path.

- `$path` is a string representing the path to remove.

```php
$lang->removePath($path);
```

**Set Default Locale**

Set the default locale.

- `$locale` is a string representing the locale.

```php
$lang->setDefaultLocale($locale);
```

**Set Locale**

Set the current locale.

- `$locale` is a string representing the locale.

```php
$lang->setLocale($locale);
```