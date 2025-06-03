<script>

$(document).ready(function(){
    
    $('#tabs_detail_pasien').load('registration/reg_pasien/form_modal_/'+$('#no_mr').val()+'');

    $('#form_rm_pasien').ajaxForm({
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
          getMenu('rekam_medis/Rm_pasien/form/'+$('#no_registrasi_hidden').val()+'/'+$('#form_type').val()+'');
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    
    $('#btn_barcode_pasien').click(function (e) {   
      var no_mr = $('#no_mr').val();
      if( no_mr == '' ){
        alert('Silahkan cari pasien terlebih dahulu !'); return false;
      }else{
        url = 'registration/Reg_pasien/barcode_pasien/'+no_mr+'/1';
        title = 'Cetak Barcode';
        width = 600;
        height = 450;
        PopupCenter(url, title, width, height);
      }
    });


})

function get_riwayat_medis_pasien(){

noMr = $('#no_mr').val();
if (noMr == '') {
  alert('Silahkan cari pasien terlebih dahulu !'); return false;
}else{
  getMenuTabsHtml('templates/References/get_riwayat_medis/'+noMr, 'tabs_detail_pasien');
}

}

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<!-- PAGE CONTENT BEGINS -->
<div class="invisible">
  <button data-target="#sidebar2" data-toggle="collapse" type="button" class="pull-left navbar-toggle collapsed">
    <span class="sr-only">Toggle sidebar</span>
    <i class="ace-icon fa fa-dashboard white bigger-125"></i>
  </button>

  <div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state">
    <div class="center">
      <ul class="nav nav-list">

        <li class="hover">
          <a href="#" data-toggle="tab" onclick="getMenuTabs('registration/reg_pasien/form_modal_/<?php echo $value->no_mr; ?>', 'tabs_detail_pasien')"><i class="menu-icon fa fa-user bigger-150"></i><span class="menu-text"> Data Pasien</span></a><b class="arrow"></b>
        </li>

        <li class="hover">
          <a href="#" data-toggle="tab" onclick="getMenuTabs('rekam_medis/Rm_pasien/form_diagnosa/<?php echo $no_registrasi; ?>', 'tabs_detail_pasien')"><i class="menu-icon fa fa-home bigger-150"></i><span class="menu-text"> Form Resume</span></a><b class="arrow"></b>
        </li>

        <li class="hover">
          <a href="#" data-toggle="tab" id="btn_barcode_pasien"><i class="menu-icon fa fa-barcode"></i><span class="menu-text"> Barcode </span></a><b class="arrow"></b>
        </li>
        <li class="hover">
          <a data-toggle="tab" id="tabs_rekam_medis_id" href="#" data-id="0" data-url="templates/References/get_riwayat_medis" onclick="get_riwayat_medis_pasien()"><i class="menu-icon fa fa-stethoscope"></i><span class="menu-text"> Riwayat Medis </span></a><b class="arrow"></b>
        </li>

        <!-- <li class="hover">
          <a data-toggle="tab" href="#" data-id="75780" data-url="" id="tabs_rekam_medis" onclick="getMenuTabs('rekam_medis/File_rm/index/<?php echo $reg->no_mr?>', 'tabs_detail_pasien')"><i class="menu-icon fa fa-clipboard"></i><span class="menu-text"> E R M  </span></a><b class="arrow"></b>
        </li> -->

        <li class="hover">
          <a data-toggle="tab" id="tabs_riwayat_kunjungan_id" href="#" data-id="0" data-url="registration/reg_pasien/riwayat_kunjungan" onclick="getMenuTabs('rekam_medis/Rm_pasien/riwayat_kunjungan/<?php echo $reg->no_mr?>', 'tabs_detail_pasien')"><i class="menu-icon fa fa-leaf"></i><span class="menu-text"> Kunjungan </span></a><b class="arrow"></b>
        </li>
        <li class="hover">
          <a data-toggle="tab" data-id="0" data-url="registration/reg_pasien/riwayat_perjanjian" id="tabs_riwayat_perjanjian_id" href="#" onclick="getMenuTabs('rekam_medis/Rm_pasien/riwayat_perjanjian/<?php echo $reg->no_mr?>', 'tabs_detail_pasien')"><i class="menu-icon fa fa-history"></i><span class="menu-text"> Riwayat Perjanjian </span></a><b class="arrow"></b>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
          <form class="form-horizontal" method="post" id="form_rm_pasien" action="<?php echo site_url('rekam_medis/Rm_pasien/process')?>" enctype="multipart/form-data">
          <!-- <p><b><i class="fa fa-user"></i> REGISTRASI PASIEN </b></p> -->
            <p style="margin-top: 20px">
              <span style="font-size: 14px; font-weight: bold"><?php echo $reg->no_mr.' - '.$reg->nama_pasien?></span><br>
                No Registrasi. <span><?php echo $reg->no_registrasi; ?></span> &nbsp;&nbsp;&nbsp; Tgl. <?php echo $this->tanggal->formatDateTime($reg->tgl_jam_masuk)?><br>
                <?php echo strtoupper($reg->nama_bagian); ?><br>
                <?php echo strtoupper($reg->nama_pegawai); ?>
            </p>
            <!-- hidden form -->
            <input name="no_mr" id="no_mr" value="<?php echo $reg->no_mr?>" class="form-control" type="hidden">
            <input name="nama_pasien" id="nama_pasien" value="<?php echo $reg->nama_pasien?>" placeholder="" class="form-control" type="hidden" >
            <input name="no_registrasi_hidden" id="no_registrasi_hidden" value="<?php echo $no_registrasi?>" class="form-control" type="hidden">
            <input name="form_type" id="form_type" value="RJ" class="form-control" type="hidden">


            <div id="tabs_detail_pasien" style="margin-top: 20px"></div>
            
          </form>
        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


