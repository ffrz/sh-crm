<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Interaction::factory(522)->create();
    }
}
