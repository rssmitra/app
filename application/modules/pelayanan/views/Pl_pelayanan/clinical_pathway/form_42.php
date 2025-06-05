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
<div style="text-align: center; font-size: 18px;"><b>FORMULIR KRITERIA PASIEN MASUK DAN KELUAR ICU</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->
<table width="100%" border="1" width="100%" class="table">
    <tr>
        <td colspan="2" width="50%">Tanggal dan Jam Masuk : 
            <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_42[tgl_jam_masuk]" id="tgl_jam_masuk" onchange="fillthis('tgl_jam_masuk')" value="<?php echo isset($value_form['tgl_jam_masuk'])?$value_form['tgl_operasi']:date('Y-m-d')?>"> </td>
        <td width="50%">Tanggal dan Jam Keluar : 
            <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_42[tgl_jam_keluar]" id="tgl_jam_keluar" onchange="fillthis('tgl_jam_keluar')" value="<?php echo isset($value_form['tgl_jam_keluar'])?$value_form['tgl_operasi']:date('Y-m-d')?>">
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="font-weight: bold; font-size: 14px">KRITERIA PASIEN MASUK</td>
    </tr>
    <tr>
        <td style="width: 30px" align="center">No</td>
        <td>Prioritas</td>
        <td>Kriteria</td>
    </tr>

    <tr>
        <td align="center">1</td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[prioritas_1]" id="prioritas_1" onclick="checkthis('prioritas_1')" value="1">
                <span class="lbl"> Pasien Prioritas 1 </span>
            </label>
            <br>
            <ol style="padding-top: 10px; vertical-align: justify;">
                <li>Pasien sakit kritis</li>
                <li>Pasien tidak stabil yang memerlukan terapi intensif dan tertitrasi seperti : dukungan/bantuan ventilasi dan alat bantuk suportif organ/sistem yang lain, infus obat-obat vasoaktif continue, obat antiaritmia continue, pengobatan contiunue teritrasi.</li>
            </ol>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[p1_kr_1]" id="p1_kr_1" onclick="checkthis('p1_kr_1')" value="1">
                <span class="lbl"> Pasien pasca Bedah Kardiotoraksik </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p1_kr_2]" id="p1_kr_2" onclick="checkthis('p1_kr_2')" value="1">
                <span class="lbl"> Pasien sepsis berat </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p1_kr_3]" id="p1_kr_3" onclick="checkthis('p1_kr_3')" value="1">
                <span class="lbl"> Pasien keseimbangan asam basa dan elektrolit yang mengancam nyawa </span>
            </label>
        </td>
    </tr>

    <tr>
        <td align="center">2</td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[prioritas_2]" id="prioritas_2" onclick="checkthis('prioritas_2')" value="1">
                <span class="lbl"> Pasien Prioritas 2 </span>
            </label>
            <br>
            <p style="padding-top: 10px; vertical-align: justify;">
                Pasien yang memerlukan pelayanan pemantauan canggih di ICU, sebab sangat beresiko bila tidak mendapat terapi intensif segera, misal : pemantauan intensif menggunakan pulmonary arterial catheter.
            </p>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[p2_kr_1]" id="p2_kr_1" onclick="checkthis('p2_kr_1')" value="1">
                <span class="lbl"> Pasien gagal jantung - paru </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p2_kr_2]" id="p2_kr_2" onclick="checkthis('p2_kr_2')" value="1">
                <span class="lbl"> Gagal ginjal akut berat </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p2_kr_3]" id="p2_kr_3" onclick="checkthis('p2_kr_3')" value="1">
                <span class="lbl"> Pembedahan mayor </span>
            </label>
        </td>
    </tr>

    <tr>
        <td align="center">3</td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[prioritas_3]" id="prioritas_3" onclick="checkthis('prioritas_3')" value="1">
                <span class="lbl"> Pasien Prioritas 3 </span>
            </label>
            <br>
            <p style="padding-top: 10px; vertical-align: justify;">
                Pasien sakit kritis, status kesehatan sebelumnya tidak stabil, penyakit yang mendasarinya atau penyakit akutnya secara sendiria atau kombinasi, kemungkinan sembuh dan/atau manfaat terapi di ICU pada golongan ini sangat kecil.
            </p>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[p3_kr_1]" id="p3_kr_1" onclick="checkthis('p3_kr_1')" value="1">
                <span class="lbl"> Pasien dengan keganasan metastatik disertai penyulit infeksi </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p3_kr_2]" id="p3_kr_2" onclick="checkthis('p3_kr_2')" value="1">
                <span class="lbl"> Sumbatan jalan nafas </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p3_kr_3]" id="p3_kr_3" onclick="checkthis('p3_kr_3')" value="1">
                <span class="lbl"> Penyakit jantung </span>
            </label>
            <label>
                <input type="checkbox" class="ace" name="form_42[p3_kr_4]" id="p3_kr_4" onclick="checkthis('p3_kr_4')" value="1">
                <span class="lbl"> Penyakit paru terminal disertai komplikasi penyakit akut berat </span>
            </label>
        </td>
    </tr>

    <tr>
        <td align="center">4</td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[prioritas_4]" id="prioritas_4" onclick="checkthis('prioritas_4')" value="1">
                <span class="lbl"> Pengecualian </span>
            </label>
        </td>
        <td>
            <label>
                <input type="checkbox" class="ace" name="form_42[p4_kr_1]" id="p4_kr_1" onclick="checkthis('p4_kr_1')" value="1">
                <span class="lbl"> Pasien yang memenuhi kriteria masuk tetapi menolak terapi tunjangan hidup yang agresif dan hanya demi "perawatan yang aman" </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p4_kr_2]" id="p4_kr_2" onclick="checkthis('p4_kr_2')" value="1">
                <span class="lbl"> Pasien dalam keadaan vegetative permanen </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p4_kr_3]" id="p4_kr_3" onclick="checkthis('p4_kr_3')" value="1">
                <span class="lbl"> Pasien yang mengalami mati batang otak namun hanya karena kepentingan donor organ makan pasien dirawat di ICU </span>
            </label>
        </td>
    </tr>

    <tr>
        <td colspan="3" align="center" style="font-weight: bold; font-size: 14px">KRITERIA PASIEN KELUAR</td>
    </tr>

    <tr>
        <td align="center">5</td>
        <td colspan="2">
            Prioritas pasien dipindahkan dari ICU berdasarkan pertimbangan medis oleh kepala ICU dan tim yang merawat pasien.
            <br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p5_kr_1]" id="p5_kr_1" onclick="checkthis('p5_kr_1')" value="1">
                <span class="lbl"> Penyakit atau keadaan pasien yang sudah membaik dan cukup stabil sehingga tidak memerlukan terapi dan pemantauan intensive lebih lanjut </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p5_kr_2]" id="p5_kr_2" onclick="checkthis('p5_kr_2')" value="1">
                <span class="lbl"> Bila status fisik telah menurun jauh tetapi tidak ada rencana intervensi aktif </span>
            </label><br>
            <label>
                <input type="checkbox" class="ace" name="form_42[p5_kr_3]" id="p5_kr_3" onclick="checkthis('p5_kr_3')" value="1">
                <span class="lbl"> Pasien atau keluarga menolak untuk dirawat ICU </span>
            </label>
        </td>
    </tr>


</table>
   

<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
