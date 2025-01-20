<?php

use App\Models\UserLevel;
use Illuminate\Database\Seeder;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            [
                'id'   => 1,
                'name' => 'superadmin'
            ],
        ];

        foreach ($levels as $level) {
            UserLevel::firstOrCreate($level);
        }
    }
}
