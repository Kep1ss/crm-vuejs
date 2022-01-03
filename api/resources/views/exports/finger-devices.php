<table>
    <thead>
        <tr>                         
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Nama</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Address</b>
            </td>   
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->name}} 
            </td>
            <td align="center"
                valign="top">
                {{$item->address ?? '-'}}
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>