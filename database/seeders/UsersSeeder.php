<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Seed users table. Admin User. Fernando Carrasco 2021-09-24.
         */
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'registro manual',
            'email' => 'admin@admin.cc',
            'email_verified_at' => NULL,
            'password' => '$2y$10$YqLS1SpCTgBq8alQBIajLuvwH8Z6Z7xM6mac6v9Z4dI.k6HF7GU.K',
            'google_id' => NULL,
            'google_picture' => NULL,
            'remember_token' => 'Wzyo4uBLdOmzoqiYVIU0EQIuetqFJBP3Mw743sJ4bXqIqsJqeoJf2hsnRsjO',
            'created_at' => '2023-06-02 21:11:38',
            'updated_at' => '2023-06-02 21:11:38',
        ]);

    }
}
