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
<style>
    table{
        border : 1px solid #d7d7d7 !important;
    }
</style>
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
        <td style="font-weight: bold; width:20%">  Diagnosis : <input type="text" style="text-align: center; width: 70px"   name="form_38[a1]" id="a1" onchange="fillthis('a1')" class="input_type" value=""></td>
        <td style="font-weight: bold; width:60%"> DPJP : <input type="text" style="text-align: center"  name="form_38[a2]" id="a2" onchange="fillthis('a2')" class="input_type" value=""></td>
        <td style="font-weight: bold; width:20%"> Tanggal : <input type="text" style="text-align: center; width: 70px"  name="form_38[a3]" id="a3" onchange="fillthis('a3')" class="input_type" value=""></td>
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
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[1]" id="1" onclick="checkthis('1')">
                     <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[1s]" id="1s" onclick="checkthis('1s')">
                     <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a4]" id="a4" onchange="fillthis('a4')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center" rowspan="5"> 2 </td>
            <td> &nbsp; Tanda - Tanda Vital </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[2]" id="2" onclick="checkthis('2')">
                     <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[2s]" id="2s" onclick="checkthis('2s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a5]" id="a5" onchange="fillthis('a5')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; Nadi </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[3]" id="3" onclick="checkthis('3')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[3s]" id="3s" onclick="checkthis('3s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a6]" id="a6" onchange="fillthis('a6')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; Pernafasan </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[4]" id="4" onclick="checkthis('4')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[4s]" id="4s" onclick="checkthis('4s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a7]" id="a7" onchange="fillthis('a7')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; Tekanan Darah </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[5]" id="5" onclick="checkthis('5')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[5s]" id="5s" onclick="checkthis('5s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a8]" id="a8" onchange="fillthis('a8')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; SP 02 </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[6]" id="6" onclick="checkthis('6')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[6s]" id="6s" onclick="checkthis('6s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a9]" id="a9" onchange="fillthis('a9')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 3 </td>
            <td> &nbsp; Intake Parenteral </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[7]" id="7" onclick="checkthis('7')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[7s]" id="7s" onclick="checkthis('7s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a10]" id="a10" onchange="fillthis('a10')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td rowspan="2"> </td>
            <td> &nbsp; - Infus </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[8]" id="8" onclick="checkthis('8')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[8s]" id="8s" onclick="checkthis('8s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a11]" id="a11" onchange="fillthis('a11')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Transfusi </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[9]" id="9" onclick="checkthis('9')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[9s]" id="9s" onclick="checkthis('9s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a12]" id="a12" onchange="fillthis('a12')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 4 </td>
            <td> &nbsp; Spoel </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[10]" id="10" onclick="checkthis('10')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[10s]" id="10s" onclick="checkthis('10s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a13]" id="a13" onchange="fillthis('a13')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td rowspan="6"></td>
            <td> &nbsp; Output : <input type="text" style="text-align: center"  name="form_38[output]" id="output" onchange="fillthis('output')" class="input_type" value=""></td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[11]" id="11" onclick="checkthis('11')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[11s]" id="11s" onclick="checkthis('11s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a14]" id="a14" onchange="fillthis('a14')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Perdarahan </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[12]" id="12" onclick="checkthis('12')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[12s]" id="12s" onclick="checkthis('12s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a15]" id="a15" onchange="fillthis('a15')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - NGT </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[13]" id="13" onclick="checkthis('13')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[13s]" id="13s" onclick="checkthis('13s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a16]" id="a16" onchange="fillthis('a16')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Muntah </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[14]" id="14" onclick="checkthis('14')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[14s]" id="14s" onclick="checkthis('14s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a17]" id="a17" onchange="fillthis('a17')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Drain </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[15]" id="15" onclick="checkthis('15')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[15s]" id="15s" onclick="checkthis('15s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a18]" id="a18" onchange="fillthis('a18')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Urine </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[16]" id="16" onclick="checkthis('16')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[16s]" id="16s" onclick="checkthis('16s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a19]" id="a19" onchange="fillthis('a19')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 5 </td>
            <td> &nbsp; Kontraksi Uterus </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[17]" id="17" onclick="checkthis('17')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[17s]" id="17s" onclick="checkthis('17s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a20]" id="a20" onchange="fillthis('a20')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center" rowspan="7"> 6 </td>
            <td> &nbsp; Keluhan Nyeri : <input type="text" style="text-align: center"  name="form_38[keluhan_nyeri]" id="keluhan_nyeri" onchange="fillthis('keluhan_nyeri')" class="input_type" value=""></td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[18]" id="18" onclick="checkthis('18')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[18s]" id="18s" onclick="checkthis('18s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a21]" id="a21" onchange="fillthis('a21')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Lokasi : <input type="text" style="text-align: center"  name="form_38[lokasi]" id="lokasi" onchange="fillthis('lokasi')" class="input_type" value=""></td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[19]" id="19" onclick="checkthis('19')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[19s]" id="19s" onclick="checkthis('19s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a22]" id="a22" onchange="fillthis('a22')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp; - Katagori : <input type="text" style="text-align: center"  name="form_38[katagori]" id="katagori" onchange="fillthis('katagori')" class="input_type" value=""> </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[20]" id="20" onclick="checkthis('20')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[20s]" id="20s" onclick="checkthis('20s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a23]" id="a23" onchange="fillthis('a23')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp;
                <label>
                     <input type="checkbox" class="ace" name="form_38[0]" id="0" onclick="checkthis('0')"> Skala 0    = tidak nyeri </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[21]" id="21" onclick="checkthis('21')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[21s]" id="21s" onclick="checkthis('21s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a24]" id="a24" onchange="fillthis('a24')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp;
                <label>
                     <input type="checkbox" class="ace" name="form_38[1-3]" id="1-3" onclick="checkthis('1-3')"> Skala 1-3    = nyeri ringan 
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[22]" id="22" onclick="checkthis('22')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[22s]" id="22s" onclick="checkthis('22s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a25]" id="a25" onchange="fillthis('a25')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp;
                <label>
                     <input type="checkbox" class="ace" name="form_38[4-6]" id="4-6" onclick="checkthis('4-6')"> Skala 4-6    = nyeri sedang </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[23]" id="23" onclick="checkthis('23')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[23s]" id="23s" onclick="checkthis('23s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a26]" id="a26" onchange="fillthis('a26')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td> &nbsp;
                <label>
                     <input type="checkbox" class="ace" name="form_38[7-10]" id="7-10" onclick="checkthis('7-10')"> Skala 7-10    = Nyeri Berat </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[24]" id="24" onclick="checkthis('24')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[24s]" id="24s" onclick="checkthis('24s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a27]" id="a27" onchange="fillthis('a27')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 7 </td>
            <td> &nbsp; Obat Kamar Bedah </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[25]" id="25" onclick="checkthis('25')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[25s]" id="25s" onclick="checkthis('25s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a28]" id="a28" onchange="fillthis('a28')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 8 </td>
            <td> &nbsp; Instruksi Pasca Bedah / Operasi </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[26]" id="26" onclick="checkthis('26')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[26s]" id="26s" onclick="checkthis('26s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a29]" id="a29" onchange="fillthis('a29')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 9 </td>
            <td> &nbsp; Foto / X Ray </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[27]" id="27" onclick="checkthis('27')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[27s]" id="27s" onclick="checkthis('27s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a30]" id="a30" onchange="fillthis('a30')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 10 </td>
            <td> &nbsp; Laporan Operasi </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[28]" id="28" onclick="checkthis('28')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[TAO]" id="28s" onclick="checkthis('28s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a31]" id="a31" onchange="fillthis('a31')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 11 </td>
            <td> &nbsp; Status Anestesi </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[29]" id="29" onclick="checkthis('29')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[29s]" id="29s" onclick="checkthis('29s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a32]" id="a32" onchange="fillthis('a32')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 12 </td>
            <td> &nbsp; Form Ceklis Keselamatan Pasien </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[30]" id="30" onclick="checkthis('30')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[30s]" id="30s" onclick="checkthis('30s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a33]" id="a33" onchange="fillthis('a33')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 13 </td>
            <td> &nbsp; Jaringan / Bahan Pemeriksaan / Plasenta </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[31]" id="31" onclick="checkthis('31')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[31s]" id="31s" onclick="checkthis('31s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[34]" id="34" onchange="fillthis('34')" class="input_type" value=""> </td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 14 </td>
            <td> &nbsp; ALDERETE SCORE </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[32]" id="32" onclick="checkthis('32')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[32s]" id="32s" onclick="checkthis('32s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> Nilai : <input type="text" style="text-align: center"  name="form_38[35]" id="35" onchange="fillthis('35')" class="input_type" value=""></td>
        </tr>
        <tr style="text-align:left">
            <td style="text-align:center"> 15 </td>
            <td> &nbsp; Lain - Lain </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[33]" id="33" onclick="checkthis('33')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center">
                <label>
                     <input type="checkbox" class="ace" name="form_38[33s]" id="33s" onclick="checkthis('33s')">
                    <span class="lbl"></span>
                </label>
            </td>
            <td style="text-align: center"> <input type="text" style="text-align: center"  name="form_38[a36]" id="a36" onchange="fillthis('a36')" class="input_type" value=""> </td>
        </tr>
    </table>
    

<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
