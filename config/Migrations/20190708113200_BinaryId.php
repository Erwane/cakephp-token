<?php
/**
 * @noinspection PhpUnused
 * phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */
declare(strict_types=1);

use Migrations\BaseMigration;

/**
 * Class BinaryId
 */
class BinaryId extends BaseMigration
{
    /**
     * Apply migrations
     *
     * @return void
     */
    public function up(): void
    {
        $table = $this->table('token_tokens');

        $table
            ->changeColumn('id', 'string', ['limit' => 8, 'collation' => 'utf8_bin'])
            ->save();
    }
}
