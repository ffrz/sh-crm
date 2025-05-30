<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'General',
            ],
            [
                'name' => 'CCTV',
            ],
            [
                'name' => 'Software',
            ],
            [
                'name' => 'POS',
            ],
            [
                'name' => 'Computer',
            ],
            [
                'name' => 'GPS',
            ],
            [
                'name' => 'Networking',
            ],
            [
                'name' => 'Printer',
            ],
            [
                'name' => 'Server',
            ],
            [
                'name' => 'Other',
            ],
        ]);
    }
}
