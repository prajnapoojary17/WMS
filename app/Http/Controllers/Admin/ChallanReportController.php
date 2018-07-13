<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Datatables;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ChallanReport;

class ChallanReportController extends Controller
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
        return view('admin.challan_report');
    }

    public function reportSearch(Request $request)
    {
        $from_date_format = $request->from_date;
        $to_date_format = $request->to_date;
        $from_date = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : '0');
        $to_date = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : '0');        
        $report_data = ChallanReport::getSearchResult($from_date, $to_date);
        return Datatables::of($report_data)->make(true);
    }

    public function printReport(Request $request)
    {
        $requestData = $request->all();
        $from_date_req = $requestData['from_date'];
        $to_date_req = $requestData['to_date'];
        $from_date = ($from_date_req != '' ? date("Y-m-d", strtotime($from_date_req)) : '0');
        $to_date = ($to_date_req != '' ? date("Y-m-d", strtotime($to_date_req)) : '0');
        $report_data = ChallanReport::getSearchResult($from_date, $to_date);
        $view = View("admin/challan_report_printpdf", ["data" => $report_data])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a2', 'landscape');
        return $pdf->stream('challanReport.pdf');
    }

    public function printExcelReport(Request $request)
    {
        $arrParams = $request->all();
        $from_date_format = $arrParams['from_date'];
        $to_date_format = $arrParams['to_date'];
        $from_date = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : '0');
        $to_date = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : '0');    
        if ($from_date_format != '') {
            $from_date = date("Y-m-d", strtotime($from_date_format));
        } else {
            $from_date = '0';
        }
        if ($to_date_format != '') {
            $to_date = date("Y-m-d", strtotime($to_date_format));
        } else {
            $to_date = '0';
        }
        $report_data = ChallanReport::getSearchResult($from_date, $to_date);
        $paymentsArray = [];

        $resultArray[] = ['Sl.No','Sequence Number','Consumer Name','Mobile No', 'Challan No ', 'Bank Name', 'Payment Date', 'Branch Name', 'Payment Mode ', 'Cheque/DD', 'Transaction Number', 'Total Amount'];
        $i = 1;
        foreach ($report_data as $data) {
            $rowdata = [];
            $rowdata['slno'] = $i;
            $rowdata['sequence_number'] = $data['sequence_number'];
            $rowdata['name'] = $data['name'];
            $rowdata['mobile_no'] = ($data['mobile_no'] == '0'? '': $data['mobile_no']);
            $rowdata['challan_no'] = $data['challan_no'];
            $rowdata['bank_name'] = $data['bank_name'];
            $rowdata['payment_date'] = $data['payment_date'];
            $rowdata['branch_name'] = $data['branch_name'];
            $rowdata['payment_mode'] = $data['mode_type'];
            $rowdata['cheque_dd'] = $data['cheque_dd'];
            $rowdata['transaction_number'] = $data['transaction_number'];
            $rowdata['total_amount'] = $data['total_amount'];
            $resultArray[] = $rowdata;
            $i++;
        }
        Excel::create('challan_report', function($excel) use ($resultArray) {
            $excel->setTitle('Challan Report');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Challan Report file');
            $excel->sheet('sheet1', function($sheet) use ($resultArray) {
                $sheet->fromArray($resultArray, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
