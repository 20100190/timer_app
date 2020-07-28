<?php

use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('staff')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/staff.csv'));
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
                "employee_no" => $line[1],
                "first_name" => $line[2],
                "last_name" => $line[3],
                "initial" => $line[4],
                "department" => $line[5],
                "title" => $line[6],
                "billing_title" => $line[7],
                "rate" => $line[8],
                "extension" => $line[9],
                "email" => $line[10],
                "cell_phone" => $line[11],
                "status" => $line[12],
                "default_role" => ""
            ];
        }

        DB::table("staff")->insert($list);
    }
}
