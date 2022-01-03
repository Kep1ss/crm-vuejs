<table>
    <thead>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Username</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Nama Lengkap</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Email</b>
            </td>   
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->username ?? '-'}} 
            </td>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->fullname ?? '-'}} 
            </td>
            <td align="center"
                valign="top">
                {{$item->email ?? '-'}}
            </td>            
        </tr>
        @endforeach
    </tbody>
</table>