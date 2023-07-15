# FyreLang

**FyreLang** is a free, open-source language library for *PHP*.


## Table Of Contents
- [Installation](#installation)
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


## Methods

**Add Path**

Add a language path.

- `$path` is the path to add.
- `$prepend` is a boolean indicating whether to prepend the file path, and will default to *false*.

```php
Lang::addPath($path, $prepend);
```

**Clear**

Clear all language data.

```php
Lang::clear();
```

**Clear Paths**

Clear the paths.

```php
Lang::clearPaths();
```

**Get**

Get a language value.

- `$key` is the key to lookup.
- `$data` is an array containing data to insert into the language string.

```php
$lang = Lang::get($key, $data);
```

See the [*MessageFormatter::formatMessage*](https://www.php.net/manual/en/messageformatter.formatmessage.php) method for details about message formatting.

**Get Default Locale**

Get the default locale.

```php
$defaultLocale = Lang::getDefaultLocale();
```

**Get Locale**

Get the current locale.

```php
$locale = Lang::getLocale();
```

**Get Paths**

Get the paths.

```php
$paths = Lang::getPaths();
```

**Set Default Locale**

Set the default locale.

- `$locale` is the locale.

```php
Lang::setDefaultLocale($locale);
```

**Remove Path**

Remove a path.

- `$path` is the path to remove.

```php
$removed = Lang::removePath($path);
```

**Set Locale**

Set the current locale.

- `$locale` is the locale.

```php
Lang::setLocale($locale);
```