<?php

use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('task')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/task.csv'));
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
                "project_type" => $line[1],
                "name" => $line[2],
                "is_standard" => $line[3]                          
            ];
        }

        DB::table("task")->insert($list);
    }
}
