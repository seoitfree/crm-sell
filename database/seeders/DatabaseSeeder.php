<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use CrmSell\Users\Domains\Entities\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'password' => bcrypt("adept1234"),
            'email' => 'test@example.com',
            'first_name' => 'Yakovenko',
            'last_name' => 'Vlad'
        ]);
        $user->save();
    }
}
