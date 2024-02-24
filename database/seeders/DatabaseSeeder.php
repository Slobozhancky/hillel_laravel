<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Цей клас дозволить нам, викликати всі створені сідери, однією командою sail a db:seed

        $this->call(PermissionAndRolesSeeder::class);
    }
}
