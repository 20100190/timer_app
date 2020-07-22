<?php

use Illuminate\Database\Seeder;

class WeeksTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('week')->truncate();
        
        $file = new SplFileObject(base_path('database\seeds\csvs\week.csv'));
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
                "year" => $line[0],
                "month" => $line[1],
                "week" => $line[2],
                "day" => $line[3],
                "no" => $line[4]                
            ];
        }

        DB::table("week")->insert($list);
    }
}
