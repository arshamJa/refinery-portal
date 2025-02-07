<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;



class UserImport implements ToCollection,ToModel
{
    private $current = 0;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        $this->current++;
        if($this->current > 1){
            $count = User::where('full_name' , '=',$row[2])->count();
            if (empty($count)){
                $user = new User;
                $user->role = $row[0];
                $user->unit_id = $row[1];
                $user->full_name = $row[2];
                $user->password = $row[3];
                $user->n_code = $row[4];
                $user->p_code = $row[5];
                $user->phone = $row[6];
                $user->save();
            }
        }
    }
}
