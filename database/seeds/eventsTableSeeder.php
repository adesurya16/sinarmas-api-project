<?php

use Illuminate\Database\Seeder;

class eventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
        	'event_name' => '1001 Pendaki Tanam Pohon',
        	'lat' => 0,
        	'lng' => 0,
        	'event_time' => date('Y-m-d H:i:s'),
          'event_date' => date('Y-m-d'),
        	'participants' => 100,
        	'created_by' => 'System',
        	'updated_by' => 'System',
          'location' => 'Garut, Jawa Barat',
          'desc' => 'Ini adalah kegiatan untuk menanam Pohon',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);

        DB::table('events')->insert([
        	'event_name' => '1002 Pendaki Tanam Pohon',
        	'lat' => 0,
        	'lng' => 0,
        	'event_time' => date('Y-m-d H:i:s'),
          'event_date' => date('Y-m-d'),
        	'participants' => 100,
        	'created_by' => 'System',
        	'updated_by' => 'System',
          'location' => 'Garut, Jawa Barat',
          'desc' => 'Ini adalah kegiatan untuk menanam Pohon',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);

        DB::table('events')->insert([
        	'event_name' => '1003 Pendaki Tanam Pohon',
        	'lat' => 0,
        	'lng' => 0,
        	'event_time' => date('Y-m-d H:i:s'),
          'event_date' => date('Y-m-d'),
        	'participants' => 100,
        	'created_by' => 'System',
        	'updated_by' => 'System',
          'location' => 'Garut, Jawa Barat',
          'desc' => 'Ini adalah kegiatan untuk menanam Pohon',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);

        DB::table('events')->insert([
        	'event_name' => '1004 Pendaki Tanam Pohon',
        	'lat' => 0,
        	'lng' => 0,
        	'event_time' => date('Y-m-d H:i:s'),
          'event_date' => date('Y-m-d'),
        	'participants' => 100,
        	'created_by' => 'System',
        	'updated_by' => 'System',
          'location' => 'Garut, Jawa Barat',
          'desc' => 'Ini adalah kegiatan untuk menanam Pohon',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);

        DB::table('events')->insert([
        	'event_name' => '1005 Pendaki Tanam Pohon',
        	'lat' => 0,
        	'lng' => 0,
        	'event_time' => date('Y-m-d H:i:s'),
          'event_date' => date('Y-m-d'),
        	'participants' => 100,
        	'created_by' => 'System',
        	'updated_by' => 'System',
          'location' => 'Garut, Jawa Barat',
          'desc' => 'Ini adalah kegiatan untuk menanam Pohon',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);
    }
}
