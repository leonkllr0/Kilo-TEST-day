<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Boris',
                'email' => 'boris@test.lt',
                'password' => bcrypt('qwerty'),
            ],
            [
                'name' => 'User2',
                'email' => 'user2@test.lt',
                'password' => bcrypt('qwerty'),
            ],
            [
                'name' => 'User3',
                'email' => 'user3@test.lt',
                'password' => bcrypt('qwerty'),
            ],
            [
                'name' => 'User4',
                'email' => 'user4@test.lt',
                'password' => bcrypt('qwerty'),
            ],
        ]);

        DB::table('user_subscriptions')->insert([
            [
                'user_id' => 1,
                'code' => 'gkleRtgipfDs',
                'provider_name' => 'apple',

                'initiated_at' => null,
                'canceled_at' => null,
                'renewed_at' => null,

                'verification_key' => null,
            ],
        ]);
    }
}
