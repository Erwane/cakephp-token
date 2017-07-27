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

    public function testReadValue()
    {
        $entity = $this->Tokens->read('abcde789');
        $this->assertCount(2, $entity->value);
        $this->assertArrayHasKey('email', $entity->value);
        $this->assertSame('erwane@phea.fr', $entity->value['email']);
    }
}
