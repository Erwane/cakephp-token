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

### Delete token
Tokens deletion can be ignored, they will be destroyed on expire, but sometime you need to delete one token immediately
```php
/**
 * delete token from id or entity
 * @param string|Token $token   token string id or token entity
 * @return bool                 true if token was deleted
 */
$result = \Token\Token::delete($token);
```

### Auto cleaning
Each time a token is read, expired tokens are pruned
