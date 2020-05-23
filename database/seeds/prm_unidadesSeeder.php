<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class prm_unidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prm_unidades')->insert([
			[
				'unidad' => 'Und',
				'slug' => 'Und',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],
			[
				'unidad' => 'Kl',
				'slug' => 'Kl',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],  
        ]);
    }
}
