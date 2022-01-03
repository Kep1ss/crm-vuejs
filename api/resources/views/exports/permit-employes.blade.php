<table>
    <thead>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Description</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Employe</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Type</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Date Start</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Date End</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Days Permit</b>
            </td>   
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->description ?? '-'}} 
            </td>     
            <td height="50px"
                align="center"
                valign="top">
                {{$item->employe->name ?? '-'}} 
            </td>     
            <td height="50px"
                align="center"
                valign="top">
                {{$item->permit_type->name ?? '-'}} 
            </td>       
            <td height="50px"
                align="center"
                valign="top">
                {{$item->permit_date_start ?? '-'}} 
            </td>       
            <td height="50px"
                align="center"
                valign="top">
                {{$item->permit_date_end ?? '-'}} 
            </td>    
            <td height="50px"
                align="center"
                valign="top">
                {{$item->days_permit ?? '-'}} 
            </td>                     
        </tr>
        @endforeach
    </tbody>
</table>