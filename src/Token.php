<?php
namespace Token;

use Cake\ORM\TableRegistry;
use Token\Model\Table\TokensTable;

class Token
{

    /**
     * Create a token with data and return the id
     * @param  string $scope   The associated model. Can be null
     * @param  int    $scopeId The id of associated model. Can be null
     * @param  string $type    The type of token
     * @param  [type] $expire  expire exprimed in '+6 days +2 hours' format
     * @param  array  $value   an array of custom data
     * @return string          The token id
     */
    public static function generate($scope = null, $scopeId = null, $type = null, $expire = null, array $value = [])
    {
        return TableRegistry::get('Token.Tokens')->newToken($scope, $scopeId, $type, $expire, $value);
    }

    /**
     * read token from id
     * @param  string $id   token string id
     * @return Token        entity
     */
    public static function read($id)
    {
        return TableRegistry::get('Token.Tokens')->findById($id)->first();
    }
}
