<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Super Admins
         * ==============
         */
        $admins = [
            [
                'email'    => 'admin1@tes.com',
                'password' => Crypt::encrypt('admin1'),
                'name'     => 'admin 1',
                'level_id' => 1,
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate($admin);
        }
    }
}
