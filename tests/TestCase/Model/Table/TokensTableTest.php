<?php
namespace Token\Test\TestCase\Model\Table;

use Token\Model\Table\TokensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Token\Model\Table\TokensTable Test Case
 */
class TokensTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Token\Model\Table\TokensTable
     */
    public $Tokens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.token.tokens',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Tokens') ? [] : ['className' => TokensTable::class];
        $this->Tokens = TableRegistry::get('Tokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tokens);

        parent::tearDown();
    }

    public function testReadExpired()
    {
        $entity = $this->Tokens->read('abcde456');
        $this->assertNull($entity);
    }

    public function testReadExists()
    {
        $entity = $this->Tokens->read('abcde123');
        $this->assertInstanceOf(\Token\Model\Entity\Token::class, $entity);
        $this->assertSame('abcde123', $entity->id);
    }

    public function testReadContent()
    {
        $entity = $this->Tokens->read('abcde789');
        $this->assertCount(3, $entity->content);
        $this->assertArrayHasKey('email', $entity->content);
        $this->assertSame('erwane@phea.fr', $entity->content['email']);
    }

    public function testGenerate()
    {
        // no data at all
        $id = $this->Tokens->newToken();
        $entity = $this->Tokens->get($id);
        $this->assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 1 day')));
        $this->assertEmpty($entity->content);

        // // expire in 3 days
        $id = $this->Tokens->newToken([], '+3 days');
        $entity = $this->Tokens->get($id);
        $this->assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 3 day')));

        // content as array
        $id = $this->Tokens->newToken([
            'model' => 'Users',
            'model_id' => 1,
            'type' => 'accountValidation',
        ]);
        $entity = $this->Tokens->get($id);
        $this->assertCount(3, $entity->content);
        $this->assertArrayHasKey('model', $entity->content);
    }
}
