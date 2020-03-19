<script type="text/javascript">

$(document).ready(function() {
  //initiate dataTables plugin
    oTableTindakan = $('#table-tindakan-hd').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "hemodialisa/Hd_pelayanan_pasien/get_data_tindakan?bagian=013101&jenis=tindakan&kode=<?php echo $no_kunjungan?>",
          "type": "POST"
      },

    });

    oTableObat = $('#table-obat-hd').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "hemodialisa/Hd_pelayanan_pasien/get_data_obat?bagian=013101&jenis=obat&kode=<?php echo $no_kunjungan?>",
          "type": "POST"
      },

    });

    $('#InputKeyTindakan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getTindakanByBagianAutoComplete/013101",
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
          console.log(val_item);
          $('#hd_kode_tindakan_hidden').val(val_item);
          $('#InputKeyDokterBagian1').focus();
          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, 16);
        }

    });

    $('#InputKeyDokterBagian1').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: { keyword:query, bag:'013101' },            
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
          console.log(val_item);
          $('#hd_kode_dokter_hidden1').val(val_item);
          $('#btn_add_tindakan').focus();
        }

    });

    $('#InputKeyDokterBagian2').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getDokterByBagian",
                data: { keyword:query, bag:'013101' },            
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
          console.log(val_item);
          $('#hd_kode_dokter_hidden2').val(val_item);
          $('#btn_add_tindakan').focus();
        }

    });

    $('#btn_add_tindakan').click(function (e) {   
      e.preventDefault();
      /*process add tindakan*/
      $.ajax({
          url: "hemodialisa/Hd_pelayanan_pasien/process_add_tindakan",
          data: $('#form_pelayanan_hd').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              if(confirm('Apakah anda akan menambahkan tindakan lainnya?')){
                e.preventDefault();
                $('#InputKeyTindakan').focus(); 
              }else{
                e.preventDefault();
                var scrollPos =  $("#inputKeyObat").offset().top;
                $(window).scrollTop(scrollPos);
                $('#inputKeyObat').focus(); 
              }

              /*reset all field*/
              $('#InputKeyTindakan').val('');
              $('#hd_kode_tindakan_hidden').val('');
              $('#InputKeyDokterBagian1').val('');
              $('#InputKeyDokterBagian2').val('');
              $('#hd_kode_dokter_hidden1').val('');
              $('#hd_kode_dokter_hidden2').val('');
              $('#detailTarifHtml').html('');
            }else{
              alert('Error'); return false;
            }
            
          }
      });

    });

    $('#btn_add_obat').click(function (e) {  

      e.preventDefault();
      /*process add obat*/
      $.ajax({
          url: "hemodialisa/Hd_pelayanan_pasien/process_add_obat",
          data: $('#form_pelayanan_hd').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              if(confirm('Apakah anda akan menambahkan obat lainnya?')){
                e.preventDefault();
                $('#inputKeyObat').focus(); 
              }else{
                e.preventDefault();
                var scrollPos =  $("#inputKeyObat").offset().top;
                $(window).scrollTop(scrollPos);
                $('#inputKeyObat').focus(); 
              }

              /*reset all field*/
              $('#inputKeyObat').val('');
              $('#hd_kode_tindakan_hidden').val('');
              $('#div_detail_obat').hide('fast');
              $('#detailObatHtml').html('');
            }else{
              alert('Error'); return false;
            }
            
          }
      });

    });

    $('#inputKeyObat').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword:query, bag:'013101' },            
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
          console.log(val_item);
          $('#hd_kode_brg_hidden').val(val_item);
          $('#hd_jumlah_obat').focus();
          getDetailObatByKodeBrg(val_item, '013101');
        }

    });

    $( "#hd_jumlah_obat" ).keypress(function(event) { 
      var keycode =(event.keyCode?event.keyCode:event.which);  
      if(keycode ==13){  
        event.preventDefault(); 
        if($(this).valid()){  
          $('#btn_add_obat').focus();  
        }   
        return false;                 
      }
      
    });



});

function getDetailTarifByKodeTarifAndKlas(kode_tarif, kode_klas){

  $.getJSON("<?php echo site_url('templates/references/getDetailTarif') ?>?kode="+kode_tarif+"&klas="+kode_klas+"&type=html", '' , function (data) {

    /*show detail tarif html*/
    $('#detailTarifHtml').html(data.html);

  })

}

function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&bag="+kode_bag+"&type=html", '' , function (response) {
    if(response.sisa_stok==0){
      $('#btn_add_obat').hide('fast');
      $('#warning_stok_obat').html('<span style="color:red"><b><i>Stok sudah habis !</i></b></span>');
    }else{
      $('#btn_add_obat').show('fast');
      $('#warning_stok_obat').html('');
    }
    /*show detail tarif html*/
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);

  })

}

function reset_table(){
    oTableTindakan.ajax.url('hemodialisa/Hd_pelayanan_pasien/get_data_tindakan?bagian=013101&jenis=tindakan&kode=<?php echo $no_kunjungan?>').load();
    oTableObat.ajax.url('hemodialisa/Hd_pelayanan_pasien/get_data_obat?bagian=013101&jenis=obat&kode=<?php echo $no_kunjungan?>').load();
}

function delete_transaksi(myid){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'hemodialisa/Hd_pelayanan_pasien/delete',
        type: "post",
        data: {ID:myid},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            reset_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}


</script>

<!-- hidden form -->
<input type="hidden" class="form-control" name="no_kunjungan" value="<?php echo isset($value)?$value->no_kunjungan:''?>">
<input type="hidden" class="form-control" name="no_registrasi" value="<?php echo isset($value)?$value->no_registrasi:''?>">
<input type="hidden" class="form-control" name="kode_kelompok" value="<?php echo isset($value)?$value->kode_kelompok:''?>">
<input type="hidden" class="form-control" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>">
<input type="hidden" class="form-control" name="no_mr" value="<?php echo isset($value)?$value->no_mr:''?>">
<input type="hidden" class="form-control" name="nama_pasien_layan" value="<?php echo isset($value)?$value->nama_pasien:''?>">
<input type="hidden" class="form-control" name="kode_bagian_asal" value="<?php echo isset($value)?$value->kode_bagian_asal:''?>">


<p><b><i class="fa fa-edit"></i> DIAGNOSA DAN PEMERIKSAAN </b></p>

<div class="form-group">
    <label class="control-label col-sm-2" for="">Anamnesa</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" name="hd_anamnesa">
    </div>
    <label class="control-label col-sm-2" for="">Diagnosa (*)</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" name="hd_diagnosa">
    </div>
</div>


<div class="form-group">
    <label class="control-label col-sm-2" for="">Pemeriksaan</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" name="hd_pemeriksaan">
    </div>
    <label class="control-label col-sm-2" for="">Pengobatan</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" name="hd_pengobtan">
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
      <br>
        <p><b><i class="fa fa-edit"></i> TINDAKAN PASIEN </b></p>

        <div class="form-group">
            <label class="control-label col-sm-4" for="">Nama Tindakan</label>
            <div class="col-sm-8">
               <input type="text" class="form-control" id="InputKeyTindakan" name="hd_nama_tindakan" placeholder="Masukan Keyword Tindakan">
               <input type="hidden" class="form-control" id="hd_kode_tindakan_hidden" name="hd_kode_tindakan_hidden" >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-4" for="">Nama Dokter</label>
            <div class="col-sm-8">
               <input type="text" class="form-control" id="InputKeyDokterBagian1" name="hd_nama_dokter1" placeholder="Masukan Keyword Nama Dokter">
               <input type="hidden" class="form-control" id="hd_kode_dokter_hidden1" name="hd_kode_dokter_hidden1" >
            </div>
        </div>

        <!-- <div class="form-group">
            <label class="control-label col-sm-4" for="">Nama Dokter 2</label>
            <div class="col-sm-8">
               <input type="text" class="form-control" id="InputKeyDokterBagian2" name="hd_nama_dokter2" placeholder="Masukan Keyword Nama Dokter">
               <input type="hidden" class="form-control" id="hd_kode_dokter_hidden2" name="hd_kode_dokter_hidden2" >
            </div>
        </div> -->

        <div class="form-group">
            <label class="control-label col-sm-4" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_tindakan"> <i class="fa fa-plus"></i> Tambahkan </a>
            </div>
        </div>

    </div>

    <div class="col-sm-6">
      <br>
      <div id="detailTarifHtml" style="margin-left:-5%"></div>
    </div>

    <div>
      <table id="table-tindakan-hd" class="table table-bordered table-hover">
         <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px"></th>
            <th width="100px">Kode</th>
            <th>Nama Tindakan</th>
            <th>Nama Dokter</th>
            <th width="150px">Total Tarif</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">
      <br>
        <p><b><i class="fa fa-edit"></i> OBAT YANG DIBERIKAN </b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Nama Obat</label>
            <div class="col-sm-6">
               <input type="text" class="form-control" id="inputKeyObat" name="hd_nama_obat" placeholder="Masukan Keyword Obat ">
               <input type="hidden" class="form-control" id="hd_kode_brg_hidden" name="hd_kode_brg_hidden">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">Jumlah</label>
            <div class="col-sm-2">
               <input type="text" class="form-control" name="hd_jumlah_obat" id="hd_jumlah_obat" value="1">
            </div>
        </div>

        <div class="form-group" style="display:none" id="div_detail_obat">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-10" style="margin-left:6px">
              <div id="warning_stok_obat"></div>
               <div id="detailObatHtml"></div>
            </div>
        </div>

        

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
               <a href="#" class="btn btn-xs btn-primary" id="btn_add_obat"> <i class="fa fa-plus"></i> Tambahkan </a>
            </div>
        </div>

    </div>

    <div style="margin-top:0px">
      <table id="table-obat-hd" class="table table-bordered table-hover">
         <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px"></th>
            <th width="100px">Kode</th>
            <th>Nama Obat</th>
            <th width="150px">Total Tarif</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

</div>





