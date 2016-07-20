<?php

namespace Token\Utility;

use Token\Model\Table\TokensTable;
use Cake\ORM\TableRegistry;

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
    static public function generate($scope = null, $scopeId = null, $type = null, $expire = null, array $value = [])
    {
        $Tokens = TableRegistry::get('Token.Tokens');
        return $Tokens->newToken($scope, $scopeId, $type, $expire, $value);
    }
}
