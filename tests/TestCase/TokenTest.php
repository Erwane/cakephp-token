<?php
declare(strict_types=1);

namespace Token\Test\TestCase;

use Cake\TestSuite\TestCase;
use Token\Token;

/**
 * Class TokenTest
 *
 * @package Token\Test\TestCase
 * @coversDefaultClass \Token\Token
 */
class TokenTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Token.Tokens',
    ];

    /**
     * @test
     * @covers ::getTable
     */
    public function testGetTable()
    {
        $table = Token::getTable();

        self::assertInstanceOf('Token\Model\Table\TokensTable', $table);
    }

    /**
     * @test
     * @covers ::get
     */
    public function testGetExpired()
    {
        $token = Token::get('abcde456');
        self::assertNull($token);
    }

    /**
     * @test
     * @covers ::get
     */
    public function testGetData()
    {
        $token = Token::get('abcde789');
        self::assertCount(3, $token->content);
        self::assertArrayHasKey('email', $token->content);
        self::assertSame('erwane@phea.fr', $token->content['email']);
    }

    /**
     * @test
     * @covers ::read
     */
    public function testReadData()
    {
        /** @noinspection PhpDeprecationInspection */
        $token = Token::read('abcde789');
        self::assertCount(3, $token->content);
        self::assertArrayHasKey('email', $token->content);
        self::assertSame('erwane@phea.fr', $token->content['email']);
    }

    /**
     * @test
     * @covers ::generate
     */
    public function testGenerate()
    {
        // no data at all
        $id = Token::generate();
        $token = Token::get($id);
        self::assertSame($token->expire->toDateString(), date('Y-m-d', strtotime('now + 1 day')));
        self::assertEmpty($token->content);

        // content as array
        $id = Token::generate([
            'model' => 'Users',
            'model_id' => 1,
            'type' => 'accountValidation',
        ]);
        $token = Token::get($id);
        self::assertCount(3, $token->content);
        self::assertArrayHasKey('model', $token->content);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function testDelete()
    {
        // expired token
        $result = Token::delete('abcde456');
        self::assertFalse($result);

        // exist token
        $result = Token::delete('abcde789');
        self::assertTrue($result);

        // Check if deleted
        $token = Token::get('abcde789');
        self::assertNull($token);
    }
}
