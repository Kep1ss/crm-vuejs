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
                <b>Value</b>
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
                {{$item->value ?? '-'}}
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>