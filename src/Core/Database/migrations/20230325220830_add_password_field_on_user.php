<?php

use Phinx\Migration\AbstractMigration;

class AddPasswordFieldOnUser extends AbstractMigration
{

    public function change()
    {
        $this
            ->table('user')
            ->addColumn('password', 'string', ['limit' => 20])
            ->save();
    }
}
