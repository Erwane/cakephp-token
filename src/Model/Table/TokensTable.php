<?php
declare(strict_types=1);

namespace Token\Model\Table;

use Cake\Chronos\Chronos;
use Cake\Database\Schema\TableSchema;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Token\Model\Entity\Token;

/**
 * Class TokensTable
 *
 * @package Token\Model\Table
 */
class TokensTable extends Table
{
    /**
     * {@inheritDoc}
     */
    protected function _initializeSchema(TableSchema $schema)
    {
        $schema->setColumnType('content', 'json');

        return $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('token_tokens')
            ->setPrimaryKey('id')
            ->addBehavior('Timestamp');
    }

    /**
     * get token by id
     * @param  string $id   token id
     * @return \Token\Model\Entity\Token|null Token entity
     */
    public function read(string $id): ?Token
    {
        // clean expired tokens first
        $this->_cleanExpired();

        // clean id
        $id = preg_replace('/^([a-f0-9]{8}).*/', '$1', $id);

        // Get token for this id
        return $this->findById($id)->first();
    }

    /**
     * create token with option
     * @param  array        $content token content (custom)
     * @param  null|date    $expire  expire date or null
     * @return string                token id
     */
    public function newToken(array $content = [], $expire = null)
    {
        $entity = $this->newEntity([
            'id' => $this->_uniqId(),
            'content' => $content,
            'expire' => is_null($expire) ? Chronos::parse('+1 day') : Chronos::parse($expire),
        ]);

        $this->save($entity);

        return $entity->id;
    }

    /**
     * generate uniq token id
     * @return string
     */
    protected function _uniqId()
    {
        $exists = true;

        $length = 8;

        do {
            // generate random
            $random = base64_encode(Security::randomBytes($length * 4));

            // cleanup
            $clean = preg_replace('/[^A-Za-z0-9]/', '', $random);

            // random part length
            $key = substr($clean, random_int(1, $length * 2), $length);
        } while ($this->exists(['id' => $key]));

        return $key;
    }

    /**
     * clean expired tokens
     * @return void
     */
    protected function _cleanExpired()
    {
        $this->deleteAll(['expire <' => \Cake\I18n\FrozenTime::now()]);
    }
}
