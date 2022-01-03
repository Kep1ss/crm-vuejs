<table>
    <thead>
        <tr>
            <td style="width:50px"
                height="30px"
                valign="top"
                align="center">
                <b>Kode</b>
            </td>                        
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Nama</b>
            </td>   
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->code}} 
            </td>
            <td align="center"
                valign="top">
                {{$item->name ?? '-'}}
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>