
	<table id="corp_ward_wise_reporttable" border="1" class="table table-responsive table-bordered table-hover table-striped">
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
					
			
        <tbody>
                    @foreach($corp_ward_report_array as $key => $values)
                <tr>
                        <td>{{ $values['sequence_number'] }}</td>
                        <td>{{ $values['consumer_name'] }}</td>
                        <td>{{ $values['door_no'] }}</td>
                        <td>{{ $values['comm_address'] }}</td>
                         <td>{{ $values['meter_no'] }}</td>
                        <td>{{ $values['comm_mobile_no'] }}</td>
                        <td>{{ $values['connection_name'] }}</td>
                        <td>{{ $values['no_of_flats'] }}</td>
                         <td>{{ $values['corp_name'] }}</td>
                        <td>{{ $values['status'] }}</td>
                        <td>{{ $values['total_amount'] }}</td>
                      
                </tr>
                  @endforeach  

        </tbody>
</table>


    
            
            
            