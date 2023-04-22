<?php


use Phinx\Seed\AbstractSeed;

class ExempleSeed extends AbstractSeed
{
    public function run()
    {
        $faker = \Faker\Factory::create('fr_FR');
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'title' => $faker->sentence(),
                'content' => $faker->text(3000),

            ];
        }
        $this
            ->table('exemple')
            ->insert($data)
            ->save();
    }
}
