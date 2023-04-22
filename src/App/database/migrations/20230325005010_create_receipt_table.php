<?php

use Phinx\Migration\AbstractMigration;

class CreateReceiptTable extends AbstractMigration
{
    public function change()
    {
        $this
            ->table('receipt')
            ->addColumn('file_name', 'string', ['limit' => 100])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('user_id', 'integer')
            ->addColumn('transaction_id', 'integer')
            ->create();
    }
}
