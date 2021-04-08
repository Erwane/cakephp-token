<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Class BinaryId
 */
class BinaryId extends AbstractMigration
{
    /**
     * Apply migrations
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('token_tokens');

        $table
            ->changeColumn('id', 'string', ['limit' => 8, 'collation' => 'utf8_bin'])
            ->save();
    }
}
