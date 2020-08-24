<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/users.csv'));
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
                "name" => $line[1],
                "email" => $line[2],
                "email_verified_at" => $line[3],
                "password" => $line[4],
                "remember_token" => $line[5],
                "created_at" => $line[6],
                "updated_at" => $line[7]
            ];
        }

        DB::table("users")->insert($list);
    }
}
