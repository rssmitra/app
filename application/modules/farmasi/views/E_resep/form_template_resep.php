<script>
  $('#btn_save_template').click(function (e) {  
        e.preventDefault();
        var formData = {
            kode_pesan_resep : $('#kode_pesan_resep_temp').val(),
            id_template : $('#id_template_form').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_dokter : $('#kode_dokter').val(),
            nama_resep : $('#nama_resep').val(),
            keterangan : $('#keterangan_resep_temp').val(),
        };
        $.ajax({
            url: "farmasi/E_resep/proses_template_resep",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  
                  // close modal
                  $('#globalModalViewSmall').modal('toggle');
                  oTable2.ajax.url("farmasi/E_resep/get_template_resep/"+$('#kode_dokter_poli').val()+"").load();
                  activeTab('#template_tab');
                }else{          
                    $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                } 
                achtungHideLoader();
            }
        });
    });

</script>
<div class="row">
  <div class="col-xs-12">  
    <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('farmasi/E_resep/save_template')?>" enctype="multipart/form-data" autocomplete="off">  
    
      <!-- hidden -->
      <input type="hidden" name="kode_pesan_resep_temp" id="kode_pesan_resep_temp" value="<?php echo $kode_pesan_resep?>">

      <div class="form-group">
        <label class="control-label col-sm-2">Nama Dokter</label>
        <div class="col-md-10">
        <?php echo $this->master->get_change($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), isset($value->kode_dokter ) ? $value->kode_dokter : $kode_dokter , 'kode_dokter', 'kode_dokter', 'form-control', '', '') ?>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">Nama Resep</label>
        <div class="col-md-10">
          <input type="text" class="form-control" name="nama_resep" id="nama_resep" value="<?php echo isset($value->nama_resep)?$value->nama_resep:''?>">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2">Deskripsi</label>
        <div class="col-md-10">
          <textarea class="form-control" style="height: 100px !important; margin-left: 6px !important" name="keterangan_resep_temp" id="keterangan_resep_temp"><?php echo isset($value->keterangan)?$value->keterangan:''?></textarea>
        </div>
      </div>
      <br>
      <div class="center">
          <a href="#" class="btn btn-xs btn-primary" id="btn_save_template"><i class="fa fa-save"></i> Simpan sebagai template</a>
      </div>
    </form>
  </div>
</div>

