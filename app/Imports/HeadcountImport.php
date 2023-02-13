<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class HeadcountImport implements ToCollection
{
    public function collection(Collection $rows)
    {	
        $collect_period = [];
    	try {
            // Dont incluce first row while it's a title
    		unset($rows[0]);
    	} catch(\Exception $e){}

        foreach ($rows as $row) 
        {	
            if(isset($row[0]) && $row[0] && isset($row[1]) && $row[1] && isset($row[2]) && $row[2] && isset($row[3]) && $row[3] && isset($row[4]) && $row[4]){
                $period = (String)$row[0]."-".(String)$row[1]."-".(String)$row[2];
                try {
                    if(!in_array($period, $collect_period)){
                        // Dont incluce first row while it's a title
                        DB::connection('dbayana-stg')
                        ->table('dbo.MasterPnlHeadcount')
                        ->where(['PLANT_ID'=>$row[0], 'PERIOD'=>$row[1], 'YEAR'=>$row[2]])
                        ->delete();
                        array_push($collect_period, $period);
                    }
                } catch(\Exception $e){}

            	$data_insert = [
                    'PLANT_ID'=>isset($row[0]) ? $row[0] : '',
                    'PERIOD' =>isset($row[1]) ? $row[1] : '',
                    'YEAR'=>isset($row[2]) ? $row[2] : '',
                    'SAP_COSTCENTER'=>isset($row[3]) ? $row[3] : '',
                    'EMPLOYEE_NAME_ID'=>isset($row[4]) ? $row[4] : ''
                ];
                DB::connection('dbayana-stg')
                ->table('dbo.MasterPnlHeadcount')
                ->insert($data_insert);
            } else {
                continue;
            }
        }
    }
}