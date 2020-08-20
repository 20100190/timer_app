<?php

use Illuminate\Database\Seeder;

class BudgetTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('budget')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/budget.csv'));
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
                "assign_id" => $line[0],
                "year" => $line[1],
                "month" => $line[2],
                "day" => $line[3],
                "working_days" => $line[4],
                "ymd" => $line[5]
            ];
        }

        DB::table("budget")->insert($list);
    }
}
