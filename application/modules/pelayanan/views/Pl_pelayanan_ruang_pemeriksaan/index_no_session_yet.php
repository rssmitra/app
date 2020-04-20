<script>
$(document).ready(function(){
  
    $('#form_save_session').ajaxForm({
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
          getMenu('pelayanan/Pl_pelayanan');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('select[name="poliklinik"]').change(function () {      


        $.getJSON("<?php echo site_url('Templates/References/getDokterSpesialis') ?>/" + $(this).val(), '', function (data) {              

            $('#select_dokter option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));                         

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));                    
                  
            });      
  

        });    

    }); 

})

</script>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_save_session" action="pelayanan/Pl_pelayanan/saveSessionPoli">

    <input name="current_day" id="current_day" class="form-control" type="hidden" value="<?php echo $this->tanggal->gethari(date('D'))?>">

      <div class="col-md-12">

        <center><h4>FORM RUANG KHUSUS PEMERIKSAAN<br><small style="font-size:12px">Silahkan pilih Poli/Klinik dibawah ini <br>Untuk menampilkan data pasien sesuai dengan Poli/Klinik yang dipilih </small></h4></center>
        <hr>

        <div class="form-group">
            <label class="control-label col-md-2">Silahkan pilih Poli/Klinik</label>
            <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('has_observe_room' => 'Y')), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
            </div>

            <label class="control-label col-md-1">Dokter</label>
            <div class="col-md-4">
              <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
            </div>

            <div class="col-md-1" style="margin-left:-1%">
            <button type="submit" id="btn_save_session" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Save Session
            </button>
        </div>

        </div>

      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




