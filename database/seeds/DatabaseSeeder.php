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
        $this->call(AssignTableSeeder::class);
        $this->call(TaskTableSeeder::class);
        $this->call(RoleOrderTableSeeder::class);
        $this->call(ProjectTypeTableSeeder::class);
        $this->call(BudgetTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PhaseTableSeeder::class);
    }
}
