# Token plugin for CakePHP

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![codecov](https://codecov.io/gh/Erwane/cakephp-token/branch/3.x/graph/badge.svg?token=Ai4gc1GP9P)](https://codecov.io/gh/Erwane/cakephp-token)
[![Build Status](https://github.com/Erwane/cakephp-token/actions/workflows/ci.yml/badge.svg?branch=3.x)](https://github.com/Erwane/cakephp-contact/actions)
[![Packagist Downloads](https://img.shields.io/packagist/dt/Erwane/cakephp-token)](https://packagist.org/packages/Erwane/cakephp-token)
[![Packagist Version](https://img.shields.io/packagist/v/Erwane/cakephp-token)](https://packagist.org/packages/Erwane/cakephp-token)

## Version map

| branch | CakePHP core | PHP min |
|--------|--------------|---------|
| 1.x    | ^3.0         | PHP 7.2 |
| 2.x    | ^4.0         | PHP 7.4 |
| 3.x    | ^5.0         | PHP 8.1 |

## Description
This is a really "simple to use" cakephp 4 plugin for generating and reading temporaries tokens

## Installation
```bash
composer require erwane/cakephp-token
bin/cake migrations migrate -p Token
```

## Usage

### Generate a token
```php
/**
 * Create a token with data and return the id
 * @param  array  $content   an array of custom data
 * @param  string $expire    expire exprimed in '+6 days +2 hours' format
 * @param  int $length Token length
 * @return string            The token id
 */

$myNewTokenId = \Token\Token::generate(array $content, $expire, 8);
```

### Get token
```php
// return null (expired or not found) or Token entity
$token = \Token\Token::get($tokenId);
```

### Delete token
Tokens deletion can be ignored, they will be destroyed on expire, but sometime you need to delete one token immediately
```php
/**
 * Delete token from id or entity
 * 
 * @param \Token\Model\Entity\Token|string $token Token entity or id
 * @return bool True if token was deleted
 */
$result = \Token\Token::delete($token);
```

### Auto cleaning
Each time a token is read, expired tokens are pruned
