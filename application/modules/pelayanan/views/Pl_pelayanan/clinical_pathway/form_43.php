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

    $('#43_dokter_sp').typeahead({
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
        $('#43_dokter_sp').val(label_item);
      }
    });

    $('#43_dokter_konsul_sp').typeahead({
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
            $('#43_dokter_konsul_sp').val(label_item);
        }

    });

});

</script>

<?php echo $header; ?>
<hr>
<br>
<div style="text-align: center; font-size: 18px;"><b>PERMINTAAN KONSULTASI</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->

Kepada Yth. <br>
dr. Spesialis, <input type="text" class="input_type" style="width: 60% !important" name="form_43[43_dokter_sp]" id="43_dokter_sp" onchange="fillthis('43_dokter_sp')" value="<?php echo isset($value_form['43_dokter_sp'])?$value_form['43_dokter_sp']:''?>"> <br>
Dengan Hormat, <br>
<br>
<p>
    Mohon konsultasi BIASA/CITO atas pasien tersebut diatas untuk :
    <br>
    <table class="table">
        <tr>
            <td style="padding:5px">
                <label>
                    <input type="checkbox" class="ace" name="form_43[list_1]" id="list_1" onclick="checkthis('list_1')" value="1">
                    <span class="lbl"> Penilaian khusus saat ini saja </span>
                </label>
                <br>
                <label>
                    <input type="checkbox" class="ace" name="form_43[list_2]" id="list_2" onclick="checkthis('list_2')" value="1">
                    <span class="lbl">Saran tindak medis lanjutan </span>
                </label>
                <br>
                <label>
                    <input type="checkbox" class="ace" name="form_43[list_3]" id="list_3" onclick="checkthis('list_3')" value="1">
                    <span class="lbl"> Pengatasan masalah medis saat ini </span>
                </label>
            </td>
            <td>
                <label>
                    <input type="checkbox" class="ace" name="form_43[list_4]" id="list_4" onclick="checkthis('list_4')" value="1">
                    <span class="lbl"> Tindak lanjut secara khusus </span>
                </label>
                <br>
                <label>
                    <input type="checkbox" class="ace" name="form_43[list_5]" id="list_5" onclick="checkthis('list_5')" value="1">
                    <span class="lbl"> Pengambilalihan kasus selanjutnya </span>
                </label>
                <br>
                <label>
                    <input type="checkbox" class="ace" name="form_43[list_6]" id="list_6" onclick="checkthis('list_6')" value="1">
                    <span class="lbl"> Rawat bersama selanjutnya </span>
                </label>
            </td>
        </tr>
    </table>
    <br>
    Untuk permintaan konsultasi diatas, penemuan klinis terpenting adalah sebagai berikut : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_43[penemuan_klinis]" id="penemuan_klinis" onchange="fillthis('penemuan_klinis')"></textarea>
    <br>
    Kecurigaan klinis / Harapan : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_43[kecurigaan]" id="kecurigaan" onchange="fillthis('kecurigaan')"></textarea>

    <hr>

    <div style="text-align: center; font-size: 18px;"><b>JAWABAN KONSULTASI</b></div>
    <br>
    <br>
    Dengan hormat, <br>
    Sesuai permintaan konsultasi diatas, pada kasus ini ditemukan : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_43[penemuan_konsultasi]" id="penemuan_konsultasi" onchange="fillthis('penemuan_konsultasi')"></textarea>
    <br>
    <br>
    Saran tindak medis / pengobatan : <br>
    <textarea class="textarea-type" rows="3" style="min-height: 50px !important" name="form_43[saran_tindak_medis]" id="saran_tindak_medis" onchange="fillthis('saran_tindak_medis')"></textarea>
    <br>
    <br>
    Salam Sejawat, <br>
    dr. Spesialis, <input type="text" class="input_type" style="width: 60% !important" name="form_43[43_dokter_konsul_sp]" id="43_dokter_konsul_sp" onchange="fillthis('43_dokter_konsul_sp')" value="<?php echo isset($value_form['43_dokter_konsul_sp'])?$value_form['43_dokter_konsul_sp']:''?>"> <br>
    Tanggal : <input type="text" class="input_type date-picker" data-date-format="yyyy-mm-dd" name="form_43[43_tgl_jwbn_konsul]" id="43_tgl_jwbn_konsul" onchange="fillthis('43_tgl_jwbn_konsul')" value="<?php echo isset($value_form['43_tgl_jwbn_konsul'])?$value_form['43_tgl_jwbn_konsul']:date('Y-m-d')?>"> 
    <br>
    Jam : <input type="text" class="input_type" name="form_43[43_jam_jwb_konsul]" id="43_jam_jwb_konsul" onchange="fillthis('43_jam_jwb_konsul')" value="<?php echo isset($value_form['43_jam_jwb_konsul'])?$value_form['43_jam_jwb_konsul']:date('H:i')?>">

</p>

<!--END MAIN CONTENT -->

<?php echo $footer; ?>
