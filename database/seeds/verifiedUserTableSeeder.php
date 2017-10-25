<?php

use Illuminate\Database\Seeder;

class verifiedUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = DB::table('users')->insertGetId([
		        	'name' => 'test_user',
					'email' => 'test_user@mail.com',
					'username' => 'test_user',
					'password' => bcrypt('test_user'),
					'created_by' => 'System',
					'updated_by' => 'System'
		        ]);

       	$profile_id = DB::table('profiles')->insertGetId([
			       		'user_id' => $id,
						'fullname' => 'Test User',
						'birthday' => '2017-09-11',
						'phone_number' => '123456977',
						'avatar' => '/assets/images/submissions/765-default-avatar.png',
						'bio' => '',
						'created_by' => 'System',
						'updated_by' => 'System',

			        ]);

        DB::table('verified_submissions')->insert([
        	'profile_id' => $profile_id,
			'self_image' => '/assets/images/submissions/765-default-avatar.png',
			'image_id_card' => '/assets/images/submissions/default.jpg',
			'filing_status' => 0,
			'created_by' => 'System',
			'created_at' => '2017-07-20 09:40:51',
			'updated_at' => '2017-07-20 09:40:51',
			'updated_by' => 'System'
        ]);
    }
}
