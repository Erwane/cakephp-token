<?php
declare(strict_types=1);

namespace Token\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Token\Model\Entity\Token;

/**
 * Class TokensTableTest
 *
 * @package Token\Test\TestCase\Model\Table
 * @coversDefaultClass \Token\Model\Table\TokensTable
 */
class TokensTableTest extends TestCase
{
    /**
     * Table
     *
     * @var \Token\Model\Table\TokensTable
     */
    public $table;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Token.Tokens',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->table = $this->getTableLocator()->get('Token.Tokens');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->table);

        parent::tearDown();
    }

    /**
     * @test
     * @covers ::_initializeSchema
     */
    public function testSchema()
    {
        $schema = $this->table->getSchema();
        self::assertSame('json', $schema->getColumnType('content'));
    }

    /**
     * @test
     * @covers ::initialize
     */
    public function testInitialize()
    {
        self::assertSame('token_tokens', $this->table->getTable());
        self::assertSame('id', $this->table->getPrimaryKey());
        self::assertTrue($this->table->hasBehavior('Timestamp'));
    }

    /**
     * @test
     * @covers ::read
     * @covers ::_cleanExpired
     */
    public function testReadExpired()
    {
        $entity = $this->table->read('abcde456');
        self::assertNull($entity);
    }

    /**
     * @test
     * @covers ::read
     */
    public function testReadExists()
    {
        $entity = $this->table->read('abcde123');
        self::assertInstanceOf(Token::class, $entity);
        self::assertSame('abcde123', $entity->id);
    }

    /**
     * @test
     * @covers ::read
     */
    public function testReadExistsBinary()
    {
        $entity = $this->table->read('abcdE123');
        self::assertNull($entity);
    }

    /**
     * @test
     * @covers ::read
     */
    public function testReadContent()
    {
        $entity = $this->table->read('abcde789');
        self::assertCount(3, $entity->content);
        self::assertArrayHasKey('email', $entity->content);
        self::assertSame('erwane@phea.fr', $entity->content['email']);
    }

    /**
     * @test
     * @covers ::generate
     * @covers ::_uniqId
     */
    public function testGenerateWithNoData()
    {
        // no data at all
        $id = $this->table->generate();

        /** @var \Token\Model\Entity\Token $entity */
        $entity = $this->table->get($id);

        self::assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 1 day')));
        self::assertEmpty($entity->content);
    }

    /**
     * @test
     * @covers ::generate
     */
    public function testGenerateExpire3Days()
    {
        // // expire in 3 days
        $id = $this->table->generate([], '+3 days');

        /** @var \Token\Model\Entity\Token $entity */
        $entity = $this->table->get($id);

        self::assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 3 day')));
    }

    /**
     * @test
     * @covers ::generate
     */
    public function testGenerateWithData()
    {
        // content as array
        $id = $this->table->generate([
            'model' => 'Users',
            'model_id' => 1,
            'type' => 'accountValidation',
        ]);

        /** @var \Token\Model\Entity\Token $entity */
        $entity = $this->table->get($id);

        self::assertCount(3, $entity->content);
        self::assertArrayHasKey('model', $entity->content);
    }

    /**
     * @test
     * @covers ::generate
     */
    public function testGenerateWithLength()
    {
        // content as array
        $id = $this->table->generate(['model' => 'Users'], null, 32);

        self::assertEquals(32, strlen($id));
    }
}