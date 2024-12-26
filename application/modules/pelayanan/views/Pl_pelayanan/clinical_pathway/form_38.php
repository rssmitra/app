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
<div style="text-align: center; font-size: 18px;"><b>CEK LIST SERAH TERIMA PASIEN PASCA BEDAH</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->

<table border="1" width="100%">
    <tr>
        <td style="font-weight: bold; width:20%">  Diagnosis : <input type="text" style="text-align: center; width: 70px"   name="a1" id="a1" onchange="fillthis('a1')" class="input_type" value=""></td>
        <td style="font-weight: bold; width:60%"> DPJP : <input type="text" style="text-align: center"  name="a2" id="a2" onchange="fillthis('a2')" class="input_type" value=""></td>
        <td style="font-weight: bold; width:20%"> Tanggal : <input type="text" style="text-align: center; width: 70px"  name="a3" id="a3" onchange="fillthis('a3')" class="input_type" value=""></td>
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
            <td> &nbsp; Kesadaran </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="1" id="1" onclick="checkthis('1')"></td>
        <td style="text-align: center"> <input type="checkbox" class="ace" name="1s" id="1s" onclick="checkthis('1s')"></td>
        <td style="text-align: center"> <input type="text" style="text-align: center"  name="a4" id="a4" onchange="fillthis('a4')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center" rowspan="5"> 2 </td>
            <td> &nbsp; Tanda - Tanda Vital </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="2" id="2" onclick="checkthis('2')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="2s" id="2s" onclick="checkthis('2s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a5" id="a5" onchange="fillthis('a5')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; Nadi </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="3" id="3" onclick="checkthis('3')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="3s" id="3s" onclick="checkthis('3s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a6" id="a6" onchange="fillthis('a6')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; Pernafasan </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="4" id="4" onclick="checkthis('4')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="4s" id="4s" onclick="checkthis('4s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a7" id="a7" onchange="fillthis('a7')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; Tekanan Darah </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="5" id="5" onclick="checkthis('5')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="5s" id="5s" onclick="checkthis('5s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a8" id="a8" onchange="fillthis('a8')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; SP 02 </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="6" id="6" onclick="checkthis('6')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="6s" id="6s" onclick="checkthis('6s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a9" id="a9" onchange="fillthis('a9')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 3 </td>
            <td> &nbsp; Intake Parenteral </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="7" id="7" onclick="checkthis('7')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="7s" id="7s" onclick="checkthis('7s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a10" id="a10" onchange="fillthis('a10')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td rowspan="2"> </td>
            <td> &nbsp; - Infus </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="8" id="8" onclick="checkthis('8')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="8s" id="8s" onclick="checkthis('8s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a11" id="a11" onchange="fillthis('a11')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Transfusi </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="9" id="9" onclick="checkthis('9')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="9s" id="9s" onclick="checkthis('9s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a12" id="a12" onchange="fillthis('a12')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 4 </td>
            <td> &nbsp; Spoel </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="10" id="10" onclick="checkthis('10')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="10s" id="10s" onclick="checkthis('10s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a13" id="a13" onchange="fillthis('a13')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td rowspan="6"></td>
            <td> &nbsp; Output : <input type="text" style="text-align: center"  name="de1" id="de1" onchange="fillthis('de1')" class="input_type" value=""></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="11" id="1" onclick="checkthis('11')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="11s" id="11s" onclick="checkthis('11s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a14" id="a14" onchange="fillthis('a14')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Perdarahan </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="12" id="12" onclick="checkthis('12')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="12s" id="12s" onclick="checkthis('12s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a15" id="a15" onchange="fillthis('a15')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - NGT </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="13" id="13" onclick="checkthis('13')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="13s" id="13s" onclick="checkthis('13s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a16" id="a16" onchange="fillthis('a16')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Muntah </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="14" id="14" onclick="checkthis('14')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="14s" id="14s" onclick="checkthis('14s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a17" id="a17" onchange="fillthis('a17')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Drain </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="15" id="15" onclick="checkthis('15')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="15s" id="15s" onclick="checkthis('15s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a18" id="a18" onchange="fillthis('a18')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Urine </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="16" id="16" onclick="checkthis('16')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="16s" id="16s" onclick="checkthis('16s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a19" id="a19" onchange="fillthis('a19')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 5 </td>
            <td> &nbsp; Kontraksi Uterus </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="17" id="17" onclick="checkthis('17')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="17s" id="17s" onclick="checkthis('17s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a20" id="a20" onchange="fillthis('a20')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center" rowspan="7"> 6 </td>
            <td> &nbsp; Keluhan Nyeri : <input type="text" style="text-align: center"  name="de1" id="de1" onchange="fillthis('de1')" class="input_type" value=""></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="18" id="18" onclick="checkthis('18')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="18s" id="18s" onclick="checkthis('18s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a21" id="a21" onchange="fillthis('a21')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Lokasi : <input type="text" style="text-align: center"  name="de1" id="de1" onchange="fillthis('de1')" class="input_type" value=""></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="19" id="19" onclick="checkthis('19')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="19s" id="19s" onclick="checkthis('19s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a22" id="a22" onchange="fillthis('a22')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Katagori : <input type="text" style="text-align: center"  name="de1" id="de1" onchange="fillthis('de1')" class="input_type" value=""> </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="20" id="20" onclick="checkthis('20')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="20s" id="20s" onclick="checkthis('20s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a23" id="a23" onchange="fillthis('a23')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; <input type="checkbox" class="ace" name="0" id="0" onclick="checkthis('0')"> Skala 0    = tidak nyeri </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="21" id="21" onclick="checkthis('21')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="21s" id="21s" onclick="checkthis('21s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a24" id="a24" onchange="fillthis('a24')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; <input type="checkbox" class="ace" name="1-3" id="1-3" onclick="checkthis('1-3')"> Skala 1-3    = nyeri ringan </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="22" id="22" onclick="checkthis('22')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="22s" id="22s" onclick="checkthis('22s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a25" id="a25" onchange="fillthis('a25')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; <input type="checkbox" class="ace" name="4-6" id="4-6" onclick="checkthis('4-6')"> Skala 4-6    = nyeri sedang </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="23" id="23" onclick="checkthis('23')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="23s" id="23s" onclick="checkthis('23s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a26" id="a26" onchange="fillthis('a26')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; <input type="checkbox" class="ace" name="7-10" id="7-10" onclick="checkthis('7-10')"> Skala 7-10    = Nyeri Berat </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="24" id="24" onclick="checkthis('24')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="24s" id="24s" onclick="checkthis('24s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a27" id="a27" onchange="fillthis('a27')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 7 </td>
            <td> &nbsp; Obat Kamar Bedah </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="25" id="25" onclick="checkthis('25')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="25s" id="25s" onclick="checkthis('25s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a28" id="a28" onchange="fillthis('a28')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 8 </td>
            <td> &nbsp; Instruksi Pasca Bedah / Operasi </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="26" id="26" onclick="checkthis('26')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="26s" id="26s" onclick="checkthis('26s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a29" id="a29" onchange="fillthis('a29')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 9 </td>
            <td> &nbsp; Foto / X Ray </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="27" id="27" onclick="checkthis('27')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="27s" id="27s" onclick="checkthis('27s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a30" id="a30" onchange="fillthis('a30')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 10 </td>
            <td> &nbsp; Laporan Operasi </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="28" id="28" onclick="checkthis('28')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="TAO" id="28s" onclick="checkthis('28s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a31" id="a31" onchange="fillthis('a31')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 11 </td>
            <td> &nbsp; Status Anestesi </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="29" id="29" onclick="checkthis('29s')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="29s" id="29" onclick="checkthis('29s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a32" id="a32" onchange="fillthis('a32')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 12 </td>
            <td> &nbsp; Form Ceklis Keselamatan Pasien </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="30" id="30" onclick="checkthis('30')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="30s" id="30s" onclick="checkthis('30s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a33" id="a33" onchange="fillthis('a33')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 13 </td>
            <td> &nbsp; Jaringan / Bahan Pemeriksaan / Plasenta </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="31" id="31" onclick="checkthis('31')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="31s" id="31s" onclick="checkthis('31s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="34" id="34" onchange="fillthis('34')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 14 </td>
            <td> &nbsp; ALDERETE SCORE </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="32" id="32" onclick="checkthis('32')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="32s" id="32s" onclick="checkthis('32s')"></td>
            <td style="text-align: center"> Nilai : <input type="text" style="text-align: center"  name="35" id="35" onchange="fillthis('35')" class="input_type" value=""></td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 15 </td>
            <td> &nbsp; Lain - Lain </td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="33" id="33" onclick="checkthis('33')"></td>
            <td style="text-align: center"> <input type="checkbox" class="ace" name="33s" id="33s" onclick="checkthis('33s')"></td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="a36" id="a36" onchange="fillthis('a36')" class="input_type" value=""> </td>
        </tr>
    </table>
    

<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
