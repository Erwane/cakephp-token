<?php
namespace Token\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class TokensFixture extends TestFixture
{
    public $connection = 'test';
    public $table = 'token_tokens';

    /**
     * fields property
     *
     * @var array
     */
    public $fields = [
        'id' => ['type' => 'string', 'limit' => 50, 'null' => false],
        'scope' => ['type' => 'string', 'limit' => 50, 'null' => true],
        'scope_id' => ['type' => 'integer', 'null' => true],
        'type' => ['type' => 'string', 'limit' => 64, 'null' => true],
        'value' => ['type' => 'text', 'null' => true],
        'expire' => 'datetime',
        'created' => 'datetime',
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
        ]
    ];

    public function init()
    {
        $this->records = [
            // not expired, no data
            [
                'id' => 'abcde123',
                'expire' => date('Y-m-d H:i:s', strtotime('now +4 days')),
                'created' => date('Y-m-d H:i:s'),
            ],
            // expired
            [
                'id' => 'abcde456',
                'expire' => date('Y-m-d H:i:s', strtotime('now - 1 hour')),
                'created' => date('Y-m-d H:i:s'),
            ],
            // with lots of data
            [
                'id' => 'abcde789',
                'scope' => 'Users',
                'scope_id' => 1,
                'type' => 'emailValidation',
                'value' => json_encode(['email' => 'erwane@phea.fr', 'date' => date('Y-m-d H:i:s')]),
                'expire' => date('Y-m-d H:i:s', strtotime('now + 1 hour')),
                'created' => date('Y-m-d H:i:s'),
            ],
        ];

        parent::init();
    }
}
