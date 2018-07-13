@extends('layouts.admin_master')
@section('content')
<section class="content-header">
    <h1>
        Challan Reports

    </h1>
</section>
<style>
    .error_color
    {
        color: red;
    }


</style>
<section class="content container-fluid">
    <div class="box box-info">
        <div class="box-body">
            <div class="tab-content">
                <span class="text-danger">
                    <strong id="error-field" style="margin-top:10px;margin-left:25px;"></strong>
                </span>
                <div class="tab-pane active" id="tab_1">
                    <div class="col-md-3 form-group">
                        <b>From Date</b> 
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="datepicker_from">
                        </div>
                    </div>
                    <div class="col-md-3 form-group">
                        <b>To Date</b>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="datepicker_to">
                        </div>
                    </div>	
                    <div class="col-md-12">							
                        <button type="button" id="reset" class="btn btn-danger btn-flat pull-right ">Reset</button>
                        <button type="button" class="btn btn-warning btn-flat pull-right" id="viewreport" aria-expanded="true">View Report</button>
                    </div>
                    <input type="hidden" class="form-control pull-right" id="date_to">
                    <input type="hidden" class="form-control pull-right" id="date_from">
                </div>
                <!-- /.tab-pane -->
            </div>
        </div>
    </div>
    <div id="report_m" class="collapse out" aria-expanded="true">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Challan Reports</h3>
                <a id="printUrl" href="" target="_blank" id="printPageLink" onclick="print_report()"> <button type="button" class="btn btn-danger pull-right"  formtarget="_blank" ><i class="fa fa-print"></i></button></a> &nbsp;
                <a id="generateexcel" href="" target="_blank" id="printPageLink" onclick="generate_excel_report()"><button type="button" class="btn btn-danger pull-right" formtarget="_blank" ><i class="fa fa-file-excel-o">Export Excel</i></button></a>
            </div>
            <div class="box-body table-responsive">
                <table id="report_table" class="table table-responsive table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Sequence No</th>
                            <th>Challan No</th>
                            <th>Bank Name</th>
                            <th>Payment Date</th>
                            <th>Branch Name</th>
                            <th>Payment Mode</th>
                            <th>Cheque/DD</th>
                            <th>Transaction Number</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <div id="print_dcb_div" style="display:none;"></div>
</section>
<script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
$(document).ready(function () {
    $('#report_table').dataTable().fnDestroy();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#datepicker_from').datepicker({
        autoclose: true,
        dateFormat: 'dd/mm/yy',
        orientation: "bottom auto"
    });

    $('#datepicker_to').datepicker({
        autoclose: true,
        dateFormat: 'dd/mm/yy',
        orientation: "bottom auto"
    });
    $('body').on('click', '#reset', function () {
        $('#error-field').text('');
        var from_date = $('#datepicker_from').val('');
        var to_date = $('#datepicker_to').val('');
    });


    $('body').on('click', '#viewreport', function () {
        $('#error-field').text('');
        var from_date = $('#datepicker_from').val();
        var to_date = $('#datepicker_to').val();
        $('#date_from').val(from_date);
        $('#date_to').val(to_date);
        var eDate = new Date(to_date);
        var sDate = new Date(from_date);
        if (from_date == '' || to_date == '')
        {
            $("#report_m").attr("class", "panel-collapse collapse");
            $("#report_m").attr("style", "height: 0px;");
            $("#report_m").attr("aria-expanded", "false");
            $('#error-field').text("Please Select From and To dates.");
            return false;
        }
        if (to_date != '' && from_date != '' && sDate > eDate)
        {
            $("#report_m").attr("class", "panel-collapse collapse");
            $("#report_m").attr("style", "height: 0px;");
            $("#report_m").attr("aria-expanded", "false");
            $('#error-field').text("Please ensure that the To Date is greater than or equal to the From Date.");
            return false;
        }
        else
        {
            $("#report_m").attr("class", "panel-collapse collapse in");
            $("#report_m").attr("style", "");
            $("#report_m").attr("aria-expanded", "true");
            $("#viewreport").attr("data-toggle", "collapse");
            $('#report_table').dataTable().fnDestroy();
            dtTable = $('#report_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    'url': siteUrl + "/admin/challan_report_search",
                    'type': "POST",
                    'data': {
                        from_date: from_date,
                        to_date: to_date
                    },
                },
                "columns": [
                    {"data": "sequence_number"},
                    {"data": "challan_no"},
                    {"data": "bank_name"},
                    {"data": "payment_date"},
                    {"data": "branch_name"},
                    {"data": "mode_type"},
                    {"data": "cheque_dd"},
                    {"data": "transaction_number"},
                    {"data": "total_amount"}
                ]
            });
        }
    });

});
function print_report()
{
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var siteUrl = '<?php echo url('/'); ?>';
    printUrl = siteUrl + '/admin/challan_report_print' + '?from_date=' + from_date + '&to_date=' + to_date;
    $("#printUrl").attr("href", printUrl);
}

function generate_excel_report()
{        
    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();
    var siteUrl = '<?php echo url('/'); ?>';
    printPageUrl = siteUrl + '/admin/generate_challan_excel' + '?from_date=' + from_date + '&to_date=' + to_date;
    $("#generateexcel").attr("href", printPageUrl);
}
</script>
@endsection