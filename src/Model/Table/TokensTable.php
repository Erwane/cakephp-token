<?php

namespace Token\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Chronos\Chronos;

class TokensTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
    }

    public function newToken($scope, $scopeId, $type, $expire = null, array $value = [])
    {
        $entity = $this->newEntity([
            'id' => $this->uniqId(),
            'scope' => $scope,
            'scope_id' => $scopeId,
            'type' => $type,
            'content' => json_encode($value),
            'expire' => is_null($expire) ? Chronos::now() : Chronos::parse($expire),
        ]);

        $this->save($entity);

        return $entity->id;
    }

    protected function uniqId()
    {
        $exists = true;
        while($exists) {
            $key = $this->generateKey();
            $exists = $this->find()->where(['id' => $key])->first();
        }

        return $key;
    }

    protected function generateKey()
    {
        return substr( hash( 'sha256', Text::uuid() ), 0, 8);
    }
}
