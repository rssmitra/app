<script>

jQuery(function($) {  

  $('.date-picker').datepicker({    
    autoclose: true,    
    todayHighlight: true    
  })  
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){    
    $(this).prev().focus();    
  });  

    $('#dokter_igd').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                    return item;
                }));
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_igd').val(label_item);
      }
    });

    $('#dokter_dpjp').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getAllDokter",
              data: { keyword:query },            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                    return item;
                }));
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#dokter_dpjp').val(label_item);
      }
    });

    $('#diagnosa_utama_form_36').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                    
                }
            });
        },
        afterSelect: function (item) {
            // do what is needed with item
            var label_item=item.split(':')[1];
            var val_item=item.split(':')[0];
            console.log(val_item);
            $('#diagnosa_utama_form_36').val(label_item);
        }

    });

});

</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 16px;"><b>CEK LIST SERAH TERIMA PASIEN PRA BEDAH</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->

<table border="1" width="100%">
<tr>
    <td style="font-weight: bold; width:20%">  Diagnosis : <input type="text" style="text-align: center; width: 70px"  name="form_37[de1]" id="de1" onchange="fillthis('de1')" class="input_type" value=""></td>
    <td style="font-weight: bold; width:60%"> DPJP : <input type="text" style="text-align: center"  name="form_37[dpjp]" id="dpjp" onchange="fillthis('dpjp')" class="input_type" value=""></td>
    <td style="font-weight: bold; width:20%"> Tanggal : <input type="text" style="text-align: center; width: 70px"  name="form_37[tgl]" id="tgl" onchange="fillthis('tgl')" class="input_type" value=""></td>
</tr>

</table>
<table border="1" width="100%">
    <tr>
        <td style="font-weight: bold;text-align:center;height: 50px" valign="center" width="1%"> No.</td>
        <td style="font-weight: bold;text-align:center;height: 50px" valign="center" width="10%"> Tindakan</td>
        <td style="font-weight: bold;text-align:center;height: 50px" valign="top" width="3%"> Ya <br> (V)</td>
        <td style="font-weight: bold;text-align:center;height: 50px" valign="top" width="3%"> Tidak <br> (X) </td>
        <td style="font-weight: bold;text-align:center;height: 50px" valign="center" width="10%"> Keterangan </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 1 </td>
        <td> &nbsp; Gelang Identitas Terpasang Pada Pasien</td>
        <td style="text-align: center"> 
            <label>
            <input type="checkbox" class="ace" name="form_37[GI]" id="GI" onclick="checkthis('GI')">
            <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[TP]" id="TP" onclick="checkthis('TP')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="text" style="text-align: center"  name="form_37[a1]" id="a1" onchange="fillthis('a1')" class="input_type" value="">
                 <span class="lbl"></span>
            </label> 
        </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 2 </td>
        <td> &nbsp; Informed Consent Bedah </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[I]" id="I" onclick="checkthis('I')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[C]" id="C" onclick="checkthis('C')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a2]" id="a2" onchange="fillthis('a2')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 3 </td>
        <td> &nbsp; Informed Consent Anestesi </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[CA]" id="CA" onclick="checkthis('CA')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> 
            <label>
                <input type="checkbox" class="ace" name="form_37[AC]" id="AC" onclick="checkthis('AC')">
                <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a3]" id="a3" onchange="fillthis('a3')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 4 </td>
        <td> &nbsp; Puasa </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[PU]" id="PU" onclick="checkthis('PU')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[UP]" id="UP" onclick="checkthis('UP')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a4]" id="a4" onchange="fillthis('a4')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 5 </td>
        <td> &nbsp; Kebersihan Kuku / Kutek </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KK]" id="KK" onclick="checkthis('KK')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KKK]" id="KKK" onclick="checkthis('KKK')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a5]" id="a5" onchange="fillthis('a5')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 6 </td>
        <td> &nbsp; Kebersihan Anus / Bokong </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KA]" id="KA" onclick="checkthis('KA')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KB]" id="KB" onclick="checkthis('KB')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a6]" id="a6" onchange="fillthis('a6')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 7 </td>
        <td> &nbsp; Cukur Daerah Operasi</td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[CD]" id="CD" onclick="checkthis('CD')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[CO]" id="CO" onclick="checkthis('CO')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a7]" id="a7" onchange="fillthis('a7')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 8 </td>
        <td> &nbsp; Klisma (hukmah) </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KL]" id="KL" onclick="checkthis('KL')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KH]" id="KH" onclick="checkthis('KH')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a8]" id="a8" onchange="fillthis('a8')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 9 </td>
        <td> &nbsp; Gigi Palsu / Permanen</td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[GP]" id="GP" onclick="checkthis('GP')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[GPP]" id="GPP" onclick="checkthis('GPP')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a9]" id="a9" onchange="fillthis('a9')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 10 </td>
        <td> &nbsp; Barang Berharga Lainnya (Cincin, Gelang, Kalung, HP) </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[BB]" id="BB" onclick="checkthis('BB')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[BL]" id="BL" onclick="checkthis('BL')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a10]" id="a10" onchange="fillthis('a10')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 11 </td>
        <td> &nbsp; Rekam Medis </td>
        <td style="text-align: center"> 
            <label>
            <input type="checkbox" class="ace" name="form_37[RM]" id="RM" onclick="checkthis('RM')">
            <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[MR]" id="MR" onclick="checkthis('MR')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a11]" id="a11" onchange="fillthis('a11')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 12 </td>
        <td> &nbsp; Laboratorium </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[LB]" id="LB" onclick="checkthis('LB')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> 
            <label>
                <input type="checkbox" class="ace" name="form_37[LR]" id="LR" onclick="checkthis('LR')">
                <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a12]" id="a12" onchange="fillthis('a12')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 13 </td>
        <td> &nbsp; Foto Daerah Operasi </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[FD]" id="FD" onclick="checkthis('FD')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[FO]" id="FO" onclick="checkthis('FO')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a13]" id="a13" onchange="fillthis('a13')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 14 </td>
        <td> &nbsp; Darah </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[DH]" id="DH" onclick="checkthis('DH')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[HD]" id="HD" onclick="checkthis('HD')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a14]" id="a14" onchange="fillthis('a14')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 15 </td>
        <td> &nbsp; Premedikasi </td>
        <td style="text-align: center"> 
            <label>
                <input type="checkbox" class="ace" name="form_37[PRM]" id="PRM" onclick="checkthis('PRM')">
                <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[MPR]" id="MPR" onclick="checkthis('MPR')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a15]" id="a15" onchange="fillthis('a15')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 16 </td>
        <td> &nbsp; Keadaan Umum : <input type="text" style="text-align: center"  name="form_37[a16]" id="a16" onchange="fillthis('a16')" class="input_type" value=""></td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KU]" id="KU" onclick="checkthis('KU')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> 
            <label>
                <input type="checkbox" class="ace" name="form_37[UK]" id="UK" onclick="checkthis('UK')">
                <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a17]" id="a17" onchange="fillthis('a17')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Kesadaran</td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[ADR]" id="ADR" onclick="checkthis('ADR')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[ADR]" id="ARD" onclick="checkthis('ARD')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a18]" id="a18" onchange="fillthis('a18')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Tekanan Darah</td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[TDR]" id="TDR" onclick="checkthis('TDR')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[RDT]" id="RDT" onclick="checkthis('RDT')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a19]" id="a19" onchange="fillthis('a19')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Heart Rate / HR : <input type="text" style="text-align: center; width: 50px"  name="form_37[a50]" id="a50" onchange="fillthis('a50')" class="input_type" value="">  xmnt</td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[HRT]" id="HRT" onclick="checkthis('HRT')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[TRH]" id="TRH" onclick="checkthis('TRH')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a20]" id="a20" onchange="fillthis('a20')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Respiratori Rate HR : <input type="text" style="text-align: center; width: 50px"  name="form_37[a51]" id="a51" onchange="fillthis('a51')" class="input_type" value=""> xmnt</td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[RRH]" id="RRH" onclick="checkthis('RRH')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[HRR]" id="HRR" onclick="checkthis('HRR')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a21]" id="a21" onchange="fillthis('a21')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Suhu Badan </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[SB]" id="SB" onclick="checkthis('SB')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[BS]" id="BS" onclick="checkthis('BS')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a22]" id="a22" onchange="fillthis('a22')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 17 </td>
        <td> &nbsp; Terpasang Alat Medik </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[TAM]" id="TAM" onclick="checkthis('TAM')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[MAT]" id="MAT" onclick="checkthis('MAT')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a23]" id="a23" onchange="fillthis('a23')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Infus </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[INF]" id="INF" onclick="checkthis('INF')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[NIF]" id="NIF" onclick="checkthis('NIF')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a24]" id="a24" onchange="fillthis('a24')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td></td>
        <td> &nbsp;&nbsp;&nbsp;- Kateter </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[KAT]" id="KAT" onclick="checkthis('KAT')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[TAK]" id="TAK" onclick="checkthis('TAK')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a25]" id="a25" onchange="fillthis('a25')" class="input_type" value=""> </td>
    </tr>
    <tr style="text-align:left">
        <td style="text-align:center"> 18 </td>
        <td> &nbsp; Obat Anjuran Dokter Bedah / Anestesi </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[OAT]" id="OAT" onclick="checkthis('OAT')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center">
            <label>
                 <input type="checkbox" class="ace" name="form_37[TAO]" id="TAO" onclick="checkthis('TAO')">
                 <span class="lbl"></span>
            </label>
        </td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_37[a26]" id="a26" onchange="fillthis('a26')" class="input_type" value=""> </td>
    </tr>
</table>



<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
