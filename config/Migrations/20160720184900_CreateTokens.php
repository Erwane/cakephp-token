<?php

use Migrations\AbstractMigration;

class CreateTokens extends AbstractMigration
{

    public $autoId = false;

    public function change()
    {
        $table = $this->table('tokens');
        $table->addColumn('id', 'string', [
            'limit' => 12,
            'null' => false,
            ])
        ->addColumn('scope', 'string', [
            'limit' => 50,
            'default' => null,
            'null' => true,
            ])
        ->addColumn('scope_id', 'integer', [
            'signed' => false,
            'limit' => 11,
            'default' => null,
            'null' => true,
            ])
        ->addColumn('type', 'string', [
            'limit' => 64,
            'null' => true,
            ])
        ->addColumn('content', 'text', [
            'null' => true,
            ])
        ->addColumn('expire', 'datetime', [
            'null' => false,
            ])
        ->addColumn('created', 'datetime', [
            'null' => false,
            ])
        ->addPrimaryKey([
            'id',
            ])
        ->addIndex(['scope', 'scope_id'])
        ->create();
    }
}
