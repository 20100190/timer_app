<?php

use Illuminate\Database\Seeder;

class ProjectTypeTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('project_type')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/project_type.csv'));
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
                "billable" => $line[2]                
            ];
        }

        DB::table("project_type")->insert($list);
    }
}
