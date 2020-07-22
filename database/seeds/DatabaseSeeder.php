<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        
        $this->call(StaffTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(WeeksTableSeeder::class);
    }
}
