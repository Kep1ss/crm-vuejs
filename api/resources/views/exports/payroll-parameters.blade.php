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
                <b>Type Parameter</b>
            </td>               
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td align="center"
                valign="top">
                {{$item->name ?? '-'}}
            </td>            
            <td align="center"
                valign="top">
                {{$item->parameter_type ?? '-'}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>