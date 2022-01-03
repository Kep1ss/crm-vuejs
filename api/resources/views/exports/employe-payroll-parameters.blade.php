<table>
    <thead>
        <tr>                            
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Nama Pegawai</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Kode Pegawai</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Parameter Gaji</b>
            </td>  
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Metode Pengajian</b>
            </td>   
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Hari Kerja</b>
            </td>  
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Persenan</b>
            </td>  
            <td style="width:100px"
                valign="top"
                align="center">
                <b>Jumlah</b>
            </td>  
        </tr>
    </thead>

    <tbody>
        @foreach($data as $item)
        <tr>
            <td height="50px"
                align="center"
                valign="top">
                {{$item->employe->name ?? '-'}} 
            </td>
            <td align="center"
                valign="top">
                {{$item->employe->code ?? '-'}}
            </td>            
            <td align="center"
                valign="top">
                {{$item->payroll_parameter->name ?? '-'}}
            </td> 
            <td align="center"
                valign="top">
                {{$item->payroll_method ?? '-'}}
            </td>   
            <td align="center"
                valign="top">
                {{$item->workday ?? '-'}}
            </td>    
            <td align="center"
                valign="top">
                {{$item->percentage ?? '-'}}
            </td>    
            <td align="center"
                valign="top">
                {{$item->amount ?? '-'}}
            </td>  
        </tr>
        @endforeach
    </tbody>
</table>