<table id="dcb_report_month" border="1">
    <thead>
        <tr>
            <th>Sequence No</th>
            <th>challan_no</th>
            <th>bank_name</th>
            <th>payment_date</th>
            <th>branch_name</th>
            <th>payment_mode</th>
            <th>cheque_dd</th>
            <th>transaction_number</th>
            <th>total_amount</th>            
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $values)
        <tr>
            <td>{{ $values['sequence_number'] }}</td>
            <td>{{ $values['challan_no'] }}</td>
            <td>{{ $values['bank_name'] }}</td>
            <td>{{ $values['payment_date'] }}</td>
            <td>{{ $values['branch_name'] }}</td>
            <td>{{ $values['payment_mode'] }}</td>
            <td>{{ $values['cheque_dd'] }}</td>
            <td>{{ $values['transaction_number'] }}</td>
            <td>{{ $values['total_amount'] }}</td>          
        </tr>
        @endforeach  
    </tbody>
</table>





