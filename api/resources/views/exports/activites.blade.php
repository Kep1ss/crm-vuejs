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
            <td style="width:100px"
                valign="top"  
                align="center">
                Detail
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
            <td align="center"
                valign="top">
                Table : {{$item->property ? $itme->property->table ?? '-' : '-'}} <br/>
                Nama : {{$item->property ? $item->property->name  ?? '-' : '-'}}<br/>
                User : {{$item->causer ? $item->causer->username ?? '-' : '-'}}
            </td>        
        </tr>
        @endforeach
    </tbody>
</table>