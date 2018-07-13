<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Ward;
use App\Models\ConnectionType;
use App\Models\CorpWardWiseReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;

class CorpWardReportController extends Controller
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
        return view('admin.corp_ward_report',['wards' => $wards,'connTypes' => $connType]);
      
    }
    public function CorpWardReportSearch(Request $request)
    {
       
        $ward_id=$request->ward;
        $corp_ward_id=$request->corp_ward;
        $corp_ward_report_data = CorpWardWiseReport::getCorpWardSearchResult($ward_id,$corp_ward_id);
   
       return Datatables::of($corp_ward_report_data)->make(true); 
    }
    
    public function CorpWardReportPrint(Request $request)
    {
      
        $arrParams = $request->all();
	$ward_id = $arrParams['ward'];
        $corp_ward_id= $arrParams['corp_ward'];
        $corp_ward_report_data = CorpWardWiseReport::getCorpWardSearchResult($ward_id,$corp_ward_id);
        $view = View("admin/corp_ward_report_table", ["corp_ward_report_array" => $corp_ward_report_data])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('corpwardreport.pdf');
    }
}
