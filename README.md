# CakePHP 3 Token plugin

## Description
This is a really "simple to use" cakephp 3 plugin for generating and reading temporaries tokens

## Installation
```bash
composer require erwane/cakephp-token:~1
composer update
bin/cake migrations migrate -p Token
```

## Usage

### Generate a token

```php
/**
 * Create a token with data and return the id
 * @param  array  $content   an array of custom data
 * @param  string $expire    expire exprimed in '+6 days +2 hours' format
 * @return string            The token id
 */

$myNewTokenId = \Token\Token::generate(array $content, $expire);
```

### Read token
```php
// return false (expired or not found) or Token entity
$token = \Token\Token::read($tokenId);
```

### Auto cleaning
Each time a token is read, expired tokens are pruned before
