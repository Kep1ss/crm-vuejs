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
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Role</b>
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
            <td align="center"
                valign="top">
                @if($item->role === 0)
                    SuperAdmin
                @elseif($item->role === 1)
                    Managaer Nasional
                @elseif($item->role === 2)
                    Manager Area
                @elseif($item->role === 3)
                    Kaper
                @elseif($item->role === 4)
                    Spv
                @elseif($item->role === 5)
                    Sales
                @elseif($item->role === 6)
                    Kotele
                @elseif($item->role === 7)
                    Tele Markerting
                @elseif($item->role == 8)
                    Admin Nasional 
                @elseif($item->role == 9)
                    Admin Area
                @elseif($item->role == 10)
                    Admin Kaper
                @else
                    Tidak Diketahui
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>