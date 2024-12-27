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

<table border="1" width="100%">
    <tbody>
        <tr>
            <td style="text-align:center; font-weight: bold; font-size: 20px; color: white; background-color: black" width="100%" colspan="9">
            HUMPTY DUMPTY
            </td>
        </tr>
        <tr>
            <td style="text-align:center;" width="8%" rowspan="3">
                <strong>FAKTOR<br>
                RESIKO</strong>
            </td>
            <td rowspan="3" >
                <strong>SKALA</strong>
            </td>
            <td width="2%" rowspan="3">
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
                <input type="text" style="width:40px; text-align: center" name="b1" id="b1" onchange="fillthis('b1')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">3 Tahun - 7 tahun</td>
            <td  align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">7 Tahun - 13 tahun</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">&gt; 13 tahun</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Wanita</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Respiratory, dehidrasi, anemia, anoreksia,syncope</td>
            <td  align="center">3</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Perilaku</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Lain-lain</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Pelupa, berkurangnya orientasi sekitar</td>
            <td  align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Dapat menggunakan daya pikir tanpa hambatan</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Pasien di tempat tidur standar</td>
            <td align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Area pasien Rawat Jalan</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Dalam 48 jam</td>
            <td  align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">&gt; 48 jam / tidak ada respon</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
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
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Salah satu obat di atas</td>
            <td  align="center">2</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td class="sub_data">Obat-obatan lainnya / tanpa obat</td>
            <td align="center">1</td>
            <td style="background-color: gray"></td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[b2]" id="b2" onchange="fillthis('b2')" class="input_type">
            </td>    
        </tr>
        <tr>
            <td align="center"; colspan="4">
                <strong>TOTAL SKOR</strong>
            </td>
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[c5]" id="c5" onchange="fillthis('c5')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[c4]" id="c4" onchange="fillthis('c4')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[c3]" id="c3" onchange="fillthis('c3')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[c2]" id="c2" onchange="fillthis('c2')" class="input_type">
            </td>    
            <td align="center">
                <input type="text" style="width:40px; text-align: center" name="form_21[c1]" id="c1" onchange="fillthis('c1')" class="input_type">
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