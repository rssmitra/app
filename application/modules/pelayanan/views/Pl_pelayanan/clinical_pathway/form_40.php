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
<div style="text-align: center; font-size: 18px;"><b>FORMULIR INFORMASI / PENJELASAN</b></div>
<br>
<!-- hidden form  -->
<input type="hidden" name="jenis_form" value="<?php echo $jenis_form?>">

<!--MAIN CONTENT -->

<table width="100%">
    <tr><td colspan="3">Telah disampaikan informasi / penjelasan kepada saya :</td><tr>
    <tr>
        <td width="18%">Nama</td>
        <td width="3%">:</td>
        <td><input type="text" style="text-align: left !important" name="form_40[mn_a]" id="mn_a" onchange="fillthis('mn_a')" class="input_type"
        value=""></td>
    <tr>
    <tr>
        <td width="18%">Keluarga dari pasien</td>
        <td width="3%">:</td>
        <td><input type="text" style="text-align: left !important" name="form_40[mj_a]" id="mj_a" onchange="fillthis('mj_a')" class="input_type"
        value=""></td>
    <tr>
    <tr>
        <td width="18%">Ruang Perawatan</td>
        <td width="3%">:</td>
        <td><input type="text" style="text-align: left !important" name="form_40[mr_p]" id="mr_p" onchange="fillthis('mr_p')" class="input_type"
        value=""></td>
    <tr>
    <tr>
        <td width="18%">Mengenai</td>
        <td width="3%">:</td>
        <td><input type="text" style="text-align: left !important" name="form_40[mj_a1]" id="mj_a1" onchange="fillthis('mj_a1')" class="input_type"
        value=""></td>
    <tr>
</table>
<br>
<table border="1" width="100%">
    <tbody>
        <tr>
            <td width="2%">
                <strong>NO.</strong>
            </td>
            <td>
                <p style="text-align:center;">
                    <strong>JENIS INFORMASI</strong>
                </p>
            </td>
            <td widht="5%">
                <p style="text-align:center;">
                    <strong>CHECK</strong>
                </p>
                <p style="text-align:center;">
                    <strong>LIST</strong>
                </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>
                Peraturan rumah sakit tentang hak dan kewajiban pasien / keluarga
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i]" id="a_i" onclick="checkthis('a_i')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                2
            </td>
            <td>
                Perawat yang bertugas
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i1]" id="a_i1" onclick="checkthis('a_i1')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                3
            </td>
            <td>
                <p>
                Fasilitas kamar pasien, meliputi:
                </p>
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i2]" id="a_i2" onclick="checkthis('a_i2')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data">a. Fasilitas yang diperoleh pasien dari rumah sakit</span>
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i3]" id="a_i3" onclick="checkthis('a_i3')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>b. Menunjukkan kamar mandi, lemari / laci pakaian serta tempat barang bawaan pasien.
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i4]" id="a_i4" onclick="checkthis('a_i4')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>c. Menjelaskan letak bel untuk memanggil perawat
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i5]" id="a_i5" onclick="checkthis('a_i5')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>d. Menjelaskan cara pemakaian telepon dan televisi
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i6]" id="a_i6" onclick="checkthis('a_i6')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                4
            </td>
            <td>
                Pemeriksaan kesehatan, meliputi
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i7]" id="a_i7" onclick="checkthis('a_i7')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>a. Pemeriksaan dan  tindakan medis yang akan dilakukan oleh dokter / perawat / petugas selama dirawat
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i8]" id="a_i8" onclick="checkthis('a_i8')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>b. Pemeriksaan oleh dokter yang merawat akan dilakukan pada saat-saat tertentu
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i9]" id="a_i9" onclick="checkthis('a_i9')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>c. Pemeriksaan tanda-tanda vital 2-4 kali sehari atau sesuai kondisi pasien
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i10]" id="a_i10" onclick="checkthis('a_i10')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data"></span>d. Penjelasan diit oleh petugas gizi
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i11]" id="a_i11" onclick="checkthis('a_i11')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <span class="sub_data">e. Pasien mandi 2 kali sehari dan sewaktu-waktu bila diperlukan
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i12]" id="a_i12" onclick="checkthis('a_i12')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                5
            </td>
            <td>
                Waktu konsultasi dokter
            </td>
            <td style="text-align: center;">
                <label>
                    <input type="checkbox" class="ace" name="form_40[a_i13]" id="a_i13" onclick="checkthis('a_i13')">
                    <span class="lbl"></span>
                </label>
            </td>
        </tr>
    </tbody>
</table>



<!--END MAIN CONTENT -->

<br><br>
<hr>
<?php echo $footer; ?>
