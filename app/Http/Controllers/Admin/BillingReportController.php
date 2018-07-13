<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Ward;
use App\Models\ConnectionType;
use App\Models\ConsumerBillReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;
use Maatwebsite\Excel\Facades\Excel;

class BillingReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        
        $wards = Ward::all();
        $connType = ConnectionType::all();
        return view('admin.billing_report',['wards' => $wards,'connTypes' => $connType]);
      
    }
    public function billReportSearch(Request $request)
    {
      
         $seq_number=$request->seq_number;
         $meter_no=$request->meter_no;
         $ward=$request->ward;
         $con_type=$request->con_type;
         $from_date_format= $request->from_date;
         $to_date_format= $request->to_date;
         $paginate_count= $request->length;
         $search_key=$request->search_key;
         $start_limit=0;
         if($from_date_format!='')
            {
            $from_date= date("Y-m-d", strtotime($from_date_format));
            }
            else
            {
                $from_date='0';
            }
            if($to_date_format!='')
            {
            $to_date= date("Y-m-d", strtotime($to_date_format) );
            }
            else
            {
               $to_date='0'; 
            }
         $report_search_data_res = ConsumerBillReport::getSearchResult($seq_number,$meter_no,$ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count);
         $check=  $report_search_data_res->get();
         $count=count($check);
         if ($count==0) { 
             
             $message='No Data';
             return view('admin.bill_report_table', ['result' =>'0','report_search_data' =>$message ,'i' => 0,'list_count'=>$paginate_count,'keysearch'=>$search_key]);
         }
      else
      {
		  
           $report_search_data= $report_search_data_res->paginate($paginate_count);
         return view('admin.bill_report_table', ['result' =>'1','report_search_data' => $report_search_data,'i' => 0,'list_count'=>$paginate_count,'keysearch'=>$search_key,'rowcount'=>$count]);

      }
    }
    
    public function billReportPrint(Request $request)
    {
  
        $arrParams = $request->all();
        $sequence_number = $arrParams['sequence_number'];
	$meter_no= $arrParams['meter_no'];
	$ward = $arrParams['ward'];
	$con_type= $arrParams['con_type'];
        $from_date_format= $arrParams['from_date'];
        $to_date_format= $arrParams['to_date'];
        $paginate_count=$arrParams['rowcount']; 
        $search_key=$arrParams['search_key'];
        $start_limit=$arrParams['start_limit'];
        //$end_limit=$arrParams['end_limit'];
         if($from_date_format!='')
            {
            $from_date= date("Y-m-d", strtotime($from_date_format));
            }
            else
            {
                $from_date='0';
            }
            if($to_date_format!='')
            {
            $to_date= date("Y-m-d", strtotime($to_date_format) );
            }
            else
            {
               $to_date='0'; 
            }
         $report_search_data_res = ConsumerBillReport::getSearchResult($sequence_number,$meter_no,$ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count);
       

            $report_search_data= $report_search_data_res->get();
            $view = View("admin/bill_report_print", ["report_search_data" => $report_search_data])->render();
           $headers = array('Content-Type' => 'application/pdf');
           $pdf = \App::make('dompdf.wrapper', $headers);
           $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
           $pdf->loadHTML($view)->setPaper('a2', 'landscape');
           //$pdf->setPaper([0, 0, 595, 870], 'landscape');
           return $pdf->stream('billinfo.pdf');

      
    }
    
    public function excelReport(Request $request) {
	
		ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $arrParams = $request->all();
        $sequence_number = $arrParams['sequence_number'];
		$meter_no= $arrParams['meter_no'];
		$ward = $arrParams['ward'];
		$con_type= $arrParams['con_type'];
        $from_date_format= $arrParams['from_date'];
        $to_date_format= $arrParams['to_date'];
        if($from_date_format!='')
         {
         $from_date= date("Y-m-d", strtotime($from_date_format));
         }
         else
         {
             $from_date='0';
         }
         if($to_date_format!='')
         {
         $to_date= date("Y-m-d", strtotime($to_date_format) );
         }
         else
         {
            $to_date='0'; 
         }

    $payments=ConsumerBillReport::createExcel($sequence_number,$meter_no,$ward,$con_type,$from_date,$to_date);

    $paymentsArray = []; 


    $paymentsArray[] = ['Sequence Number', 'Consumer Name','Connection Type','Door No','Date of Reading','Bill No','Previous Reading','Current Reading','Total Amount','Corp Ward','Agent Name'];

    foreach ($payments as $payment) {
        $paymentsArray[] = $payment->toArray();
    }

    // Generate and return the spreadsheet
    Excel::create('meter_reading_report', function($excel) use ($payments,$paymentsArray) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle('Meter Reading Report');
        $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
        $excel->setDescription('Meter Reading Report file');

        // Build the spreadsheet, passing in the payments array
        $excel->sheet('sheet1', function($sheet) use ($paymentsArray) {
            $sheet->fromArray($paymentsArray, null, 'A1', false, false);
        });

    })->download('xlsx');
}

}
