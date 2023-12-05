<?php

namespace Database\Seeders;

use CrmSell\Users\Domains\Entities\Role;
use Illuminate\Database\Seeder;
use CrmSell\Users\Domains\Entities\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    private string $description = "php artisan db:seed --class=DatabaseSeeder";
    /**
     * Seed the application"s database.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'password' => bcrypt("adept1234567"),
                'email' => 'test@example.com',
                'first_name' => 'Yakovenko',
                'last_name' => 'Vlad'
            ]);
            $user->save();

            $role = Role::where('name', 'Admin')->first();
            $user->assignRole($role);

            DB::commit();
        } catch (\Exception $e) {
            Log::warning($e->getMessage() . " " . $e->getTraceAsString());

            DB::rollBack();
        }

    }
}
