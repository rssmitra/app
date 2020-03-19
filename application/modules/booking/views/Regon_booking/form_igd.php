<p style="margin-left:-1%"><b><i class="fa fa-edit"></i> PENDAFTARAN PASIEN IGD</b></p>

<div class="form-group">
                
  <label class="control-label col-sm-2">Tanggal Kejadian</label>
  
  <div class="col-md-2">
    
    <div class="input-group">
        
        <input name="tgl_registrasi" id="tgl_registrasi" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
        <span class="input-group-addon">
          
          <i class="ace-icon fa fa-calendar"></i>
        
        </span>
      </div>
  
  </div>

  <label class="control-label col-sm-2" for="Province">*Jenis Kejadian</label>

      <div class="col-sm-3">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'dc_jns_celaka', 'id' => 'jns_celaka', 'name' => 'jns_celaka', 'where' => array('flag_celaka' => 1)), '' , 'jns_kejadian_igd', 'jns_kejadian_igd', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-2">*Tempat Kejadian</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="">
  
  </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-2">Dikirim Oleh</label>
  
  <div class="col-md-2">
    
    <input type="text" class="form-control" name="">
  
  </div>

  <label class="control-label col-sm-2">Diantar Oleh</label>
  
  <div class="col-md-2">
    
    <input type="text" class="form-control" name="">
  
  </div>

</div>


<div class="form-group">
                
  <label class="control-label col-sm-2">Dibawa RS dengan</label>
  
  <div class="col-md-2">
    
    <input type="text" class="form-control" name="">
  
  </div>

  <label class="control-label col-sm-2">*Status Diterima</label>
  
  <div class="col-md-2">
    
    <select name="status_diterima_igd" class="form-control">
      
      <option value="Hidup">Hidup </option>
      
      <option value="Meninggal">DOA/Death On Arrival </option>
    
    </select>
  
  </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Rujukan</label>

      <div class="col-sm-4">

          <select name="rujukan_igd" class="form-control">
            
            <option value="Kemauan sendiri/Keluarga">Kemauan sendiri/Keluarga (Non Rujukan)</option>
            
            <option value="Instansi Kesehatan">Instansi Kesehatan ( Rujukan)</option>
            
            <option value="Petugas Kesehatan">Petugas Kesehatan ( Rujukan)</option>
            
            <option value="Polisi">Polisi ( Rujukan)</option>
            
            <option value="Hukum">Hukum ( Rujukan)</option>
          
          </select>

      </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Dokter</label>

      <div class="col-sm-4">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '020101') ), '' , 'dokter_jaga_igd', 'dokter_jaga_igd', 'form-control', '', '') ?>

      </div>

</div>

<p style="margin-left:-1%"><b><i class="fa fa-user"></i> KELUARGA TERDEKAT</b></p>

<div class="form-group">
                
  <label class="control-label col-sm-2">Nama </label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="">
  
  </div>

</div>

<div class="form-group">
                
  <label class="control-label col-sm-2">Alamat</label>
  
  <div class="col-md-3">
    
    <textarea name="" class="form-control" height="50px"></textarea>
  
  </div>

  <label class="control-label col-sm-2">No Telp</label>
  
  <div class="col-md-3">
    
    <input type="text" class="form-control" name="">
  
  </div>

</div>


