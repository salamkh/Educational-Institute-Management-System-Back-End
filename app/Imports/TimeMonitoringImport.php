<?php

namespace App\Imports;

use App\Models\timeMonitoring;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\HeadingRowImport;

class TimeMonitoringImport implements ToCollection , WithHeadingRow , WithUpsertColumns //, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        for ($i=0;$i<sizeof($rows);$i++){
            $user = User::find($rows[$i]['الرقم']);
            if ($user){
            $timeMonitoring = timeMonitoring::where('userId',$rows[$i]['الرقم'])->where('date',date('Y-m-d',($rows[$i]['التاريخ']- 25569) * 86400))->get()->first();
            if(!$timeMonitoring){
                if (gmdate("H:i", $rows[$i]['الدخول']*24*3600) <= gmdate("H:i", $rows[$i]['الخروج']*24*3600) && ($rows[$i]['الدخول']) &&($rows[$i]['الخروج']) ){
                    $timeMonitoring = new timeMonitoring();

                    $timeMonitoring->userId = $rows[$i]['الرقم'];
                    
                    $timeMonitoring->startTime = gmdate("H:i", $rows[$i]['الدخول']*24*3600);
                   
                    $timeMonitoring->exitTime = gmdate("H:i", $rows[$i]['الخروج']*24*3600);
                    
                    $timeMonitoring->date = date('Y-m-d',($rows[$i]['التاريخ']- 25569)* 86400);
                    
                     $timeMonitoring->save();
                }
            }
            else{
                if (gmdate("H:i", $rows[$i]['الدخول']*24*3600) <= gmdate("H:i", $rows[$i]['الخروج']*24*3600) && ($rows[$i]['الدخول']) &&($rows[$i]['الخروج']) ){
                $timeMonitoring->userId = $rows[$i]['الرقم'];
                    
                $timeMonitoring->startTime = gmdate("H:i", $rows[$i]['الدخول']*24*3600);
               
                $timeMonitoring->exitTime = gmdate("H:i", $rows[$i]['الخروج']*24*3600);
                                
                $timeMonitoring->update();
                }
            }
        }
        }
    }
    public function model(array $row)
    {
          
        return new timeMonitoring([
            'userId'       => $row['الرقم'],
            'startTime'    => gmdate("H:i", $row['الدخول']*24*3600), 
            'exitTime'     => gmdate("H:i", $row['الخروج']*24*3600),
            'date'         => date('Y-m-d',($row['التاريخ']- 25569) * 86400)
        ]);
        
    }
    public function upsertColumns()
    {
        return ['userId', 'date'];
    }
  
}
