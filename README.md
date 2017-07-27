# CakePHP 3 Token plugin

## Description
This is a really "simple to use" cakephp 3 plugin for generating and reading temporaries tokens

## Installation
```bash
composer require erwane/cakephp-token:~1.0.0
composer update
bin/cake migrations migrate -p Token
```

## Usage

### Generate a token

```php
/**
 * Create a token with data and return the id
 * @param  string $scope   The associated model. Can be null
 * @param  int    $scopeId The id of associated model. Can be null
 * @param  string $type    The type of token (customize as you want)
 * @param  string $expire  expire exprimed in '+6 days +2 hours' format
 * @param  array  $value   an array of custom data
 * @return string          The token id
 */

$myNewTokenId = \Token\Token::generate($scope, $scopeId, $type, $expire, array $value);
```

### Read token
```php
$token = \Token\Token::read($tokenId);
```

$token will be set to false (if expired or not found) or a Token entity.

### Auto cleaning
Each time a token is read, expired tokens are pruned before
