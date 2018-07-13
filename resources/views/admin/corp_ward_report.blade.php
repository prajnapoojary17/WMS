@extends('layouts.admin_master')
@section('content')
<section class="content-header">
  <h1>
   Corp Wardwise Pending Balance Report

  </h1>
</section>
<style>
    .error_color
    {
         color: red;
    }
   
</style>
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-info">
             <span class="text-danger" style="margin-left:25px;padding-top:5px;">
                        <strong id="error-field"></strong>
                       </span>
        		<div class="box-body">
                                    <div class="col-md-3 form-group">
                                            <b>Ward</b><span class="rq">*</span>
                                            <select class="form-control select2 wardname" name="ward_id" required="required" style="width: 100%;">
                                                                      <option value="">Select</option>
                                                                     @foreach($wards as $ward)
                                                                      <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                                                     @endforeach
                                                              </select>
                                    </div>
				<div class="col-md-4 form-group">
							<b>Corp Ward</b> <span class="rq">*</span>
					 <select class="form-control select2 corpname" name="corp_ward_id" required="required" style="width: 100%;">
                                                                    <option value="">Select</option>                                                                    
                                          </select>
                                </div>
						
						<div class="col-md-2">
							<button type="button" id="view_corp_ward_wise_report" class="btn btn-warning btn-flat pull-left btn-margin">View Report</button>
							<button type="button" id="reset" class="btn btn-danger btn-flat pull-left btn-margin">Reset</button>
						</div>
				
			
				
			</div>
        </div>
        <div id="corp_ward_wise_report" style="display:none;">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Corp Wardwise Pending Balance Report Result</h3>
                                <a id="printPageUrl" href="" target="_blank" id="printPageLink" onclick="print_corp_ward()()">  <button type="button" class="btn btn-danger pull-right" id="print_button1"><i class="fa fa-print"></i></button></a>
			
			</div>
                     <div id="print_corp_ward_div" style="display:none;"></div>

			<div class="box-body table-responsive">
				<table id="corp_ward_wise_reporttable" class="table table-responsive table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Sequence No</th>
							<th>Owner Name</th>
							<th>Door No</th>
							<th>Address</th>
							<th>Meter No</th>
							<th>Phone</th>
							<th>Connection Type</th>
							<th>No Of Flats</th>
							<th>Corp Ward</th>
							<th>Connection Status</th>
							<th>Balance</th>
						</tr>
					</thead>
					
				</table>
			</div>
		</div>
        </div>
    </section>
    <script>var siteUrl = '<?php echo url('/'); ?>';</script>
<script>
      function printContent(el){
                            var restorepage = document.body.innerHTML;
                            var printcontent = document.getElementById(el).innerHTML;
                            document.body.innerHTML = printcontent;
                            window.print();
                            document.body.innerHTML = restorepage;
                            location.reload();

                    }
$(document).ready(function () {
    
                $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          })
        $('body').on('change', '.wardname', function () {
        var val = $(this).val();
        $.ajax({
            url: siteUrl + "/admin/getCorpWardForWard",
            type: 'POST',
            data: {'wardId': val},
            async: true,
            // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {                
                if (data.success == '1') {
                    console.log(data.corpWard);
                    var i = 0;
                    $('.corpname').html('');
                        $('.corpname')
                        .append($("<option></option>")
                        .attr("value",'')
                        .text("select"));                      
                    $(data.corpWard).each(function(){
                        $('.corpname')
                        .append($("<option></option>")
                        .attr("value",data.corpWard[i].id)
                        .text(data.corpWard[i].corp_name));                         
                        i++;
                    })                  
                }
                if (data.success == '0') {
                    $('.corpname').html('');
                    $('.corpname')
                        .append($("<option></option>")
                        .attr("value",'')
                        //.text("No CorpWard available")
						.text("Select")
						);  
                }
            },
        });
    });
  $('body').on('click', '#reset', function () { 
        
     var ward=$('select[name=ward_id]').val('');
     var corp_ward=$('select[name=corp_ward_id]').val('');

    });
	
	$('body').on('click', '#view_corp_ward_wise_report', function () {

       
        var ward=$('select[name=ward_id]').val();
        var corp_ward=$('select[name=corp_ward_id]').val();
        $('#error-field').text('');
       if(corp_ward=='' || ward=='')
       {
			$('#error-field').text('Both Fields are required');
			$("#corp_ward_wise_report").attr("class", "panel-collapse collapse"); 
			$("#corp_ward_wise_report").attr("style", "height: 0px;"); 
			$("#corp_ward_wise_report").attr("aria-expanded","false");
       }
       

      else
      {
        $('#corp_ward_wise_report').show();
        $("#corp_ward_wise_report").attr("class", "panel-collapse collapse in"); 
        $("#corp_ward_wise_report").attr("style", ""); 
        $("#corp_ward_wise_report").attr("aria-expanded","true");
        $('#corp_ward_wise_reporttable').dataTable().fnDestroy();
                dtTable = $('#corp_ward_wise_reporttable').DataTable({
                   "processing": true,
                   "language": {
                        "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                   "serverSide": true,
                   "ajax": {
                            'url': siteUrl + "/admin/corp_ward_report_search",
                            'type': "POST",
                             'data': {ward: ward,
                                      corp_ward: corp_ward                                     
                                  },

                            },
						   "columns":[         
							   { "data": "sequence_number" },
							   { "data": "consumer_name" },
							   { "data": "door_no"},
							   { "data": "premises_address" },
							   { "data": "meter_no"},
							   { "data": "mobile_no"},
							   { "data": "connection_name"},
							   { "data": "no_of_flats" },
							   { "data": "corp_name" },
							   { "data": "status"},
							   { "data": "total_amount" }

						   ],
						   "columnDefs": [{
								"defaultContent": "-",
								"targets": "_all"
							}]
                       });
                   }

    });

});
function print_corp_ward()
    {
      
         var ward=$('select[name=ward_id]').val();
         var corp_ward=$('select[name=corp_ward_id]').val();     
         var siteUrl = '<?php echo url('/'); ?>';
         printPageUrl = siteUrl + '/admin/corp_ward_report_print' + '?ward=' + ward+'&corp_ward='+corp_ward;
         $("#printPageUrl").attr("href", printPageUrl);

      
    } 
</script>
@endsection