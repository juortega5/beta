<?php

use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias')->insert([
			[
				'categoria' => 'Carnicos',
				'slug' => 'carnicos',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],
			[
				'categoria' => 'Lacteos',
				'slug' => 'lacteos',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],  
			[
				'categoria' => 'Verduras',
				'slug' => 'verduras',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],
			[
				'categoria' => 'Frutas',
				'slug' => 'frutas',
				'created_at'=> date("Y-m-d H:i:s"),
				'updated_at'=> date("Y-m-d H:i:s"),
			],
        ]);
    }
}
