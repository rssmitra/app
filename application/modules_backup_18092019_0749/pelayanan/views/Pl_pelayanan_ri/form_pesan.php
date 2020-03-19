<script type="text/javascript">

jQuery(function($) {  

  $('.date-picker').datepicker({    

    autoclose: true,    

    todayHighlight: true    

  })  

  //show datepicker when clicking on the icon

  .next().on(ace.click_event, function(){    

    $(this).prev().focus();    

  });  

});

$(document).ready(function() {
  //initiate dataTables plugin
    oTablePesanVK = $('#table-pesan-vk').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_pesan_vk?kode_ri=<?php echo $kode_ri?>&no_registrasi=<?php echo $no_registrasi?>",
          "type": "POST"
      },

    });

    oTablePesanOK = $('#table-pesan-ok').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_pesan_ok?kode_ri=<?php echo $kode_ri?>&no_registrasi=<?php echo $no_registrasi?>",
          "type": "POST"
      },

    });

    oTablePesanPindah = $('#table-pesan-pindah').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": "pelayanan/Pl_pelayanan_ri/get_pesan_pindah?kode_ri=<?php echo $kode_ri?>&no_registrasi=<?php echo $no_registrasi?>",
          "type": "POST"
      },

    });


    $('input[name="tipe_pesan"]').click(function (e) {

        var value = $(this).val();
        if (value=='vk') {
          $('#data_vk').show('fast');
          $('#data_ok').hide('fast');
          $('#data_pesan_pindah').hide('fast');
        }else if (value=='ok') {
          $('#data_ok').show('fast');
          $('#data_vk').hide('fast');
          $('#data_pesan_pindah').hide('fast');
        }else if (value=='pindah') {
          $('#data_pesan_pindah').show('fast');
          $('#data_vk').hide('fast');
          $('#data_ok').hide('fast');
        }

      }); 

    $('#btn_cancel_pindah').click(function (e) {
      preventDefault();
        $('#data_vk').show('fast');
        $('#data_ok').hide('fast');
        $('#data_pesan_pindah').hide('fast');
        $('input[name=tipe_pesan][value=vk]').prop('checked', 'checked'); 

      }); 

   
    $('#btn_add_vk').click(function (e) {   
      e.preventDefault();

      if( $('#pl_tgl_pesan').val() == '' ){
        alert('Silahkan pilih Tanggal !'); return false;
      }

      if( $('#pl_ri_kamar_vk').val() == '' ){
        alert('Silahkan pilih kamar !'); return false;
      }

      /*process add pesan vk*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_pesan_vk",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              /*reset all field*/
              $('#pl_tgl_pesan').val(date);
              $('#pl_ri_kamar_vk').val('');
        
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

    $('#btn_add_ok').click(function (e) {   
      e.preventDefault();

      if( $('#pl_tgl_pesan').val() == '' ){
        alert('Silahkan pilih Tanggal !'); return false;
      }

      /*process add pesan ok*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_pesan_ok",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              /*reset all field*/
              $('#pl_tgl_pesan').val(date);
              $('#selected_time').val('');
              $('#inputKeyTindakanBedah').val('');
              $('#pl_tindakan_pesan_ok').val('');
              $('#pl_dokter_ok').val('');
              $('#detailTarifHtml').html('');
              $('#formDetailTarif').hide('fast');
        
            }else{
              alert(response.message); return false;
            }
            
          }
      });

    });

    $('#btn_add_pindah').click(function (e) {  

      e.preventDefault();
      /*process add obat*/
      $.ajax({
          url: "pelayanan/Pl_pelayanan_ri/process_add_pesan_pindah",
          data: $('#form_pelayanan').serialize(),            
          dataType: "json",
          type: "POST",
          success: function (response) {
            /*reset table*/
            reset_table();
            if(response.status==200) {
              var date = '<?php echo date('m/d/Y')?>';
              /*reset all field*/
              $('#pl_tgl_pesan').val(date);
              $.achtung({message: response.message, timeout:5});
            }else{
              alert('Silahkan cari pasien !'); return false;
            }
            
          }
      });

    });

    var kelas = ($('#klas_titipan').val()==0)?$('#kode_klas_val').val():$('#klas_titipan').val();
    $('#inputKeyTindakanBedah').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/References/getTindakanBedah",
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
            $('#pl_tindakan_pesan_ok').val(val_item);
            $('#inputKeyTindakanBedah').val(label_item);
            getDetailTarifByKodeTarifAndKlas(val_item, kelas);
        }

    });

});

function getDetailTarifByKodeTarifAndKlas(kode_tarif, kode_klas){

  $.getJSON("<?php echo site_url('templates/references/getDetailTarif') ?>?kode="+kode_tarif+"&klas="+kode_klas+"&type=html", '' , function (data) {

    /*show detail tarif html*/
    $('#formDetailTarif').show('fast');
    $('#detailTarifHtml').html(data.html);

  })

}

function reset_table(){
    oTablePesanVK.ajax.url('pelayanan/Pl_pelayanan_ri/get_pesan_vk?kode_ri=<?php echo $kode_ri?>&no_registrasi=<?php echo $no_registrasi?>').load();
    oTablePesanOK.ajax.url('pelayanan/Pl_pelayanan_ri/get_pesan_ok?kode_ri=<?php echo $kode_ri?>&no_registrasi=<?php echo $no_registrasi?>').load();
    oTablePesanPindah.ajax.url('pelayanan/Pl_pelayanan_ri/get_pesan_pindah?kode_ri=<?php echo $kode_ri?>&no_registrasi=<?php echo $no_registrasi?>').load();
}

function delete_transaksi(myid,type){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'pelayanan/Pl_pelayanan_ri/delete',
        type: "post",
        data: {ID:myid,type:type},
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

counterfile = <?php $j=2;echo $j.";";?>

function hapus_file(a, b)

{

  if(b != 0){
    /*$.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
        document.getElementById("file"+a).innerHTML = "";
        greatComplate(data);
    });*/
  }else{
    y = a ;
    document.getElementById("file"+a).innerHTML = "";
  }

}

function tambah_file()

{

  counternextfile = counterfile + 1;

  counterIdfile = counterfile + 1;

  document.getElementById("input_file"+counterfile).innerHTML = "<div id=\"file"+counternextfile+"\" class='clonning_form'><div class='form-group'><label class='control-label col-sm-2'>&nbsp;</label><div class='col-sm-4'><input type='text' class='form-control' onclick='getDokterAutoComplete("+counterfile+")' id='InputKeyDokterBagian"+counterfile+"' name='pl_nama_dokter[]' placeholder='Masukan Keyword Nama Dokter'><input type='hidden' class='form-control' id='pl_kode_dokter_hidden"+counterfile+"' name='pl_kode_dokter_hidden[]' ></div><div class='col-md-1' style='margin-left: -2%'><input type='button' onclick='hapus_file("+counternextfile+",0)' value=' x ' class='btn btn-xs btn-danger'/></div></div></div><div id=\"input_file"+counternextfile+"\"></div>";

  counterfile++;

}

</script>

<div class="row">

  <div class="col-md-12">

    <div class="form-group">
        <label class="control-label col-sm-2" for="">*Tanggal</label>
          <div class="col-md-3">
                
            <div class="input-group">
                
                <input name="pl_tgl_pesan" id="pl_tgl_pesan" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>">
                <span class="input-group-addon">
                  
                  <i class="ace-icon fa fa-calendar"></i>
                
                </span>
              </div>
          
          </div>
    </div>

    <div class="form-group">

        <label class="control-label col-sm-2">Tipe Pesan</label>

        <div class="col-md-10">

          <div class="radio">

              <label>

                <input name="tipe_pesan" type="radio" class="ace" value="vk" checked="checked"  />

                <span class="lbl"> VK (Ruang Bersalin)</span>

              </label>

              <label>

                <input name="tipe_pesan" type="radio" class="ace" value="ok"/>

                <span class="lbl"> OK (Ruang Operasi)</span>

              </label>

               <label>

                <input name="tipe_pesan" type="radio" class="ace" value="pindah"/>

                <span class="lbl"> Pindah Ruangan</span>

              </label>

          </div>

        </div>

    </div>

    <div id="data_vk">
        <br>
        <p><b><i class="fa fa-edit"></i> PESAN VK (Ruang Bersalin) </b></p>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">*Pilih Kamar</label>
            <div class="col-sm-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_ruangan', 'id' => 'no_kamar', 'name' => 'no_kamar', 'where' => array('kode_bagian' => '030501', 'status' => NULL)), '', 'pl_ri_kamar_vk', 'pl_ri_kamar_vk', 'form-control', '', '') ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="">&nbsp;</label>
            <div class="col-sm-4" style="margin-left:6px">
              <a href="#" class="btn btn-xs btn-primary" id="btn_add_vk"> Pesan </a>
            </div>
        </div>  
        

        
          <table id="table-pesan-vk" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="50px"></th>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Ruangan Asal</th>
                <th>Kelas</th>
                <th>Kamar</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        

    </div>

    <div id="data_ok" style="display:none">

        <div class="col-sm-12">
            
            <br>

            <p><b><i class="fa fa-edit"></i> PESAN OK (Ruang Operasi)</b></p>

          <div class="form-group">

            <!-- <label class="control-label col-sm-2">*Jam</label>
    
            <div class="col-sm-4">
                
                <div class="input-group">
                    <input name="selected_time" id="selected_time" placeholder="hh:mm" class="form-control" type="text" >
                </div>
            
            </div> -->

            <label class="control-label col-sm-2">Jenis Layanan</label>

            <div class="col-md-2">

              <div class="radio">

                  <label>

                    <input name="jenis_layanan_pesan_ok" type="radio" class="ace" value="0"  checked />

                    <span class="lbl"> Biasa</span>

                  </label>

                  <label>

                    <input name="jenis_layanan_pesan_ok" type="radio" class="ace" value="1" />

                    <span class="lbl">Cito</span>

                  </label>

              </div>

            </div>
            
          </div>
          

            <div class="form-group">

                <label class="control-label col-sm-2" for="City">*Nama Tindakan</label>

                <div class="col-sm-4">

                    <input id="inputKeyTindakanBedah" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

                    <input type="hidden" name="pl_tindakan_pesan_ok" id="pl_tindakan_pesan_ok" class="form-control">

                </div>

                <label class="control-label col-sm-2" for="City">*Dokter</label>

                <div class="col-sm-4">

                    <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '030901')), '' , 'pl_dokter_ok', 'pl_dokter_ok', 'form-control', '', '') ?>

                </div>

            </div>

            <div class="form-group" id="formDetailTarif" style="display:none">
                <label class="control-label col-sm-2" for="">&nbsp;</label>
                <div class="col-sm-10" style="margin-left:6px">
                  <div id="detailTarifHtml"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="">&nbsp;</label>
                <div class="col-sm-4" style="margin-left:6px">
                  <a href="#" class="btn btn-xs btn-primary" id="btn_add_ok"> Pesan </a>
                </div>
            </div>
          
          <div>
            <table id="table-pesan-ok" class="table table-bordered table-hover">
              <thead>
                <tr>  
                  <th width="50px"></th>
                  <th>ID</th>
                  <th>Tanggal</th>
                  <th>Jenis Layanan</th>
                  <th>Bedah</th>
                  <th>Tindakan</th>
                  <th>Dokter</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>

        </div>

    </div>

    <div id="data_pesan_pindah" style="display:none">
            
        <div class="alert alert-info center">
          <b><h4>Pemberitahuan !</h4></b>Apakah anda yakin akan memindahkan pasien ke ruangan lain?<br><br>
          <a href="#" class="btn btn-xs btn-primary" id="btn_add_pindah"> Ya, Pesan sekarang </a>
          <a href="#" class="btn btn-xs btn-danger" id="btn_cancel_pindah"> Tidak, Batalkan </a>
        </div>

        <div class="form-group">
          
            <div class="col-sm-4" style="margin-left:6px">
              
            </div>
        </div>

        <div>
          <table id="table-pesan-pindah" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th>ID</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Ruangan</th>
                <th>Kelas</th>
                <th>Kamar</th>
                <th>Bed</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

    </div>

  </div>
    

     
</div>







