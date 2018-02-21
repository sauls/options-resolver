# Sauls OptionsResolver

[![Build Status](https://travis-ci.org/sauls/options-resolver.svg?branch=master)](https://travis-ci.org/sauls/options-resolver)
[![Packagist](https://img.shields.io/packagist/v/sauls/options-resolver.svg)](https://packagist.org/packages/sauls/options-resolver)
[![Total Downloads](https://img.shields.io/packagist/dt/sauls/options-resolver.svg)](https://packagist.org/packages/sauls/options-resolver)
[![Coverage Status](https://img.shields.io/coveralls/github/sauls/options-resolver.svg)](https://coveralls.io/github/sauls/options-resolver?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sauls/options-resolver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sauls/options-resolver/?branch=master)
[![License](https://img.shields.io/github/license/sauls/options-resolver.svg)](https://packagist.org/packages/sauls/options-resolver)

[Symfony OptionsResolver](https://symfony.com/doc/current/components/options_resolver.html) with multi dimensional array support

## Requirements

PHP >= 7.2

## Installation

### Using composer
```bash
$ composer require sauls/options-resolver
```
### Apppend the composer.json file manually
```json
{
    "require": {
        "sauls/options-resolver": "^1.0"
    }
}
```

## Usage

Standard usage can be found at [Symfony OptionsResolver official documentation](https://symfony.com/doc/current/components/options_resolver.html). 

The associative array support is added by using a `dot notation` array indexes.

### Defining options

To define the associative options, use `dot notated` array indexes.

```php
$resolver->setDefined['nested.name', 'nested.value', 'nested.deep.type'];
```

### Adding allowed types

Allowed types are added using `dot notation` index.

```php
$resolver->addAllowedType('nested.name', ['string']);
$resolver->addAllowedType('nested.value', ['int']);
```

### Adding allowed values

Allowed values are added using `dot notation` index.

```php
$resolver->addAllowedValues('nested.deep.type', ['one', 'two', 'three']);
```

### Default option values

Default options can be added as `dot notation` index or `associative array`.

```php

$resolver->setDefaults(
    [
        'nested.name' => 'Hello world!',
        'nested.value' => 100,
        'nested.deep.type' => 'one',
    ]
);

// Or

$resolver->setDefaults(
    [
        'nested' => [
            'name' => 'Hello world!',
            'value' => 100,
            'deep' => [
                'type' => 'one',
            ],
        ],
    ]
);
```

### Resolving options

Passing array to resolve options can contain either the `dot notation` indexes or `associative` array.

```php

$resolver->resolve(
    [
        'nested.name' => 'Resolve me!',
        'nested.value' => 500,
        'nested.deep.type' => 'two',
    ]
);

// Or

$resolver->resolve(
    [
        'nested' => [
            'name' => 'Resolve me!',
            'value' => 500,
            'deep' => [
                'type' => 'two',
            ],
        ]
    ]
);
```

## Exceptions

All exceptions will return what is wrong with your associative option using `dot notation`. For example:

> The option "nested.deep.type" with value "four" is invalid. Accepted values are: "one", "two", "three".

> The option "nested.value" with value "wrong" is expected to be of type "int", but is of type "string".

> The required option "nested.value" is missing.

> The option "nested.deep.nmae" does not exist. Defined options are: "nested.deep.name", "nested.name", "nested.type", "nested.value", "text", "type".
