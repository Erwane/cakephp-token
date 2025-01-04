<?php
declare(strict_types=1);

namespace Token\Test\TestCase\Model\Table;

use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Token\Model\Entity\Token;
use Token\Model\Table\TokensTable;

/**
 * TokensTable tests
 */
#[UsesClass(TokensTable::class)]
#[CoversClass(TokensTable::class)]
class TokensTableTest extends TestCase
{
    /**
     * Table
     *
     * @var \Token\Model\Table\TokensTable
     */
    public TokensTable|Table $table;

    public array $fixtures = [
        'plugin.Token.Tokens',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->table = $this->getTableLocator()->get('Token.Tokens');
    }

    protected function tearDown(): void
    {
        unset($this->table);

        parent::tearDown();
    }

    public function testSchema()
    {
        $schema = $this->table->getSchema();
        $this->assertSame('json', $schema->getColumnType('content'));
    }

    public function testInitialize()
    {
        $this->assertSame('token_tokens', $this->table->getTable());
        $this->assertSame('id', $this->table->getPrimaryKey());
        $this->assertTrue($this->table->hasBehavior('Timestamp'));
    }

    public function testReadExpired()
    {
        $entity = $this->table->read('abcde456');
        $this->assertNull($entity);
    }

    public function testReadExists()
    {
        $entity = $this->table->read('abcde123');
        $this->assertInstanceOf(Token::class, $entity);
        $this->assertSame('abcde123', $entity->id);
    }

    public function testReadExistsBinary()
    {
        $entity = $this->table->read('abcdE123');
        $this->assertNull($entity);
    }

    public function testReadContent()
    {
        $entity = $this->table->read('abcde789');
        $this->assertCount(3, $entity->content);
        $this->assertArrayHasKey('email', $entity->content);
        $this->assertSame('erwane@phea.fr', $entity->content['email']);
    }

    public function testGenerateWithNoData()
    {
        // no data at all
        $id = $this->table->generate();

        /** @var \Token\Model\Entity\Token $entity */
        $entity = $this->table->get($id);

        $this->assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 1 day')));
        $this->assertEmpty($entity->content);
    }

    public function testGenerateExpire3Days()
    {
        // // expire in 3 days
        $id = $this->table->generate([], '+3 days');

        /** @var \Token\Model\Entity\Token $entity */
        $entity = $this->table->get($id);

        $this->assertSame($entity->expire->toDateString(), date('Y-m-d', strtotime('now + 3 day')));
    }

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

        $this->assertCount(3, $entity->content);
        $this->assertArrayHasKey('model', $entity->content);
    }

    public function testGenerateWithLength()
    {
        // content as array
        $id = $this->table->generate(['model' => 'Users'], null, 32);

        $this->assertEquals(32, strlen($id));
    }
}
