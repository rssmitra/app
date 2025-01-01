<?php echo $header; ?>
<hr>
<br>

<p style="text-align:center; font-weight: bold; font-size: 16px">
    PENGKAJIAN RESIKO JATUH ANAK
</p>
<style>
    table tr td{
        padding: 2px; 
    }
</style>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<table border="1" width="100%" class="table">
    <tbody>
        <tr>
            <td style="text-align:center; font-weight: bold; font-size: 20px; color: white; background-color: black" width="100%" colspan="9">
            HUMPTY DUMPTY
            </td>
        </tr>
        <tr>
            <td style="text-align:center; vertical-align:middle" width="8%" rowspan="3">
                <strong>FAKTOR<br>
                RESIKO</strong>
            </td>
            <td rowspan="3" style="text-align:center; vertical-align:middle" >
                <strong>SKALA</strong>
            </td>
            <td width="2%" rowspan="3" style="text-align:center; vertical-align:middle">
                <strong>POINT</strong>
            </td>
            <td style="text-align:center;" width="2%" colspan="6">
                <strong>SKOR PASIEN</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" width="3%"><strong>TANGAL</strong></td></b>
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a1]" id="a1" onchange="fillthis('a1')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a2]" id="a2" onchange="fillthis('a2')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a3]" id="a3" onchange="fillthis('a3')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a4]" id="a4" onchange="fillthis('a4')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a5]" id="a5" onchange="fillthis('a5')" class="input_type"></td> 
        </tr>
        <tr>
            <td style="text-align: center;" width="3%"><strong>JAM</strong></td></b>
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a6]" id="a6" onchange="fillthis('a6')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a7]" id="a7" onchange="fillthis('a7')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a8]" id="a8" onchange="fillthis('a8')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a9]" id="a9" onchange="fillthis('a9')" class="input_type"></td> 
            <td align="center"><input type="text" style="width:40px; text-align: center" name="form_21[a10]" id="a10" onchange="fillthis('a10')" class="input_type"></td> 
        </tr>
        <tr>
            <td class="sub_data" rowspan="5">Umur</td>
        </tr>
        <tr>
            <td class="sub_data">Kurang dari 3 tahun</td>
            <td align="center">4</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b1]" id="b1" onchange="fillthis('b1')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b3]" id="b3" onchange="fillthis('b3')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b4]" id="b4" onchange="fillthis('b4')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b5]" id="b5" onchange="fillthis('b5')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">3 Tahun - 7 tahun</td>
            <td  align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b6]" id="b6" onchange="fillthis('b6')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b7]" id="b7" onchange="fillthis('b7')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b8]" id="b8" onchange="fillthis('b8')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b9]" id="b9" onchange="fillthis('b9')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b10]" id="b10" onchange="fillthis('b10')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">7 Tahun - 13 tahun</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[bb11]" id="bb11" onchange="fillthis('bb11')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b12]" id="b12" onchange="fillthis('b12')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b13]" id="b13" onchange="fillthis('b13')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b14]" id="b14" onchange="fillthis('b14')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b15]" id="b15" onchange="fillthis('b15')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">&gt; 13 tahun</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[e1]" id="e1" onchange="fillthis('e1')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b16]" id="b16" onchange="fillthis('b16')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b17]" id="b17" onchange="fillthis('b17')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b18]" id="b18" onchange="fillthis('b18')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b19]" id="b19" onchange="fillthis('b19')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data" rowspan="3">Jenis Kelamin</td>
        </tr>
        <tr>
            <td class="sub_data">Laki-laki</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b20]" id="b20" onchange="fillthis('b20')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b21]" id="b21" onchange="fillthis('b21')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b22]" id="b22" onchange="fillthis('b22')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b23]" id="b23" onchange="fillthis('b23')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b24]" id="b24" onchange="fillthis('b24')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Wanita</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b25]" id="b25" onchange="fillthis('b25')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b26]" id="b26" onchange="fillthis('b26')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b27]" id="b27" onchange="fillthis('b27')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b28]" id="b28" onchange="fillthis('b28')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b29]" id="b29" onchange="fillthis('b29')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data" rowspan="5">Diagnosa</td>
        </tr>
        <tr>
            <td class="sub_data">Neurologi</td>
            <td align="center">4</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b30]" id="b30" onchange="fillthis('b30')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b31]" id="b31" onchange="fillthis('b31')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b32]" id="b32" onchange="fillthis('b32')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b33]" id="b33" onchange="fillthis('b33')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b34]" id="b34" onchange="fillthis('b34')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Respiratory, dehidrasi, anemia, anoreksia,syncope</td>
            <td  align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b35]" id="b35" onchange="fillthis('b35')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b36]" id="b36" onchange="fillthis('b36')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b37]" id="b37" onchange="fillthis('b37')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b38]" id="b38" onchange="fillthis('b38')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b39]" id="b39" onchange="fillthis('b39')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Perilaku</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b40]" id="b40" onchange="fillthis('b40')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b41]" id="b41" onchange="fillthis('b41')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b43]" id="b43" onchange="fillthis('b43')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b42]" id="b42" onchange="fillthis('b42')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b44]" id="b44" onchange="fillthis('b44')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Lain-lain</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b45]" id="b45" onchange="fillthis('b45')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b46]" id="b46" onchange="fillthis('b46')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b47]" id="b47" onchange="fillthis('b47')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b48]" id="b48" onchange="fillthis('b48')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b200]" id="b200" onchange="fillthis('b200')" class="input_type">
            </td>   
        </tr>
        <tr>
            <td class="sub_data" rowspan="4">Gangguan Kognitif</td>
        </tr>
        <tr>
            <td class="sub_data">Keterbatasan daya pikir</td>
            <td align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b49]" id="b49" onchange="fillthis('b49')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b50]" id="b50" onchange="fillthis('b50')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b51]" id="b51" onchange="fillthis('b51')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b52]" id="b52" onchange="fillthis('b52')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b53]" id="b53" onchange="fillthis('b53')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Pelupa, berkurangnya orientasi sekitar</td>
            <td  align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b54]" id="b54" onchange="fillthis('b54')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b55]" id="b55" onchange="fillthis('b55')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b56]" id="b56" onchange="fillthis('b56')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b57]" id="b57" onchange="fillthis('b57')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b58]" id="b58" onchange="fillthis('b58')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Dapat menggunakan daya pikir tanpa hambatan</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b59]" id="b59" onchange="fillthis('b59')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b60]" id="b60" onchange="fillthis('b60')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b61]" id="b61" onchange="fillthis('b61')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b62]" id="b62" onchange="fillthis('b62')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b63]" id="b63" onchange="fillthis('b63')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data" rowspan="4">Faktor Lingkungan</td>
        </tr>
        <tr>
            <td class="sub_data">Pasien menggunakan alat bantu / bayi balita dalam ayunan</td>
            <td  align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b64]" id="b64" onchange="fillthis('b64')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b65]" id="b65" onchange="fillthis('b65')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b66]" id="b66" onchange="fillthis('b66')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b67]" id="b67" onchange="fillthis('b67')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b68]" id="b68" onchange="fillthis('b68')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Pasien di tempat tidur standar</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b69]" id="b69" onchange="fillthis('b69')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b70]" id="b70" onchange="fillthis('b70')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b71]" id="b71" onchange="fillthis('b71')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b72]" id="b72" onchange="fillthis('b72')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b73]" id="b73" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Area pasien Rawat Jalan</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b74]" id="b74" onchange="fillthis('b74')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b75]" id="b75" onchange="fillthis('b75')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b76]" id="b76" onchange="fillthis('b76')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b77]" id="b77" onchange="fillthis('b77')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b78]" id="b78" onchange="fillthis('b78')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data" rowspan="4">Respon terhadap pembedahan, Sedasai dan Anestesi</td>
        </tr>
        <tr>
            <td class="sub_data">Dalam 24 jam</td>
            <td align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b79]" id="b79" onchange="fillthis('b79')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b80]" id="b80" onchange="fillthis('b80')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b81]" id="b81" onchange="fillthis('b81')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b82]" id="b82" onchange="fillthis('b82')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b83]" id="b83" onchange="fillthis('b83')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Dalam 48 jam</td>
            <td  align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b84]" id="b84" onchange="fillthis('b84')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b85]" id="b85" onchange="fillthis('b85')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b86]" id="b86" onchange="fillthis('b86')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b87]" id="b87" onchange="fillthis('b87')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b88]" id="b88" onchange="fillthis('b88')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">&gt; 48 jam / tidak ada respon</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b89]" id="b89" onchange="fillthis('b89')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b90]" id="b90" onchange="fillthis('b90')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b91]" id="b91" onchange="fillthis('b91')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b92]" id="b92" onchange="fillthis('b92')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b93]" id="b93" onchange="fillthis('b93')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data" rowspan="4">Penggunaan Obat-obatan</td>
        </tr>
        <tr>
            <td class="sub_data">Penggunaan bersamaan sedative, barbiturate, anti-depresan, diuretik, narkotik</td>
            <td align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b94]" id="b94" onchange="fillthis('b94')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b95]" id="b95" onchange="fillthis('b95')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b96]" id="b96" onchange="fillthis('b96')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b97]" id="b97" onchange="fillthis('b97')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b98]" id="b98" onchange="fillthis('b98')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Salah satu obat di atas</td>
            <td  align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b99]" id="b99" onchange="fillthis('b99')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b100]" id="b100" onchange="fillthis('b100')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b101]" id="b101" onchange="fillthis('b101')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b102]" id="b102" onchange="fillthis('b102')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b103]" id="b103" onchange="fillthis('b103')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Obat-obatan lainnya / tanpa obat</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b104]" id="b104" onchange="fillthis('b104')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b105]" id="b105" onchange="fillthis('b105')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b106]" id="b106" onchange="fillthis('b106')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b107]" id="b107" onchange="fillthis('b107')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b108]" id="b108" onchange="fillthis('b108')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td align="center"; colspan="4">
                <strong>TOTAL SKOR</strong>
            </td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b109]" id="b109" onchange="fillthis('b109')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b110]" id="b110" onchange="fillthis('b110')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b111]" id="b111" onchange="fillthis('b111')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b112]" id="b112" onchange="fillthis('b112')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b113]" id="b113" onchange="fillthis('b113')" class="input_type">
            </td>    
        </tr>
          
        <tr>
        <td class="sub_data" colspan="9">
            <strong >Kriteria:</strong>
            <label>
                <input type="checkbox" class="ace" name="r_r" id="r_r" onclick="checkthis('r_r')">
                <span class="lbl">0 - 7 (Resiko Rendah) </span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="r_t" id="r_t" onclick="checkthis('r_t')">&ge; 
                <span class="lbl"> 12 (Resiko Tinggi)</span>
            </label>
        </td>
        </tr>
    </tbody>
</table>
<table border="1" width="100%">
        <tbody>
            <tr>
                <td   style="text-align:center; font-weight: bold; font-size: 20px; color: white; background-color: black" width="100%" colspan="2">
                    PENCEGAHAN PASIEN JATUH
                </td>
            </tr>
            <tr>
                <td >
                    <p style="text-align:center;">
                        <strong>RESUKO RENDAH</strong>
                    </p>
                </td>
                <td >
                    <p style="text-align:center;">
                        <strong>RESUKO TINGGI</strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <ol>
                        <li>Pastikan bel mudah dijangkau</li>
                        <li>Roda tempat tidur pada posisi terkunci</li>
                        <li>Pagar pengaman tempat tiduk dinaikkan</li>
                        <li>Lampu toilet cukup terang</li>
                        <li>Lakukan asesmen ulang setiap ada perubahan kondisi pasien</li>
                    </ol>
                </td>
                <td>
                    <ol>
                        <li>Lakukan semua pedoman pencegahan untuk resiko rendah</li>
                        <li>Pasangkan tanda resiko jatuh pada pergelangan tangan <b>(stiker kuning)</b></li>
                        <li>Tempat tanda resiko jatuh pada daftar nama pasien di nurse station</li>
                        <li>Beri tanda resiko jatuh pada tempat tidur pasien <b>(segitiga kuning)</b></li>
                        <li>Posisi tempat tidur pada posisi terendah</li>
                        <li>Kunjungi dan monitor pasien per 2 jam</li>
                        <li>Tempatkan pasien di kamar yang paling dekat nurse station(jika mungkin)</li>
                        <li>Beri tahu pasien jika ingin BAK / Kencing supaya minta bantuan</li>
                        <li>Lakukan asesmen resiko jatuh sebelum di transfer</li>
                    </ol>
                </td>
            </tr>
        </tbody>
</table>

<br>
<hr>
<?php echo $footer; ?>