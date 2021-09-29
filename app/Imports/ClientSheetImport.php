<?php

namespace App\Imports;

use DB;
use App\User;
use App\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;

class ClientSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $firstRowDone = false;

        foreach ($collection as $row)
        {
            if(!$firstRowDone) {
                $firstRowDone = true;
            }
            else {
                $client = Client::where("rfc",$row[3])->first();
                $user = User::where("email", $row[5])->first();

                if(empty($user) && empty($client)){
                    $user = new User;
                    $user->name = $row[1];
                    $user->email = $row[5];
                    $user->password = Hash::make($row[2]);
                    $user->save();

                    $user->roles()->attach(3);
                    $user->save();

                    $client = new Client;
                    $client->rfc = $row[3];
                    $client->phone = (!empty($row[4])) ? $row[4] : "";
                    $client->credit_days = $row[6];
                    $client->payment_method = $row[7];
                    $client->way_to_pay = $row[8];
                    $client->cfdi = "Gastos general";
                    $client->status = 1;
                    $client->cod_cliente = $row[0];
                    $client->user_id = $user->id;

                    $client->save();
                }
            }
        }
    }
}
