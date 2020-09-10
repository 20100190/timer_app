<?php

use Illuminate\Database\Seeder;

class PhaseTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('phase')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/phase.csv'));
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
                "color" => $line[3],
                "order" => $line[4],
                "description" => $line[5],
            ];
        }

        DB::table("phase")->insert($list);
    }
}
