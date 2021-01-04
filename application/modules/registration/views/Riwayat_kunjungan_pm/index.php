<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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

var kode_bagian = '<?php echo $kode_bagian ?>';

$(document).ready(function(){

  
  url = (kode_bagian!=0)?'registration/Riwayat_kunjungan_pm/get_data?bagian_tujuan='+kode_bagian+' ':'registration/Riwayat_kunjungan_pm/get_data';
  oTable = $('#dynamic-table').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bPaginate": true,
    "pageLength": 50,
    "bLengthChange": false,
    "bInfo": true,
    "ajax": {
        "url": url,
        "type": "POST"
    },

  });

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
      url: 'registration/Riwayat_kunjungan_pm/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data,'registration/Riwayat_kunjungan_pm');
      }
    });
  });

  $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        $('#form_search')[0].reset();
    });

  $('#inputDokter').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getDokterByBagian",
                  data: 'keyword=' + query + '&bag=' + $('#bagian_asal').val(),         
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
          $('#dokter').val(val_item);
          
      }
  });

});

$('select[name="bagian_asal"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val() , function (data) {              

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

function find_data_reload(result){

  oTable.ajax.url('registration/Riwayat_kunjungan_pm/get_data?'+result.data).load();
  $("html, body").animate({ scrollTop: "400px" });

}

function rollback(kode_penunjang, flag){

  preventDefault();  

  achtungShowLoader();

  $.ajax({
      url: "pelayanan/Pl_pelayanan_pm/rollback",
      data: { kode_penunjang: kode_penunjang, kode_bagian: kode_bagian,flag: flag},            
      dataType: "json",
      type: "POST",
      complete: function (xhr) {
        var data=xhr.responseText;  
        var jsonResponse = JSON.parse(data);  
        if(jsonResponse.status === 200){  
          $.achtung({message: jsonResponse.message, timeout:5}); 
          getMenu('pelayanan/Pl_pelayanan_pm?type_tujuan='+kode_bagian);
        }else{          
          $.achtung({message: jsonResponse.message, timeout:5});  
        } 
        achtungHideLoader();
      }
  });

}

function cetak_slip(kode_penunjang) {
  
  noMr = $('#noMrHidden').val();
  url = 'pelayanan/Pl_pelayanan_pm/slip?kode_penunjang='+kode_penunjang+'';
  title = 'Cetak Slip';
  width = 500;
  height = 600;
  PopupCenter(url, title, width, height); 

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

    <form class="form-horizontal" method="post" id="form_search" action="#">

    <div class="col-md-12">

      <center><h4>FORM PENCARIAN DATA KUNJUNGAN<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data per Hari ini, yaitu tanggal <?php echo date('d/m/Y')?> </small></h4></center>
      <br>

      <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="">-Silahkan Pilih-</option>
              <option value="no_mr" selected>No MR</option>
              <option value="nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <label class="control-label col-md-1">Keyword</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="keyword" id="keyword_form">
          </div>

      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan('' , 'bulan', 'bulan', 'form-control', '','') ?>
          </div>
          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <?php echo $this->master->get_tahun('' , 'tahun', 'tahun', 'form-control', '', '') ?>
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-2">Bagian Asal</label>
          <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'group_bag' => 'Detail', 'status_aktif' => 1) ), '' , 'bagian_asal', 'bagian_asal', 'form-control', '', '') ?>
          </div>
      </div>
   
      <div class="form-group" <?php echo ($kode_bagian!=0)?'style="display:none"':''?> >
          <label class="control-label col-md-2">Bagian Tujuan</label>
          <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1, 'group_bag' => 'Detail', 'status_aktif' => 1) ), ($kode_bagian!=0)?$kode_bagian:'' , 'bagian_tujuan', 'bagian_tujuan', 'form-control', '', '') ?>
          </div>
      </div>
     
      <?php if($type!='PM'): ?>
      <div class="form-group">
          <label class="control-label col-md-2">Dokter</label>
          <div class="col-md-4">
              <!-- <?php //echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'dokter', 'dokter', 'form-control', '', '') ?> -->

              <input id="inputDokter" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

              <input type="hidden" name="dokter" id="dokter" class="form-control">
          </div>
      </div>
      <?php endif ?>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Kunjungan</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10">
          <button type="submit" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </button>
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

    <hr class="separator">
    <!-- div.dataTables_borderWrap -->
    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="registration/Riwayat_kunjungan_pm" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th></th>
            <th>No Kunjungan</th>
            <th>Nama Pasien</th>
            <th>Asal Bagian</th>
            <th>Tujuan Bagian</th>
            <th>Dokter</th>
            <th>Tanggal Masuk</th>
            <th>Tanggal Keluar</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->



