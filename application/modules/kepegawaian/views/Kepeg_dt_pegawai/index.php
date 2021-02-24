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
    <form class="form-horizontal" method="post" id="form_search" action="tarif/Mst_tarif/find_data">

      <center>
          <h4>FORM PENCARIAN DATA PEGAWAI<br><small style="font-size:12px">Data yang ditampilkan adalah data pegawai aktif dan non aktif  </small></h4>
      </center>
      <br>
      <div class="form-group" style="margin-bottom: 3px">
        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_nama_tarif" id="checked_nama_tarif" type="checkbox" class="ace" value="1">
              <span class="lbl"> Unit</span>
            </label>
          </div>
        </div>
        <div class="col-md-3" style="margin-left: -15px">
          <?php echo $this->master->custom_selection(array('table'=>'kepeg_mt_unit', 'where'=>array(), 'id'=>'kepeg_unit_id', 'name' => 'kepeg_unit_nama'),'','unit','unit','chosen-slect form-control','','');?>
        </div>
      </div>

      <div class="form-group">
          <div class="control-label col-md-2">
            <div class="checkbox" style="margin-top: -5px">
              <label>
                <input name="checked_nama_tarif" id="checked_nama_tarif" type="checkbox" class="ace" value="1">
                <span class="lbl"> Nama Pegawai</span>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
              <input type="text" value="" name="nama_tarif" id="nama_tarif" class="form-control">
          </div>

          <div class="control-label col-md-2">
            <div class="checkbox" style="margin-top: -5px">
              <label>
                <input name="checked_jenis_tindakan" value="1" type="checkbox" class="ace">
                <span class="lbl"> Level Jabatan</span>
              </label>
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
              <?php echo $this->master->custom_selection(array('table'=>'kepeg_mt_level', 'where'=>array(), 'id'=>'kepeg_level_id', 'name' => 'kepeg_level_nama'),'','level_jabatan','level_jabatan','chosen-slect form-control','','');?>
          </div>
          <div class="col-md-2" style="margin-left: -15px">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Tampilkan Data
            </a>
          </div>
      </div>
      <hr class="separator">

      <div class="clearfix" style="margin-bottom:-5px">
        <?php echo $this->authuser->show_button('kepegawaian/Kepeg_dt_pegawai','C','',1)?>
          <?php echo $this->authuser->show_button('kepegawaian/Kepeg_dt_pegawai','D','',5)?>
      </div>

      <hr class="separator">
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="kepegawaian/Kepeg_dt_pegawai" data-id="flag=" url-detail="kepegawaian/Kepeg_dt_pegawai/show_detail" class="table table-bordered table-hover">
          <thead>
            <tr>  
              <th width="30px" class="center">
                <div class="center">
                  <label class="pos-rel">
                      <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                      <span class="lbl"></span>
                  </label>
                </div>
              </th>
              <th width="40px" class="center"></th>
              <th width="40px" class="center"></th>
              <th width="50px"></th>
              <th width="50px">Foto</th>
              <th width="150px">Nama Lengkap</th>
              <th>Pendidikan/Jenis Pegawai</th>
              <th>Unit/Bagian</th>
              <th>Jabatan/Golongan</th>
              <th>Status Karyawan</th>
              <th>Status Aktif</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      
    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>



