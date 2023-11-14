<?php
declare(strict_types=1);

namespace Token\Model\Table;

use Cake\Chronos\Chronos;
use Cake\Database\Schema\TableSchemaInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Exception;
use Token\Model\Entity\Token;
use function Cake\Core\deprecationWarning;

/**
 * Class TokensTable
 *
 * @package Token\Model\Table
 * @method findById(string|string[]|null $id)
 */
class TokensTable extends Table
{
    /**
     * @inheritDoc
     */
    public function getSchema(): TableSchemaInterface
    {
        return parent::getSchema()
            ->setColumnType('content', 'json');
    }

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('token_tokens')
            ->setPrimaryKey('id')
            ->addBehavior('Timestamp');
    }

    /**
     * Get token by id
     *
     * @param  string $id Token id
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
     * Create token with content
     *
     * @param  array $content Token content as array
     * @param  \DateTimeInterface|string|null $expire Expire date or null
     * @param int $tokenLength character length of the token
     * @return string Token string id
     */
    public function generate(array $content = [], $expire = null, int $tokenLength = 8): string
    {
        $entity = $this->newEntity([
            'id' => $this->_uniqId($tokenLength),
            'content' => $content,
            'expire' => is_null($expire) ? Chronos::parse('+1 day') : Chronos::parse($expire),
        ]);

        $this->save($entity);

        return $entity->id;
    }

    /**
     * Alias for generate
     *
     * @param  array $content Token content as array
     * @param  \DateTimeInterface|string|null $expire Expire date or null
     * @return string Token string id
     * @throws \Exception
     * @deprecated Use TokensTable::generate
     * @codeCoverageIgnore
     * @noinspection PhpUnused
     */
    public function newToken(array $content = [], $expire = null): string
    {
        deprecationWarning('TokensTable::newToken() is deprecated. Use TokensTable::generate().');

        return $this->generate($content, $expire);
    }

    /**
     * Generate uniq token id
     *
     * @param int $length character length of the token
     * @return string
     */
    protected function _uniqId(int $length): string
    {
        $length = ($length > 0 && $length <= 32) ? $length : 8;

        do {
            // generate random
            $random = base64_encode(Security::randomBytes($length * 4));

            // cleanup
            $clean = preg_replace('/[^A-Za-z0-9]/', '', $random);

            // @codeCoverageIgnoreStart
            try {
                $randomInt = random_int(1, $length * 2);
            } catch (Exception $exception) {
                $randomInt = mt_rand(1, $length * 2);
            }
            // @codeCoverageIgnoreEnd

            // random part length
            $key = substr($clean, $randomInt, $length);
        } while ($this->exists(['id' => $key]));

        return $key;
    }

    /**
     * clean expired tokens
     * @return void
     */
    protected function _cleanExpired()
    {
        $this->deleteAll(['expire <' => FrozenTime::now()]);
    }
}
