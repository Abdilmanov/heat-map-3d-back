<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\kadastr;
use App\Kato;
use App\Building;
use App\Logs;
use App\Type;
use App\Meter;
use App\Http\Controllers\DB;

class KadastrController extends Controller
{
  public function all(){
    try{
      $kadastr = kadastr::select('*')->get();
      return response()->json($kadastr);
    }
    catch(\Exception $exception){
      return response()->json(['message'=>$exception->getMessage()],500);
    }
  }

  public function getValue(){

      $urlMeter='C:\OSPanel\domains\cadastral\app\almaty_meter.json';
      $dataMeters = file_get_contents($urlMeter);
      $meters = json_decode($dataMeters);

      $urlLogs='C:\OSPanel\domains\cadastral\app\almaty_meter_logs.json';
      $dataLogs = file_get_contents($urlLogs);
      $logs = json_decode($dataLogs);

      $urlBuilding='C:\OSPanel\domains\cadastral\app\building.json';
      $data = file_get_contents($urlBuilding);
      $buildings = json_decode($data);

      foreach ($buildings as $building){
          foreach ($logs as $log) {
              foreach ($meters as $meter){
                  if($building->id == $meter->building_id[0]){
                      foreach ($meter->log_ids as $meterLogId){
                          if($meterLogId == $log->id){
                              if($building->cadastral_number == "20-321-050-035"){
                                  echo $log->value && $log->type_id. "<br>";
                              }
                          }
                      }
                  }
              }
          }
      }
  }

  public function getValueFromLog($cadastral_no){
        return response()->json(\DB::table('almaty_meter_logs')
            ->join('almaty_meter as am1', 'almaty_meter_logs.meter_id','=', 'am1.id')
            ->join('almaty_building', 'am1.building_id','=','almaty_building.id')
            ->join('type', 'am1.type_id','=','type.id')
            ->where('cadastral_number','=',$cadastral_no)
            ->select(
                'value',
                'type.name as type_name',
                'almaty_building.name as building_name',
                'almaty_building.street_no as street_number',
                'almaty_building.cadastral_number as cadastral',
                'almaty_building.tech_passport',
                'am1.serial',
                'check_date'
            )->get());
    }
//doesn't work
//  public function getValueFromBuilding($cadastral_no){
//        return response()->json(\DB::table('almaty_building')
//            ->select(
//                'almaty_building.id as build_id',
//                'almaty_building.name as build_name',
//                'street_no',
//                'cadastral_number',
//                'tech_passport',
//                'almaty_meter.serial',
//                'type.name as type_name',
//                'almaty_meter_logs.check_date',
//                'almaty_meter_logs.value'
//            )
//            ->join('almaty_meter', 'almaty_meter.building_id','=','almaty_building.id')
//            ->join('almaty_meter_logs', 'almaty_meter_logs.meter_id','=', 'almaty_meter.id')
//            ->join('type', 'almaty_meter.type_id','=','type.id')
//            ->where('cadastral_number','=',$cadastral_no)
//            ->groupBy('almaty_building.cadastral_number')
//            ->get());
//    }


  public function insert(){
      $urlMeter='C:\OSPanel\domains\cadastral\app\almaty_meter.json';
      $dataMeters = file_get_contents($urlMeter);
      $meters = json_decode($dataMeters);

      $urlLogs='C:\OSPanel\domains\cadastral\app\almaty_meter_logs.json';
      $dataLogs = file_get_contents($urlLogs);
      $logs = json_decode($dataLogs);

      $urlBuilding='C:\OSPanel\domains\cadastral\app\building.json';
      $data = file_get_contents($urlBuilding);
      $buildings = json_decode($data);

      foreach ($buildings as $building){
          foreach ($meters as $meter) {

              foreach ( $building->meter_ids as $buildingMeterId) {
              if($meter->id == $buildingMeterId){
                  \DB::table('almaty_meter')->insert([
                  'id'=>$meter->id,
                  'name'=>$meter->name,
                  'serial'=>$meter->serial,
                  'building_id'=>$building->id,
                  'type_id'=>$meter->type_id[0]
              ]);
            }
          }

          }
      }
      foreach ($buildings as $building){
          foreach ($meters as $meter) {


              foreach ($logs as $log) {
                  foreach ($meter->log_ids as $meterLog) {
                      if ($log->id == $meterLog) {
                          \DB::table('almaty_meter_logs')->insert([
                              'id' => $log->id,
                              'check_date' => $log->check_date,
                              'value' => $log->value,
                              'meter_id' => $meter->id,
                          ]);
                      }
                  }
              }
          }
      }



//      foreach ($characters as $character) {
//
//          \DB::table('almaty_building')->insert([
//              'id'=>$character->id,
//              'name'=>$character->name,
//              'street_no'=>$character->street_no,
//              'longtitude'=>$character->longtitude,
//              'street'=>$character->street,
//              'cadastral_number'=>$character->cadastral_number
//
//          ]);
//      }
  }
}
