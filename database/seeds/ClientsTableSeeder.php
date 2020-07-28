<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('client')->truncate();
        
        $file = new SplFileObject(base_path('database/seeds/csvs/client.csv'));
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
                "fye" => $line[2],
                "vic_status" => $line[3],
                "group_companies" => $line[4],
                "website" => $line[5],
                "address_us" => $line[6],
                "address_jp" => $line[7],
                "mailing_address" => $line[8],
                "tel1" => $line[9],
                "tel2" => $line[10],
                "tel3" => $line[11],
                "fax" => $line[12],
                "federal_id" => "",
                "state_id" => "",
                "edd_id" => "",
                "note" => $line[13],
                "pic" => $line[14],
                "nature_of_business" => $line[15],
                "incorporation_date" => $line[16],
                "incorporation_state" => $line[17],
                "business_started" => $line[18]                
                
            ];
        }

        DB::table("client")->insert($list);
    }
}
