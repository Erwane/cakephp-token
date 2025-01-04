<?php
declare(strict_types=1);

namespace Token\Test\TestCase;

use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Token\Token;

/**
 * Token tests
 */
#[UsesClass(Token::class)]
#[CoversClass(Token::class)]
class TokenTest extends TestCase
{
    public array $fixtures = [
        'plugin.Token.Tokens',
    ];

    public function testGetTable()
    {
        $table = Token::getTable();

        $this->assertInstanceOf('Token\Model\Table\TokensTable', $table);
    }

    public function testGetExpired()
    {
        $token = Token::get('abcde456');
        $this->assertNull($token);
    }

    public function testGetData()
    {
        $token = Token::get('abcde789');
        $this->assertCount(3, $token->content);
        $this->assertArrayHasKey('email', $token->content);
        $this->assertSame('erwane@phea.fr', $token->content['email']);
    }

    public function testReadData()
    {
        /** @noinspection PhpDeprecationInspection */
        $token = Token::read('abcde789');
        $this->assertCount(3, $token->content);
        $this->assertArrayHasKey('email', $token->content);
        $this->assertSame('erwane@phea.fr', $token->content['email']);
    }

    public function testGenerate()
    {
        // no data at all
        $id = Token::generate();
        $token = Token::get($id);
        $this->assertSame($token->expire->toDateString(), date('Y-m-d', strtotime('now + 1 day')));
        $this->assertEmpty($token->content);

        // content as array
        $id = Token::generate([
            'model' => 'Users',
            'model_id' => 1,
            'type' => 'accountValidation',
        ]);
        $token = Token::get($id);
        $this->assertCount(3, $token->content);
        $this->assertArrayHasKey('model', $token->content);
    }

    public function testDelete()
    {
        // expired token
        $result = Token::delete('abcde456');
        $this->assertFalse($result);

        // exist token
        $result = Token::delete('abcde789');
        $this->assertTrue($result);

        // Check if deleted
        $token = Token::get('abcde789');
        $this->assertNull($token);
    }
}
