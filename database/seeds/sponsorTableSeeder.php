<?php

use Illuminate\Database\Seeder;

class sponsorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sponsors')->insert([
        	'sponsors_name' => 'Sinarmas',
        	'desc' => 'Sinar Mas didirikan pada tahun 1938 oleh Eka Tjipta Widjaja di Indonesia. Sinar Mas merupakan sebuah brand name dengan operasi bisnis yang bergerak di berbagai sektor, seperti Pulp dan Kertas, Agribisnis dan Food, Jasa Keuangan, Developer dan Real Estate, Telekomunikasi, dan Energi dan Infrastruktur, termasuk Kesehatan dan Pendidikan[1]. Sejak tahun 2003, Sinar Mas tidak lagi menyebut dirinya sebagai Sinar Mas Group, karena setelah restrukturisasi, Sinar Mas tidak lagi memiliki holding, melainkan President office yang memfasilitasi/membantu pilar-pilar bisnis[2]. Pada tahun 1968, penyulingan minyak nabati dan kopra pertama Sinar Mas, Pabrik Bitung Manado Oil Ltd. didirikan di Sulawesi Utara. Seiring dengan perkembangannya, Sinar Mas mengakuisisi pabrik soda kimia – Tjiwi Kimia pada tahun 1972, yang kemudian menjadi pabrik kertas pertama Sinar Mas. Tahun 1972 juga menandai dimulainya pilar bisnis Developer dan Real Estate, yang dikenal dengan PT Duta Pertiwi Tbk. Kemudian di tahun 1982, PT Internas Artha Leasing didirikan dan berkembang menjadi perusahaan jasa keuangan yang terintegrasi. Pada tahun 1986, Sinar Mas Forestry mengelola hutan tanaman industrinya yang pertama. PT Dian Swastatika Sentosa didirikan pada tahun 1996 untuk memasok listrik ke fasilitas-fasilitas produksi Sinar Mas di pedalaman. Pada tahun 2006, Smartfren didirikan sebagai hasil merger dengan salah satu provider telekomunikasi.',
        	'image' => '/assets/images/sponsor/sinarmas.png',
        	'created_by' => 'System',
        	'updated_by' => 'System',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);

        DB::table('sponsors')->insert([
        	'sponsors_name' => 'Kompas',
        	'desc' => 'Harian Kompas adalah nama surat kabar Indonesia yang berkantor pusat di Jakarta. Koran Kompas diterbitkan oleh PT Kompas Media Nusantara yang merupakan bagian dari Kompas Gramedia (KG). Kompas juga terbit dalam bentuk daring di alamat Kompas.com yang dikelola oleh PT Kompas Cyber Media. Kompas.com berisi berita-berita yang diperbarui secara aktual dan juga memiliki subkanal koran Kompas dalam bentuk digital.',
        	'image' => '/assets/images/sponsor/kompas.jpg',
        	'created_by' => 'System',
        	'updated_by' => 'System',
        	'created_at' => date('Y-m-d'),
        	'updated_at' => date('Y-m-d'),
        ]);
    }
}
