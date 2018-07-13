<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MeterReading;
use App\Models\ConsumerConnection;

use Carbon\Carbon;
use DB;

class ConsumerBillReport  extends Model
{
 
    public static function getSearchResult($seq_number,$meter_no,$ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count)
    {
       
         $billing_details= MeterReading::select(                   
                 'consumer_connection.sequence_number','consumer_connection.name',
                 'consumer_connection.connection_date','consumer_address.premises_address',
                 'consumer_connection.meter_no','master_connections_type.connection_name',
                 'master_ward.ward_name','master_corp.corp_name','users.name as agent_name', 
                 DB::raw('YEAR(date_of_reading) AS year, MONTH(date_of_reading) AS month'),
                 'meter_reading.date_of_reading',
                 'meter_reading.previous_billing_date','meter_reading.previous_reading',
                 'meter_reading.current_reading','meter_reading.water_charge','meter_reading.extra_amount',
                 'meter_reading.other_charges','meter_reading.penalty','master_meter_status.meter_status','meter_reading.bill_no',
                 'meter_reading.total_amount',DB::raw('SUM(IFNULL(meter_reading.total_amount,0)+IFNULL(extra_amount,0)+IFNULL(-advance_amount,0)) as cba'),'meter_reading.total_unit_used',
                  DB::raw('SUM(payment_history.total_amount) as paid_amount'),DB::raw('IFNULL(meter_reading.total_amount,0)-IFNULL(arrears,0) as demand'),
                 'payment_history.payment_date','bank_info.transaction_number')
                 ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                 ->leftjoin('users','users.id','=','meter_reading.agent_id')
                 ->leftjoin('consumer_address','consumer_address.sequence_number','=','consumer_connection.sequence_number')
                 ->leftjoin('master_meter_status','master_meter_status.id','=','meter_reading.meter_status')
                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                 ->leftjoin('payment_history','payment_history.meter_reading_id','=','meter_reading.id')
                 ->leftjoin('bank_info','bank_info.payment_id','=','payment_history.id')

                 ->where(function($billing_details)use ($seq_number,$ward,$meter_no,$con_type,$from_date,$to_date,$search_key){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '>=',$from_date);
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $billing_details->where('consumer_connection.sequence_number', '=',$seq_number);
                   
                    }
                   if($ward!='0')
                    {
                        $billing_details->where('consumer_connection.ward_id', '=',$ward);
                    }
                    if($meter_no!='0')
                    {
                           $billing_details->where('consumer_connection.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $billing_details->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($search_key!='')
                    {
                        $billing_details->whereRaw('(consumer_connection.sequence_number LIKE  "%'.$search_key.'%" OR consumer_connection.name 
                                   LIKE "%'.$search_key.'%" OR consumer_address.premises_address LIKE "%'.$search_key.'%" OR 
                                    consumer_connection.meter_no LIKE "%'.$search_key.'%" OR master_connections_type.connection_name LIKE "%'.$search_key.'%" OR 
                                    master_ward.ward_name LIKE "%'.$search_key.'%" OR master_corp.corp_name LIKE "%'.$search_key.'%" OR users.name LIKE "%'.$search_key.'%" OR  
                                    meter_reading.bill_no LIKE "%'.$search_key.'%" OR 
                                    payment_history.payment_date LIKE "%'.$search_key.'%" OR bank_info.transaction_number LIKE "%'.$search_key.'%")');
                    }
                    
                  })
                  //->groupBy('meter_reading.bill_no')
                ->groupBy('meter_reading.id') 
                 ->orderBy('meter_reading.created_at','desc')->offset($start_limit)
                ->limit($paginate_count);


           return $billing_details; 
      }
    public static function createExcel($seq_number,$ward,$meter_no,$con_type,$from_date,$to_date)
     {
        
          $billing_details = MeterReading::select('meter_reading.sequence_number' ,'meter_reading.consumer_name','master_connections_type.connection_name',
                                                    'meter_reading.door_no','meter_reading.date_of_reading','meter_reading.bill_no',
                                                  'meter_reading.previous_reading','meter_reading.current_reading','meter_reading.total_amount',
                                                  'master_corp.corp_name','agents.agent_name')  

                    ->leftjoin('agents','agents.agent_user_id','=','meter_reading.agent_id')
                    ->leftjoin('master_corp','master_corp.id','=','meter_reading.corpward_id') 
                   ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                  ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                  ->where(function($billing_details)use ($seq_number,$ward,$meter_no,$con_type,$from_date,$to_date){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '>=',$from_date);
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $billing_details->where('meter_reading.sequence_number', '=',$seq_number);
                   
                    }
                   if($ward!='0')
                    {
                        $billing_details->where('meter_reading.ward_id', '=',$ward);
                    }
                    if($meter_no!='0')
                    {
                           $billing_details->where('meter_reading.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $billing_details->where('consumer_connection.connection_type_id', '=',$con_type);
                    }
                    })
                    ->orderBy('meter_reading.created_at','desc')->get();


               return $billing_details; 

     }

}
