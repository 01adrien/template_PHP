<?php

use Phinx\Migration\AbstractMigration;

class CreateUser extends AbstractMigration
{


    public function change()
    {
        $this
            ->table('user')
            ->addColumn('name', 'string', ['limit' => 30])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('category_id', 'integer')
            ->create();

        $this
            ->table('transaction')
            ->addColumn('description', 'string', ['limit' => 500])
            ->addColumn('amount', 'decimal')
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('category_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->create();

        $this
            ->table('transactionCategory')
            ->addColumn('name', 'string', ['limit' => 30])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
