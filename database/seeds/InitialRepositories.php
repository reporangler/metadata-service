<?php

use App\Model\Repository;
use Illuminate\Database\Seeder;

class InitialRepositories extends Seeder
{
    public function run()
    {
        $repositories = ['php', 'npm', 'pypi', 'go'];

        foreach ($repositories as $name) {
            Repository::firstOrCreate(['name' => $name]);
        }
    }
}
