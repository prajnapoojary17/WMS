@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Wardwise DCB Reports

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
							<b>Connection Type</b>

                                                        <select class="form-control select2 con_type"  id="con_type" name="con_type" style="width: 100%;">
                                                           <option value="">Select</option>
                                                         @foreach($connTypes as $connType)
                                                           <option value="{{ $connType->id }}">{{ $connType->connection_name }}</option>                                                                         @endforeach
                                                   </select>
						</div>
						<div class="col-md-3 form-group">
							<b>Ward</b>
                                                        <select class="form-control select2 wardname" name="wardname" required="required" style="width: 100%;">
                                                                                  <option value="">Select</option>
                                                                                 @foreach($wards as $ward)
                                                                                  <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                                                                 @endforeach
                                                           </select>
						</div>
						<div class="col-md-3 form-group">
							<b>From Date</b> 
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="dcb_datepicker_from">
							</div>
						</div>
						<div class="col-md-3 form-group">
							<b>To Date</b>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="dcb_datepicker_to">
							</div>
						</div>	
						<div class="col-md-12">							
							<button type="button" id="reset" class="btn btn-danger btn-flat pull-right ">Reset</button>
							<button type="button" class="btn btn-warning btn-flat pull-right" id="wardwise_dcb_view" aria-expanded="true">View Report</button>
						</div>
                                         
					</div>
					  <!-- /.tab-pane -->
		
				</div>
			</div>
		</div>
		<div id="dcb_report_m" class="collapse out" aria-expanded="true">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">Monthly Wardwise DCB Reports</h3>
					      <a id="printPageUrl" href="" target="_blank" id="printPageLink" onclick="print_dcb_report()"> <button type="button" class="btn btn-danger pull-right"  formtarget="_blank" ><i class="fa fa-print"></i></button></a>
				</div>
				<div class="box-body table-responsive">
					<table id="dcb_report_month" class="table table-responsive table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Ward No</th>
								<th>Connection Type</th>
								<th>Total INST</th>
								<th>Live</th>
								<th>Billed</th>
								<th>Consumption</th>
								<th>OB</th>
								<th>WC</th>
								<th>OC</th>
								<th>Penalty</th>
								<th>Demand</th>
								<th>Collection</th>
								<th>CB</th>
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
        /*  function printContent(el){
                            var restorepage = document.body.innerHTML;
                            var printcontent = document.getElementById(el).innerHTML;
                            document.body.innerHTML = printcontent;
                            setInterval(function(){
                             window.location.reload(true);
                          }, 50);
                            window.print();
                          document.body.innerHTML = restorepage;
                           
                    } */
          function printContent(el){
                            var restorepage = document.body.innerHTML;
                            var printcontent = document.getElementById(el).innerHTML;
                            document.body.innerHTML = printcontent;
                            window.print();
                            document.body.innerHTML = restorepage;
                            location.reload();

                    }

 $(document).ready( function() {

  $('#dcb_report_month').dataTable().fnDestroy();
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
     $('#dcb_datepicker_from').datepicker({
      autoclose: true,
          dateFormat: 'dd/mm/yy',
          orientation: "bottom auto"
    });

        $('#dcb_datepicker_to').datepicker({
      autoclose: true,
       dateFormat: 'dd/mm/yy',
      orientation: "bottom auto"
    });
    $('body').on('click', '#reset', function () { 
       
      $('#error-field').text('');
      var ward=$('select[name=wardname]').val('');
      var con_type=$('select[name=con_type]').val('');
      var from_date= $('#dcb_datepicker_from').val('');
      var to_date= $('#dcb_datepicker_to').val('');
    });
    $('body').on('click', '#wardwise_dcb_view', function () {

      $('#error-field').text('');
      var ward=$('select[name=wardname]').val();
      var con_type=$('select[name=con_type]').val();
      var from_date= $('#dcb_datepicker_from').val();
      var to_date= $('#dcb_datepicker_to').val();
      var eDate = new Date(to_date);
      var sDate = new Date(from_date);
      
    if(ward==''&& con_type=='' && from_date=='' && to_date=='' )
      {
	  
	   $("#dcb_report_m").attr("class", "panel-collapse collapse in"); 
           $("#dcb_report_m").attr("style", ""); 
           $("#dcb_report_m").attr("aria-expanded","true");
           $("#wardwise_dcb_view").attr("data-toggle", "collapse");
           $('#dcb_report_month').dataTable().fnDestroy();
                dtTable = $('#dcb_report_month').DataTable({
                   "processing": true,
                   "serverSide": true,
                   "ajax": {
                            'url': siteUrl + "/admin/dcb_report_search",
                            'type': "POST",
                             'data': {
                                      ward: 0,
                                      con_type: 0,
                                      from_date: 0,
                                      to_date:0
                                  },
                             
                            },
        
                                           "columns":[         
                                               { "data": "ward_name" },
                                               { "data": "connection_name" },
                                               { "data": "total_installation"},
                                               { "data": "live"},
                                               { "data": "bill_count" },
                                               { "data": "total_unit_used" },
                                               { "data": "old_balance"},
                                               { "data": "water_charge"},
                                               { "data": "other_charges" },
                                               { "data": "penalty" },
                                               { "data": "demand"},
                                               { "data": "collection"},
                                               { "data": "current_balance"}
                                           ]

                       });
      } 
      if(to_date!= '' && from_date!= '' && sDate> eDate)
          {
			$("#dcb_report_m").attr("class", "panel-collapse collapse"); 
			$("#dcb_report_m").attr("style", "height: 0px;"); 
			$("#dcb_report_m").attr("aria-expanded","false");
           $('#error-field').text("Please ensure that the To Date is greater than or equal to the From Date.");
           return false;
          }
          
      else
      {
		$("#dcb_report_m").attr("class", "panel-collapse collapse in"); 
		$("#dcb_report_m").attr("style", ""); 
		$("#dcb_report_m").attr("aria-expanded","true");
               $("#wardwise_dcb_view").attr("data-toggle", "collapse");
               $('#dcb_report_month').dataTable().fnDestroy();
                dtTable = $('#dcb_report_month').DataTable({
                   "processing": true,
                   "serverSide": true,
                   "ajax": {
                            'url': siteUrl + "/admin/dcb_report_search",
                            'type': "POST",
                             'data': {
                                      ward: ward,
                                      con_type: con_type,
                                      from_date: from_date,
                                      to_date: to_date
                                  },
                             
                            },
        
                                           "columns":[         
                                               { "data": "ward_name" },
                                               { "data": "connection_name" },
                                               { "data": "total_installation"},
                                               { "data": "live"},
                                               { "data": "bill_count" },
                                               { "data": "total_unit_used" },
                                               { "data": "old_balance"},
                                               { "data": "water_charge"},
                                               { "data": "other_charges" },
                                               { "data": "penalty" },
                                               { "data": "demand"},
                                               { "data": "collection"},
                                               { "data": "current_balance"}
                                           ]

                       });
                   }
   
     });

  });
  function print_dcb_report()
  {
      var ward=$('select[name=wardname]').val();
      var con_type=$('select[name=con_type]').val();
      var from_date= $('#dcb_datepicker_from').val();
      var to_date= $('#dcb_datepicker_to').val();

              var siteUrl = '<?php echo url('/'); ?>';
              printPageUrl = siteUrl + '/admin/dcb_report_print' + '?ward=' + ward+'&con_type='+con_type+'&from_date='+from_date+'&to_date='+to_date;
               $("#printPageUrl").attr("href", printPageUrl);
  }
 
</script>
@endsection