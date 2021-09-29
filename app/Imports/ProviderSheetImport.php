<?php

namespace App\Imports;

use DB;
use App\User;
use App\Provider;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;

class ProviderSheetImport implements ToCollection
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
                $user = User::where("email", $row[5])->first();
                $provider = Provider::where("rfc",$row[3])->first();

                if(empty($user) && empty($provider)){
                    $user = new User;
                    $user->name = $row[1];
                    $user->email = $row[5];
                    $user->password = Hash::make($row[2]);
                    $user->save();

                    $user->roles()->attach(2);
                    $user->save();

                    $provider = new Provider();
                    $provider->rfc = $row[3];
                    $provider->phone = (!empty($row[4])) ? $row[4] : "";
                    $provider->contact = (!empty($row[10])) ? $row[10] : "";
                    $provider->credit_terms = (!empty($row[6])) ? $row[6] : 0;
                    $provider->cfdi = "Gastos general";
                    $provider->cod_proveedor = $row[0];
                    $provider->user_id = $user->id;

                    $provider->save();
                }
                else {
                    $provider = Provider::with('user')->where('user_id', $provider->user_id)->get();
                    $provider = $provider[0];
                    $user = $provider->user;

                    // $user->name = $row[1];
                    // $user->password = Hash::make($row[2]);
                    // $user->save();

                    Provider::updateOrCreate(
                        ['user_id' => $provider->user_id],
                        [   'rfc' => $row[3],
                            'phone' => (!empty($row[4])) ? $row[4] : "",
                            'contact' => (!empty($row[10])) ? $row[10] : "",
                            'credit_terms' => (!empty($row[6])) ? $row[6] : 0,
                            'cod_proveedor' => $row[0],
                        ]
                    );
                }
            }
        }
    }
}
