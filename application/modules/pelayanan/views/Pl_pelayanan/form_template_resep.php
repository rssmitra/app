<div class="row">
  <div class="col-xs-12">  
    <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   
      <div class="form-group">
        <label class="control-label col-sm-2">Nama Resep</label>
        <div class="col-md-5">
          <textarea class="form-control" name="nama_resep" style="height:50px !important"></textarea>
      </div>
      <div class="center">
          <a href="#" class="btn btn-xs btn-primary" onclick="save_template()"><i class="fa fa-save"></i> Simpan</a>
      </div>
    </form>
  </div>
</div>

