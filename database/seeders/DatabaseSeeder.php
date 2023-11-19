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
        // \App\Models\User::factory(10)->create();

        $user = User::create([
            'name' => 'Test User',
            'password' => bcrypt("test1234"),
            'email' => 'test@example.com',
        ]);
        $user->save();
    }
}
