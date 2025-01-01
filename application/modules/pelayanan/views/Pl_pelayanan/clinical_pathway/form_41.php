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
<div style="text-align: center; font-size: 18px;"><b>PENILAIAN TINGKAT NYERI NEONATUS / BAYI</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->


<b>NIPS (NEONATAL INFANT PAIN SCALE)</b>
        <table border="1" width="100%" class="table">
            <thead>
                <tr>
                    <th style="text-align: center" width="200px"><b> NIPS </b></th>
                    <th style="text-align: center" width="100px"><b> 0 </b></th>
                    <th style="text-align: center" width="200px"><b> 1 </b></th>
                    <th style="text-align: center" width="100px"><b> 2 </b></th>
                    <th style="text-align: center" width="100px"><b> TGL/JAM </b></th>
                    <th style="text-align: center" width="100px"><b> TGL/JAM </b></th>
                    <th style="text-align: center" width="100px"><b> TGL/JAM </b></th>
                    <th style="text-align: center" width="100px"><b> TGL/JAM </b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center" >Ekspresi wajah</td>
                    <td style="text-align: center" > Relaks</td>
                    <td style="text-align: center" > Kontraksi</td>
                    <td style="text-align: center" > -</td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a1]" id="a1" onchange="fillthis('a1')" class="input_type"> </td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a2]" id="a2" onchange="fillthis('a2')" class="input_type"> </td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a3]" id="a3" onchange="fillthis('a3')" class="input_type"> </td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a4]" id="a4" onchange="fillthis('a4')" class="input_type"></td>
                </tr>
                <tr>
                    <td style="text-align: center" >Menangis</td>
                    <td style="text-align: center" >Tidak Ada</td>
                    <td style="text-align: center" >Mumbling</td>
                    <td style="text-align: center" >Kuat</td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a5]" id="a5" onchange="fillthis('a5')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a6]" id="a6" onchange="fillthis('a6')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a7]" id="a7" onchange="fillthis('a7')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a8]" id="a8" onchange="fillthis('a8')" class="input_type"></td>
                </tr>
                <tr>
                    <td style="text-align: center" >Bernapas</td>
                    <td style="text-align: center" >Relaks</td>
                    <td style="text-align: center" >Berbeda dengan basal</td>
                    <td style="text-align: center" > - </td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a9]" id="a9" onchange="fillthis('a9')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a10]" id="a10" onchange="fillthis('a10')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a11]" id="a11" onchange="fillthis('a11')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a12]" id="a12" onchange="fillthis('a12')" class="input_type"></td>
                </tr>
                <tr>
                    <td style="text-align: center" >Lengan</td>
                    <td style="text-align: center" >Relaks</td>
                    <td style="text-align: center" >Fleksi/tegang</td>
                    <td style="text-align: center" > - </td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a13]" id="a13" onchange="fillthis('a13')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a14]" id="a14" onchange="fillthis('a14')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a15]" id="a15" onchange="fillthis('a15')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a16]" id="a16" onchange="fillthis('a16')" class="input_type"></td>
                </tr>
                <tr>
                    <td style="text-align: center" >Tungkai</td>
                    <td style="text-align: center">Relaks</td>
                    <td style="text-align: center">fleksi/tegang</td>
                    <td style="text-align: center"> - </td>
                    <td style="text-align: center"><input type="text" style="width:50px; text-align: center" name="form_41[a17]" id="a17" onchange="fillthis('a17')" class="input_type"></td>
                    <td style="text-align: center"><input type="text" style="width:50px; text-align: center" name="form_41[a18]" id="a18" onchange="fillthis('a18')" class="input_type"></td>
                    <td style="text-align: center"><input type="text" style="width:50px; text-align: center" name="form_41[a19]" id="a19" onchange="fillthis('a19')" class="input_type"></td>
                    <td style="text-align: center"><input type="text" style="width:50px; text-align: center" name="form_41[a20]" id="a20" onchange="fillthis('a20')" class="input_type"></td>
                </tr>
                <tr>
                    <td style="text-align: center">Alertness</td>
                    <td style="text-align: center">Tidur/Tenang</td>
                    <td style="text-align: center" >Tidak Nyaman</td>
                    <td style="text-align: center" > - </td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a21]" id="a21" onchange="fillthis('a21')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a22]" id="a22" onchange="fillthis('a22')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a23]" id="a23" onchange="fillthis('a23')" class="input_type"></td>
                    <td style="text-align: center" > <input type="text" style="width:50px; text-align: center" name="form_41[a24]" id="a24" onchange="fillthis('a24')" class="input_type"></td>
                </tr>
                <tr>
                    <td style="text-align: center" >Total score</td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a29]" id="a29" onchange="fillthis('a29')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a30]" id="a30" onchange="fillthis('a30')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a31]" id="a31" onchange="fillthis('a31')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a25]" id="a25" onchange="fillthis('a25')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a26]" id="a26" onchange="fillthis('a26')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a27]" id="a27" onchange="fillthis('a27')" class="input_type"></td>
                    <td style="text-align: center" ><input type="text" style="width:50px; text-align: center" name="form_41[a28]" id="a28" onchange="fillthis('a28')" class="input_type"></td>
                </tr>
            </tbody>
        </table>
        <br>

        Penatalaksanaan dari manajemen nyeri : 
        <br>
        <br>
        . NYERI RINGAN : 0 - 2 :
        <ol>
            <li>Edukasi keluarga</li>
            <li>Ajarkan tehnik penggunaan non farmakologi misalnya : relaksasi, biofeedback, hypnosis, giude imagery
        terapi musik, distraksi, terapi bermain,acupressure, terapi dingin/panas maupun terapi pijatan.</li>
            <li>Kaji kembali nyeri setelah 1 jam jika tindakan tehnik non farmakologi tidak berhasil kolaborasi dengan dokter dalam pemberian terapi farmakologi(terapi NSAID). </li>
            <li>Kaji nyeri setelah 8 jam dari pemberian terapi farmakologi. </li>
        </ol><br>
        
        SKALA NYERI SEDANG : 3 - 4
        <ol>
            <li>Edukasi keluarga</li>
            <li>Ajarkan tehnik penggunaan non farmakologi misalnya : relaksasi, biofeedback, hypnosis, giude imagery
                terapi musik, distraksi, terapi bermain,acupressure, terapi dingin/panas maupun terapi pijatan.</li>
            <li>Kaji nyeri setelah 1 jam jika tehnik non farmakologi tidak berhasil kolaborasi dengan dokter jaga/DPJP dalam pemberian terapi farmakologi (terapi NSAID, opioid lemah).</li>
            <li>Kaji nyeri tiap 2 jam dan tiap 8 jam laporkan ke dr jaga/DPJP</li>
        </ol><br>

        SKALA NYERI BERAT : >4
        <ol>
            <li>Edukasi keluarga</li>
            <li>Ajarkan tehnik non farmakologi misalnya : relaksasi, biofeedback, hypnosis, giude imagery
                terapi musik, distraksi, terapi bermain,acupressure, terapi dingin/panas maupun terapi pijatan.</li>
            <li>Kaji nyeri setelah 1 jam jika tehnik non farmakologi tidak berhasil kolaborasi dengan DPJP dalam pemberian terapi opioid kuat.</li>
            <li>Kaji nyeri tiap 1 jam dan tiap 8 jam dilaporkan ke DPJP.</li>
        </ol>
   

<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
