<?php

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('project')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/project.csv'));
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
                "client_id" => $line[1],
                "project_type" => $line[2],
                "project_year" => $line[3],
                "project_name" => $line[4],
                "pic" => $line[5],
                "start" => $line[6],
                "end" => $line[7],
                "billable" => $line[8],
                "note" => $line[9],
                "engagement_fee_unit" => $line[10],
                "invoice_per_year" => $line[11],
                "adjustments" => $line[12]
            ];
        }

        DB::table("project")->insert($list);
    }
}
