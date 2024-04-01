<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

// phpcs:ignore
final class CreateApplicationsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        //create the table
        // (id int, application text, environment text, version text, who text, time int)
        $table = $this->table('applications', ['id' => false, 'primary_key' => ['name', 'environment']]);
        // Phinx automatically creates `id` as primary_key
        $table->addColumn('name', 'text')
              ->addColumn('environment', 'text')
              ->addColumn('deployment_id', 'integer')
              ->addIndex(['name', 'environment'], ['unique' => true])
              ->addForeignKey(
                  'deployment_id',
                  'deployments',
                  'id',
                  ['delete' => 'NO_ACTION', 'update' => 'NO_ACTION']
              )
              ->create();
    }
}
