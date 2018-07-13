<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Ward;
use App\Models\ConnectionType;
use App\Models\WardWiseDCBReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;

class WardDCBReportController extends Controller
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
        return view('admin.wardwise_dcb_report',['wards' => $wards,'connTypes' => $connType]);
      
    }
    public function DCBReportSearch(Request $request)
    {
     
        $ward=$request->ward;
        $con_type=$request->con_type;
        $from_date_format= $request->from_date;
        $to_date_format= $request->to_date;
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
      
         $dcb_report_search_data = WardWiseDCBReport::getWardDCBSearchResult($ward,$con_type,$from_date,$to_date);
       
         return Datatables::of($dcb_report_search_data)->make(true); 

    }
    public function DCBReportPrint(Request $request)
    {
 
        $arrParams = $request->all();
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
        
        $dcb_report_search_data = WardWiseDCBReport::getWardDCBSearchResult($ward,$con_type,$from_date,$to_date);
    
        //return view('admin.dcb_report_table', ['dcb_report_array' => $dcb_report_search_data]);
        $view = View("admin/dcb_report_table", ["dcb_report_array" => $dcb_report_search_data])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a2', 'landscape');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('billinfo.pdf');
    }

    

}
