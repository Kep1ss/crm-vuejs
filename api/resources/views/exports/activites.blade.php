<table>
    <thead>
        <tr>
            <td style="width:50px"
                height="30px"
                valign="top"
                align="center">
                <b>Dibuat Pada</b>
            </td>                        
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Deskripsi</b>
            </td>   
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->created_at}} 
            </td>
            <td align="center"
                valign="top">
                {{$item->description ?? '-'}}
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>