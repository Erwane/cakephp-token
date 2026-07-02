<?php
/**
 * @noinspection PhpUnused
 * phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */
declare(strict_types=1);

use Migrations\BaseMigration;
use Migrations\Db\Adapter\MysqlAdapter;

/**
 * Class CreateTokens
 */
class CreateTokens extends BaseMigration
{
    public bool $autoId = false;

    /**
     * Apply migrations
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('token_tokens');

        $table
            ->addColumn('id', 'string', ['limit' => 12, 'null' => false,])
            ->addColumn('scope', 'string', ['limit' => 50, 'default' => null, 'null' => true,])
            ->addColumn(
                'scope_id', 'integer',
                ['signed' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'default' => null, 'null' => true,],
            )
            ->addColumn('type', 'string', ['limit' => 64, 'null' => true,])
            ->addColumn('content', 'text', ['null' => true,])
            ->addColumn('expire', 'datetime', ['null' => false,])
            ->addColumn('created', 'datetime', ['null' => false,])
            ->addPrimaryKey(['id',])
            ->addIndex(['scope', 'scope_id'])
            ->create();
    }
}
