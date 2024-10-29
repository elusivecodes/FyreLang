# FyreLang

**FyreLang** is a free, open-source language library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Lang Creation](#lang-creation)
- [Lang Methods](#lang-methods)



## Installation

**Using Composer**

```
composer require fyre/lang
```

In PHP:

```php
use Fyre\Lang\Lang;
```


## Lang Creation

- `$paths` is an array containing the paths, and will default to *[]*.
- `$locale` is a string representing the locale, and will default to *null*.
- `$defaultLocale` is a string representing the default locale, and will default to *null*.

```php
$lang = new Lang($paths, $locale, $defaultLocale);
```


## Lang Methods

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