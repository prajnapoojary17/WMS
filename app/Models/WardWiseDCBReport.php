<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsumerConnection;

use Carbon\Carbon;
use DB;

class WardWiseDCBReport  extends Model
{
 
    public static function getWardDCBSearchResult($ward,$con_type,$from_date,$to_date)
    {
    
         $dcb_ward_wise_data= ConsumerConnection::select('master_ward.ward_name',
                'master_connections_type.connection_name',
                 DB::raw('count(*) as total_installation') ,
                 DB::raw('SUM(if(consumer_connection.connection_status_id = 2,1,0)) as live'),
                 DB::raw('COUNT(meter_reading.bill_no) AS bill_count'),
                 DB::raw('SUM(meter_reading.total_unit_used) as total_unit_used'), 
                 DB::raw('SUM(meter_reading.water_charge) as water_charge'),
                 DB::raw('SUM(meter_reading.other_charges) as other_charges'),                 
                 DB::raw('SUM(meter_reading.penalty) as penalty'),
                 DB::raw('(SUM(meter_reading.water_charge)+SUM(meter_reading.other_charges)-SUM(IFNULL(log_connection_change.excess_amt,0))) as demand'),
                 DB::raw('SUM(meter_reading.total_amount) as old_balance'),
                 DB::raw('SUM(payment_history.total_amount) as collection'),
                 DB::raw('SUM(CASE WHEN meter_reading.payment_status =0 THEN meter_reading.total_amount END) as current_balance'))
                 ->leftJoin('meter_reading', function($join){
                        $join->on('consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')
                             ->where('meter_reading.active_record', 1);
                    })
                 ->leftjoin('master_ward' ,'master_ward.id', '=','consumer_connection.ward_id')
                 ->leftjoin('master_connections_type' ,'master_connections_type.id', '=','consumer_connection.connection_type_id')
                 ->leftjoin('payment_history' ,'payment_history.meter_reading_id', '=','meter_reading.id')
                 ->leftjoin('log_connection_change','log_connection_change.sequence_no','=','meter_reading.sequence_number')
                 ->where(function($dcb_ward_wise_data)use ($ward,$con_type,$from_date,$to_date){
                    if($from_date !=0 && $to_date!=0)
                    {
                        $dcb_ward_wise_data->where(DB::raw('date(consumer_connection.connection_date)'), '>=',$from_date);
                        $dcb_ward_wise_data->where(DB::raw('date(consumer_connection.connection_date)'), '<=',$to_date);
                    }
                   if($ward!=0 && $con_type==0 )
                    {
                        $dcb_ward_wise_data->where('consumer_connection.ward_id', '=',$ward);
                    }
                  
                    if($con_type!=0 && $ward==0)
                    {
                        $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($con_type!=0 && $ward!=0)
                    {
                         $dcb_ward_wise_data->where('consumer_connection.ward_id', '=',$ward);
                         $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=',$con_type);
                    }
                    })
                ->groupBy('consumer_connection.connection_type_id')
                ->groupBy('consumer_connection.ward_id');
                
         $dcb_ward_wise_data_result = $dcb_ward_wise_data->get()->toArray();
         if(empty($dcb_ward_wise_data_result))
            {
               $dcb_ward_wise_data_result=[];
               return $dcb_ward_wise_data_result; 
           
            }
            else
            {
               
              return $dcb_ward_wise_data_result; 
            } 
                

        } 

 
}
