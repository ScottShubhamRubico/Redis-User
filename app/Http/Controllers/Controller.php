<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function computer(Request $request)
    {
        $file = $request->file('file');
        $fileContents=  file($file->getPathname());
        $data = [];
        foreach ($fileContents as $key=>$line) {
if($key==0){
    continue;
}
$data = str_getcsv($line);
$statusId =  $this->getStatusId($data[16]??"NA");
$manufacturerId =  $this->manuFacturers($data[7]??'');
            $comment = 'Who Manage: ' . ($data[6] ?? '') . ',\n ' .
                'Zoho/QB Transaction Amount: ' . ($data[3] ?? '') . '-' . (isset($data[4]) ? $data[4] : '') . ', \n ' .
                'Individual Asset Amount INR: ' . (isset($data[9]) ? $data[9] : '') . ', \n ' .
                'Labeled: ' . (isset($data[13]) ? $data[13] : '') . ', \n ' .
                'Date Warranty Expires: ' . (isset($data[12]) ? $data[12] : '');

            $date = date_create('today');
             if(strtotime($data[1]??'')) {
                 $date = date_create($data[1]);
             }
            if(!isset($data[6])){
                continue;
            }
           $computer =  DB::table('glpi_computers')->where([ 'name' => $data[6],
                'serial'=>$data[11]??'', 'states_id'=>$statusId,
               'manufacturers_id'=>$manufacturerId])->exists();
if($computer){
continue;
}

          DB::table('glpi_computers')->insert([
                'name' => $data[6],
                'serial'=>$data[11]??'',
                'date_creation'=>date_format($date,"Y/m/d H:i:s"),
                'states_id'=>$statusId,
                'manufacturers_id'=>$manufacturerId,
                'comment'=>$comment,
                'locations_id'=>$this->locationId($data[15]??''),
              'computertypes_id'=>$this->typeId($data[6]??'')
            ]);
        }
        return $data;
    }

    private function getStatusId($statusName) {
        if($statusName==''){
            $statusName =  'NA';
        }
        $status = DB::table('glpi_states')->where('name', $statusName)->first();
        if (!$status) {
            DB::table('glpi_states')->insert(['name' => $statusName,'completename'=>$statusName]);
            return $this->getStatusId($statusName);
        } else {
            return $status->id;
        }
    }

    private function locationId($locationName) {
        if($locationName==''){
            $locationName =  'NA';
        }
        $status = DB::table('glpi_locations')->where('name', $locationName)->first();
        if (!$status) {
            DB::table('glpi_locations')->insert(['name' => $locationName,'completename'=>$locationName,'state'=>'Haridwar']);
            return $this->getStatusId($locationName);
        } else {
            return $status->id;
        }
    }


    private function manuFacturers($manufacturerName) {
        if($manufacturerName==''){
            $manufacturerName =  'NA';
        }
        $manufacturerId = DB::table('glpi_manufacturers')->where('name', $manufacturerName)->first();
        if (!$manufacturerId) {
            DB::table('glpi_manufacturers')->insert(['name' => $manufacturerName]);
            return $this->manuFacturers($manufacturerName);
        } else {
            return $manufacturerId->id;
        }
    }

    private function typeId($typeName) {
        if($typeName==''){
            $typeName =  'NA';
        }
        $typeId = DB::table('glpi_computertypes')->where('name', $typeName)->first();
        if (!$typeId) {
            DB::table('glpi_computertypes')->insert(['name' => $typeName]);
            return $this->manuFacturers($typeName);
        } else {
            return $typeId->id;
        }
    }
}
