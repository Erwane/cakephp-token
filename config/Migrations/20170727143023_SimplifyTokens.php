<?php
declare(strict_types=1);

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

/**
 * Class SimplifyTokens
 */
class SimplifyTokens extends AbstractMigration
{
    public $autoId = false;

    /**
     * Migration up
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('token_tokens');

        $table
            ->removeIndex(['scope', 'scope_id'])
            ->removeColumn('scope')
            ->removeColumn('scope_id')
            ->removeColumn('type')
            ->update();
    }

    /**
     * Migration down
     *
     * @return void
     */
    public function down()
    {
        $table = $this->table('token_tokens');

        $table
            ->addColumn('scope', 'string', [ 'limit' => 50, 'default' => null, 'null' => true, ])
            ->addColumn('scope_id', 'integer', [ 'signed' => false, 'limit' => MysqlAdapter::INT_REGULAR, 'default' => null, 'null' => true, ])
            ->addColumn('type', 'string', [ 'limit' => 64, 'null' => true, ])
            ->addIndex(['scope', 'scope_id'])
            ->save();
    }
}
