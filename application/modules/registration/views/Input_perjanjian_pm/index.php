<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
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
});

$('select[name="klinik"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian_') ?>/" + $(this).val() , function (data) {              

            $('#dokter option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#dokter'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#dokter'));                    

            });                

        });            

    } else {          

        $('#dokter option').remove()            

    }        

}); 

$("#button_konfirmasi").click(function(event){
      event.preventDefault();
      var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
        return $(this).val();
      }).toArray();
      konfirmasi_kunjungan(searchIDs);
});

$( ".form-control" )    
  .keypress(function(event) {  
    var keycode =(event.keyCode?event.keyCode:event.which);        
    if(keycode ==13){       
      event.preventDefault();      
      if($(this).valid()){     
        $('#btn_search_data').focus();   
      }        
      return false;     
    }   
}); 

function konfirmasi_kunjungan(myid){
  $.ajax({
    url: 'registration/Input_perjanjian_pm/find_data',
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
        getMenu('registration/Input_perjanjian_pm/konfirmasi_kunjungan?'+jsonResponse.data+'')
      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }

  });
}

function cetak_surat_kontrol(ID) {   
  var no_mr = $('#tabs_riwayat_perjanjian_id').attr('data-id');  
  if( no_mr == '' ){
    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  }else{
    url = 'registration/Reg_pasien/surat_control?id_tc_pesanan='+ID;
    title = 'Cetak Barcode';
    width = 850;
    height = 500;
    PopupCenter(url, title, width, height);
  }

}


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

    <form class="form-horizontal" method="post" id="form_search" action="registration/Input_perjanjian_pm/find_data">

    <div class="col-md-12 no-padding">

      <center><h4>FORM PENCARIAN DATA PERJANJIAN RADIOLOGI<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" class="form-control">
              <option value="no_mr">No MR</option>
              <option value="nama">Nama Pasien</option>
            </select>
          </div>
          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword">
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">*Dokter</label>
        <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '050201', 'status' => 0)), '' , 'dokter', 'dokter', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Bulan Kunjungan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan('' , 'bulan', 'bulan', 'form-control', '','') ?>
          </div>
          <div class="col-md-5 no-padding">
            &nbsp;
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
            <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
              <i class="fa fa-file-word-o bigger-110"></i>
              Export Excel
            </a>
          </div>
      </div>

    </div>
    <div class="clearfix"></div>
    <!-- <a href="#" onclick="getMenu('registration/Input_perjanjian_pm/form')" class="btn btn-xs btn-primary"><i class="menu-icon fa fa-calendar"></i><span class="menu-text"> Perjanjian Pasien </span></a> -->
    <a href="#" id="button_konfirmasi" class="btn btn-xs btn-success"><i class="menu-icon fa fa-bullhorn"></i><span class="menu-text"> Konfirmasi Kunjungan</span></a>
    <a href="#" id="button_delete" class="btn btn-xs btn-danger"><i class="menu-icon fa fa-trash"></i><span class="menu-text"> Hapus yang dipilih</span></a>

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Input_perjanjian_pm" class="table table-bordered table-hover">
       <thead>
        <tr>  
          <th width="30px" class="center">No</th>
          <th></th>
          <th>Nama Pasien</th>
          <th>Penjamin</th>
          <!-- <th>Tujuan</th> -->
          <th>Nama Dokter</th>
          <th>Tindakan</th>
          <th>Bulan Kunjungan</th>
          <th>No. Telp</th>
          <th style="max-width: 200px">Keterangan</th>
          <th>Konfirmasi<br>Berkunjung</th>
          
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>



