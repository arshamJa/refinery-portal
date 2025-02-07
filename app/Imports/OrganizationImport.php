<?php

namespace App\Imports;

use App\Models\Organization;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class OrganizationImport implements ToCollection, ToModel
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
            $count = Organization::where('organization_name' , '=',$row[1])->orWhere('url','=',$row[2])->count();
            if (empty($count)){
                $organization = new Organization();
                $organization->organization_name = $row[1];
                $organization->url = $row[2];
                $organization->save();
            }
        }
    }
}
