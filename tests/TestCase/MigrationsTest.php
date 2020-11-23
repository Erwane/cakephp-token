<?php
namespace Token\Test\TestCase;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use Migrations\Migrations;

/**
 * Class MigrationsTest
 *
 * @package Token\Test\TestCase
 */
class MigrationsTest extends TestCase
{
    public function testMigrations()
    {
        $migrations = new Migrations([
            'connection' => 'test',
        ]);
        $success = $migrations->migrate();
        self::assertTrue($success);

        $status = $migrations->status();

        self::assertCount(3, $status);

        $cnx = ConnectionManager::get('test');

        $schema = $cnx->getSchemaCollection()->describe('token_tokens');

        $columns = [
            'id' => [
                'type' => 'string',
                'length' => 8,
                'null' => false,
                'default' => null,
                'precision' => null,
                'comment' => null,
                'fixed' => null,
                'collate' => null,
            ],
            'content' => [
                'type' => 'text',
                'length' => null,
                'null' => true,
                'default' => null,
                'precision' => null,
                'comment' => null,
                'collate' => null,
            ],
            'expire' => [
                'type' => 'datetime',
                'length' => null,
                'null' => true,
                'default' => null,
                'precision' => null,
                'comment' => null,
            ],
            'created' => [
                'type' => 'datetime',
                'length' => null,
                'null' => true,
                'default' => null,
                'precision' => null,
                'comment' => null,
            ],
        ];

        foreach ($columns as $name => $expected) {
            $column = $schema->getColumn($name);
            self::assertSame($column, $expected);
        }
    }
}
