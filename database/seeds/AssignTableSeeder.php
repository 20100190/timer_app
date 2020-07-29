<?php

use Illuminate\Database\Seeder;

class AssignTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('assign')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/assign.csv'));
        $file->setFlags(
            \SplFileObject::READ_CSV |
            \SplFileObject::READ_AHEAD |
            \SplFileObject::SKIP_EMPTY |
            \SplFileObject::DROP_NEW_LINE
        );
        $list = [];
        //$now = Carbon::now();
        foreach($file as $line) {
            $list[] = [
                "id" => $line[0],
                "project_id" => $line[1],
                "staff_id" => $line[2],
                "role" => $line[3],
                "budget_hour" => $line[4]    
            ];
        }

        DB::table("assign")->insert($list);
    }
}
