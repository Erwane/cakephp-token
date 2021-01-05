<?php
declare(strict_types=1);

namespace Token;

use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use Token\Model\Entity\Token as TokenEntity;

/**
 * Class Token
 *
 * @package Token
 */
class Token
{
    /**
     * Get Tokens table
     *
     * @return \Token\Model\Table\TokensTable|\Cake\ORM\Table
     */
    public static function getTable(): Table
    {
        $locator = new TableLocator();

        return $locator->get('Token.Tokens');
    }

    /**
     * Create a token with data and return the id
     *
     * @param  array  $content Token content as array
     * @param  string|null $expire Expire exprimed in '+6 days +2 hours' format
     * @return string Token id
     */
    public static function generate(array $content = [], string $expire = null): string
    {
        return self::getTable()->newToken($content, $expire);
    }

    /**
     * Read token from id
     *
     * @param  string $id Token string id
     * @return \Token\Model\Entity\Token|null Entity
     * @deprecated Use Token::get(string $id)
     */
    public static function read(string $id): ?TokenEntity
    {
        return self::getTable()->read($id);
    }

    /**
     * Read token from id
     *
     * @param  string $id Token string id
     * @return \Token\Model\Entity\Token|null Entity
     */
    public static function get(string $id): ?TokenEntity
    {
        return self::getTable()->read($id);
    }

    /**
     * Delete token
     *
     * @param  \Token\Model\Entity\Token|string $token Token entity or string id
     * @return bool
     */
    public static function delete($token): bool
    {
        if (is_string($token)) {
            $token = self::get($token);
        }

        if (!($token instanceof TokenEntity)) {
            return false;
        }

        return self::getTable()->delete($token);
    }
}
