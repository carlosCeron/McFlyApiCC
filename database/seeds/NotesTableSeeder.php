<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * 
 */
class NotesTableSeeder extends Seeder
{
    public function run(){

        DB::table('notes')->insert([
            'title' => 'Nota1',
            'description' => 'Esta es mi primera Nota',
            'author' => 'Carlos Ceron',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

         DB::table('notes')->insert([
            'title' => 'Nota2',
            'description' => 'Esta es mi Segunda Nota',
            'author' => 'Carlos Ceron',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}


?>