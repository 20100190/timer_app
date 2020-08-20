<?php

use Illuminate\Database\Seeder;

class RoleOrderTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('role_order')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/role_order.csv'));
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
                "role" => $line[1],
                "order" => $line[2]                
            ];
        }

        DB::table("role_order")->insert($list);
    }
}
