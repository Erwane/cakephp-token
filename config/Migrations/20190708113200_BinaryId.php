<?php

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class BinaryId extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('token_tokens');

        $table
            ->alterColumn('id', 'string', ['limit' => 8, 'collation' => 'utf8_bin'])
            ->save();
    }

    public function down()
    {
        $table = $this->table('token_tokens');
    }
}
