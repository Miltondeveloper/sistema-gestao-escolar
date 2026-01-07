<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Importe a classe do módulo
use Modules\AccessControl\Infrastructure\Database\Seeders\AccessControlSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Chama o seeder do nosso módulo
        $this->call([
            AccessControlSeeder::class,
        ]);
    }
}