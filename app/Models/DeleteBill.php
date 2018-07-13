<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsumerConnection;
use App\Models\MeterReading;
use App\Models\DeleteBillLog;
use Carbon\Carbon;
use DB;
use Auth;

class DeleteBill  extends Model
{
    
     public static function getBasicDetails($seq_no) {
         
         
         $data=ConsumerConnection::select('consumer_connection.sequence_number','consumer_connection.name',
                                         'consumer_connection.meter_no','master_connections_type.connection_name', 'master_ward.ward_name','master_corp.corp_name')
                                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                                ->where('consumer_connection.sequence_number',$seq_no)->get();

  
         return $data;
         
     }
     
     public static function deleteRecordCountCheck($seq_no)
     {
         $fromDate = new Carbon('last week'); 
         $toDate = new Carbon('today');
         $get_total_count= MeterReading::select('meter_reading.id')
                  ->where('meter_reading.sequence_number',$seq_no)
                  ->where('meter_reading.active_record','=','1')
                  ->whereBetween(DB::raw('date(meter_reading.date_of_reading)'), array($fromDate,$toDate) )->get();

          return $get_total_count;
     }
     
     public static function deleteRecordData($last_id,$seq_no)
     {
        $to_delete_record=MeterReading::select('meter_reading.sequence_number','meter_reading.date_of_reading')
                                    ->where('meter_reading.id', '=', $last_id)
                                   -> where('meter_reading.active_record','=','1')->first();
        $deleted_seq=$to_delete_record->sequence_number;
        $deleted_date_reading=$to_delete_record->date_of_reading;
        $user = Auth::User();
        $deleted_by=$user->id;
        $affectedRows = MeterReading::where('meter_reading.id', '=', $last_id)
                                   -> where('meter_reading.active_record','=','1')->delete();
        
        if($affectedRows==1)
        {
             $add_log= DeleteBillLog::create([
                    'sequence_number' => $deleted_seq,
                    'date_of_reading' => $deleted_date_reading,
                    'deleted_by' => $deleted_by ]);
             $get_latest_one= MeterReading::select('meter_reading.id','meter_reading.meter_status')
                  ->where('meter_reading.sequence_number',$seq_no)
                 ->orderBy('meter_reading.id','desc')
                 ->limit(1)->first();
             $set_active_id=$get_latest_one->id;
             $get_meter_status=$get_latest_one->meter_status;
             $update_active_record=MeterReading::where('meter_reading.id',$set_active_id)
              ->update(['meter_reading.active_record' =>'1']);
             $update_active_record=ConsumerConnection::where('consumer_connection.sequence_number',$seq_no)
              ->update(['consumer_connection.meter_status_id' =>$get_meter_status]);
             
             
             
        }
     }
     
     public static function sequenceNoCheck($seq_no)
     {
          $get_seq_no= ConsumerConnection::select('consumer_connection.id')
                  ->where('consumer_connection.sequence_number',$seq_no)
                  ->get();
          
          return $get_seq_no;
         
     }
     
    
}
