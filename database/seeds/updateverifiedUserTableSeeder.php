<?php

use Illuminate\Database\Seeder;

class updateverifiedUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = DB::table('users')->where('email', 'test_user@mail.com')->first();
    	$profiles = DB::table('profiles')->where('user_id', $users->id)->first();
        DB::table('verified_submissions')->where('profile_id', $profiles->id)->update([
			'created_at' => '2017-07-20 09:40:51',
			'updated_at' => '2017-07-20 09:40:51'
        ]);
    }
}
