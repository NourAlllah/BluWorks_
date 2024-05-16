<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 20; $i++) {
            DB::table('workers')->insert([
                'name' => $faker->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
