<table>
    <thead>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Name</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Kode</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Division</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Position</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Graduate</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Join Date</b>
            </td>   
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->name ?? '-'}} 
            </td>
            <td align="center"
                valign="top">
                {{$item->code ?? '-'}}
            </td>            
            <td align="center"
                valign="top">
                {{$item->division->name ?? '-'}}
            </td>        
            <td align="center"
                valign="top">
                {{$item->position->name ?? '-'}}
            </td>        
            <td align="center"
                valign="top">
                {{$item->graduate ?? '-'}}
            </td>        
            <td align="center"
                valign="top">
                {{$item->join_date ?? '-'}}
            </td>        
        </tr>
        @endforeach
    </tbody>
</table>