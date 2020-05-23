<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class prm_rolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prm_roles')->insert([
			[
				'rol' => 'Cliente',
				'slug' => 'cliente',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],
			[
				'rol' => 'Proveedor',
				'slug' => 'proveedor',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],  
        ]);
    }
}
