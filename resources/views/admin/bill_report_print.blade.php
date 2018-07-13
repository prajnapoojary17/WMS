<style>
    .amount_align
    {
        text-align: right;
    }
  td[colspan="18"] {
    text-align: center;
}

</style>
<table id="consumer_billing_report_table" border="1" style="width:2075px;">
    <thead>
            	<tr>
								<th>Owner Details</th>
								<th>Reading-Y/M</th>
								<th>Previous Reading Date</th>
								<th>Bill Date</th>
								<th>Previous Reading</th>
								<th>Current reading</th>
								<th>Consumption</th>
								<th>Water Charges</th>
								<th>Other Charges</th>
								<th>Penalty</th>
								<th>Demand</th>
								<th>Status</th>
								<th>Bill No</th>
								<th>OBA</th>
								<th>CBA</th>
								<th>Paid Amount</th>
								<th>Pay Date</th>
								<th>Payment Number</th>
						
							</tr>
    </thead>

    
    <tbody>
        <?php
         $myNewArray = [];

           $i = 0;
           $j=0;
            foreach($report_search_data as $key => $values )
            {
                $seqNumber = $values['sequence_number'];

                if($j  == 0)
                {
                    $myNewArray[$i]['basic']['name'] = $values['name'];
                    $myNewArray[$i]['basic']['sequence_number'] = $values['sequence_number'];
                    $myNewArray[$i]['basic']['connection_date'] = $values['connection_date'];
                    $myNewArray[$i]['basic']['comm_address'] = $values['comm_address'];
                    $myNewArray[$i]['basic']['meter_no'] = $values['meter_no'];
                    $myNewArray[$i]['basic']['connection_name'] = $values['connection_name'];
                    $myNewArray[$i]['basic']['ward_name'] = $values['ward_name'];
                    $myNewArray[$i]['basic']['corp_name'] = $values['corp_name'];
                    $myNewArray[$i]['basic']['agent_name'] = $values['agent_name'];
                    $myNewArray[$i]['details'][] = $values;    
                    $j++;
                }
                else if($myNewArray[$i]['basic']['sequence_number'] == $values['sequence_number'])
                {

                    $myNewArray[$i]['details'][] = $values;
                }
                else
                {
                    $i++;
                    $myNewArray[$i]['basic']['name'] = $values['name'];
                    $myNewArray[$i]['basic']['sequence_number'] = $values['sequence_number'];
                    $myNewArray[$i]['basic']['connection_date'] = $values['connection_date'];
                    $myNewArray[$i]['basic']['comm_address'] = $values['comm_address'];
                    $myNewArray[$i]['basic']['meter_no'] = $values['meter_no'];
                    $myNewArray[$i]['basic']['connection_name'] = $values['connection_name'];
                    $myNewArray[$i]['basic']['ward_name'] = $values['ward_name'];
                    $myNewArray[$i]['basic']['corp_name'] = $values['corp_name'];
                    $myNewArray[$i]['basic']['agent_name'] = $values['agent_name'];
                    $myNewArray[$i]['details'][] = $values;   

                } 

            }
            ?>

            @foreach($myNewArray as $key => $values)
            
            
               <tr>
                  <td width="200" rowspan="<?php echo count($values['details']);?>" class="vert-top">
                        <b>Owner </b>:     {{  $values['basic']['name'] }}<br><hr>
                        <b>Connection Date</b> :    {{  $values['basic']['connection_date'] }}
                        <hr>
                        <b>Address </b>:   {{  $values['basic']['comm_address'] }}

                        <hr>
                        <b>Meter No</b> :   {{  $values['basic']['meter_no'] }}
                        <hr>
                        <b>Tariff</b> :   {{  $values['basic']['connection_name'] }}
                        <hr>
                        <b>Ward</b> :   {{  $values['basic']['ward_name'] }}
                        <hr>
                        <b>Corp Ward</b> :   {{  $values['basic']['corp_name'] }}
                        <hr>
                        <b>Agent</b> :   {{  $values['basic']['agent_name'] }}
                        <hr>
                        <b>Sequence Number</b> :   {{  $values['basic']['sequence_number'] }}
                </td>
              @foreach($values['details'] as $key1 => $value1)
             
                  @if ($key1 == 0) 
                 
                <td> {{ $value1['year'] }} / {{$value1['month'] }}</td> 
                <td> {{ $value1['previous_billing_date'] }}</td> 
                <td> {{ $value1['date_of_reading'] }}</td> 
                <td> {{ $value1['previous_reading'] }}</td> 
                <td> {{ $value1['current_reading'] }}</td> 
                <td> {{ $value1['total_unit_used'] }}</td> 
                <td> {{ $value1['water_charge'] }}</td> 
                <td> {{ $value1['other_charges'] }}</td> 
                <td> {{ $value1['penalty'] }}</td> 
                <td> {{ $value1['total_unit_used'] }}</td> 
                <td> {{ $value1['meter_status'] }}</td> 
                <td> {{ $value1['bill_no'] }}</td> 
                <td> {{ $value1['arrears'] }}</td> 
                <td> {{ $value1['total_amount'] }}</td> 
                <td> {{ $value1['paid_amount'] }}</td> 
                <td> {{ $value1['payment_date'] }}</td> 
                <td> {{ $value1['transaction_number'] }}</td> 

               </tr>  
                 @else
     
               <tr>
                <td> {{ $value1['year'] }} /  {{ $value1['month'] }}</td> 
                <td> {{ $value1['previous_billing_date'] }}</td> 
                <td> {{ $value1['date_of_reading'] }}</td> 
                <td> {{ $value1['previous_reading'] }}</td> 
                <td> {{ $value1['current_reading'] }}</td> 
                 <td> {{ $value1['total_unit_used'] }}</td> 
                <td> {{ $value1['water_charge'] }}</td> 
                <td> {{ $value1['other_charges'] }}</td> 
                <td> {{ $value1['penalty'] }}</td> 
                <td> {{ $value1['total_unit_used'] }}</td> 
                <td> {{ $value1['meter_status'] }}</td> 
                <td> {{ $value1['bill_no'] }}</td> 
                <td> {{ $value1['arrears'] }}</td> 
                <td> {{ $value1['total_amount'] }}</td> 
                <td> {{ $value1['paid_amount'] }}</td> 
                <td> {{ $value1['payment_date'] }}</td> 
                <td> {{ $value1['transaction_number'] }}</td> 
               </tr>
          @endif

               @endforeach  

             @endforeach
    </tbody>
  
</table>
