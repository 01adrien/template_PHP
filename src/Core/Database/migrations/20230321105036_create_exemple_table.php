<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateExempleTable extends AbstractMigration
{

    public function change()
    {
        $this
            ->table('exemple')
            ->addColumn('title', 'string')
            ->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->create();
    }
}
