<?php
namespace Token;

use Cake\ORM\TableRegistry;
use Token\Model\Table\TokensTable;

class Token
{

    /**
     * Create a token with data and return the id
     * @param  array  $content an array of custom data
     * @param  null|string $expire  expire exprimed in '+6 days +2 hours' format
     * @return string          The token id
     */
    public static function generate(array $content = [], $expire = null)
    {
        return TableRegistry::get('Token.Tokens')->newToken($content, $expire);
    }

    /**
     * read token from id
     * @param  string $id   token string id
     * @return Token        entity
     */
    public static function read($id)
    {
        return TableRegistry::get('Token.Tokens')->read($id);
    }

    /**
     * delete token
     * @param  string|Token $token   token string id or Token entity
     * @return Token        entity
     */
    public static function delete($token)
    {
        if (is_string($token)) {
            $token = self::read($token);
        }

        if (!($token instanceof \Token\Model\Entity\Token)) {
            return false;
        }

        return TableRegistry::get('Token.Tokens')->delete($token);
    }
}
