<?php
declare(strict_types=1);

namespace Token\Test\TestCase;

use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use Exception;
use Migrations\Migrations;

/**
 * Class MigrationsTest
 *
 * @package Token\Test\TestCase
 */
class MigrationsTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();

        if (is_file(TMP . 'test_token')) {
            unlink(TMP . 'test_token');
        }
    }

    /**
     * @test
     */
    public function testMigrations()
    {
        ConnectionManager::setConfig('migration', ['url' => 'sqlite:///' . TMP . 'test_token']);
        $cnx = ConnectionManager::get('migration');

        // Cleanup database before migrations
        try {
            $tables = $cnx->getSchemaCollection()->listTables();
            foreach ($tables as $table) {
                $sql = $cnx->getDriver()->newTableSchema($table)->dropSql($cnx);
                foreach ($sql as $stmt) {
                    $cnx->execute($stmt)->closeCursor();
                }
            }
        } catch (Exception $e) {
            self::assertFalse(true, "Can't cleanup database");
        }

        $migrations = new Migrations([
            'plugin' => 'Token',
            'connection' => 'migration',
        ]);
        $success = $migrations->migrate();
        self::assertTrue($success);

        $status = $migrations->status();
        self::assertCount(4, $status);

        $schema = $cnx->getSchemaCollection()->describe('token_tokens');

        // Columns
        self::assertSame(['id', 'content', 'expire', 'created'], $schema->columns());

        // Describe
        $columns = [
            'id' => [
                'type' => 'string',
                'length' => 32,
                'null' => false,
                'default' => null,
                'precision' => null,
                'comment' => null,
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
                'null' => false,
                'default' => null,
                'precision' => null,
                'comment' => null,
            ],
            'created' => [
                'type' => 'datetime',
                'length' => null,
                'null' => false,
                'default' => null,
                'precision' => null,
                'comment' => null,
            ],
        ];

        foreach ($columns as $name => $expected) {
            $column = $schema->getColumn($name);
            self::assertSame($column, $expected, "Field `$name`: comparison fail");
        }
    }
}