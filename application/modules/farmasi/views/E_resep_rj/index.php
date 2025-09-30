<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
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

$( ".form-control" )
  .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      $('#btn_search_data').click();
      return false;       
    }
});

function popUnder(node) {
    var newWindow = window.open("about:blank", node.target, "width=700,height=500"); 
    window.focus();
    newWindow.location.href = node.href;
    return false;
}


function rollback(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/rollback',
        type: "post",
        data: { ID : id },
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
            // show poup cetak resep
            reload_table();

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

function udpateStatusVerif(kode_pesan_resep){
  preventDefault();
  var checked = $("#status_verif_"+kode_pesan_resep+"").is(':checked');
  console.log(checked);
  $.ajax({
    url: 'farmasi/process_entry_resep/update_status_verif',
    type: "post",
    data: { ID : kode_pesan_resep, status : checked },
    dataType: "json",
    beforeSend: function() {
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, 'className' : 'achtungFail'});
      }
      achtungHideLoader();
    }

  });
  
}

function udpateStatusLock(kode_pesan_resep){
  preventDefault();
  var checked = $("#status_lock_"+kode_pesan_resep+"").is(':checked');
  console.log(checked);
  $.ajax({
    url: 'farmasi/process_entry_resep/update_status_lock',
    type: "post",
    data: { ID : kode_pesan_resep, status : checked },
    dataType: "json",
    beforeSend: function() {
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, 'className' : 'achtungFail'});
      }
      achtungHideLoader();
    }

  });
  
}

function udpateStatusVerifperItem(id){
  preventDefault();
  var checked = $("#status_verif_"+id+"").is(':checked');
  console.log(checked);
  $.ajax({
    url: 'farmasi/process_entry_resep/update_status_verif_per_item',
    type: "post",
    data: { ID : id, status : checked },
    dataType: "json",
    beforeSend: function() {
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, 'className' : 'achtungFail'});
      }
      achtungHideLoader();
    }

  });
  
}

function cancel_resep(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
      url: 'farmasi/process_entry_resep/cancel_resep',
      type: "post",
      data: { ID : id },
      dataType: "json",
      beforeSend: function() {
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          reload_table();
          $.achtung({message: jsonResponse.message, timeout:5});
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, 'className' : 'achtungFail'});
        }
        achtungHideLoader();
      }
    });
  }
}

function rollback_cancel_resep(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
      url: 'farmasi/process_entry_resep/rollback_cancel_resep',
      type: "post",
      data: { ID : id },
      dataType: "json",
      beforeSend: function() {
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status === 200){
          reload_table();
          $.achtung({message: jsonResponse.message, timeout:5});
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, 'className' : 'achtungFail'});
        }
        achtungHideLoader();
      }
    });
  }
}

function saveCatatanVerif(id){
  preventDefault();
  $.ajax({
    url: 'farmasi/process_entry_resep/save_catatan_verif',
    type: "post",
    data: { ID : id, catatan : $("#catatan_verif_"+id+"").val() },
    dataType: "json",
    beforeSend: function() {
    },
    uploadProgress: function(event, position, total, percentComplete) {
    },
    complete: function(xhr) {     
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      if(jsonResponse.status === 200){
        $.achtung({message: jsonResponse.message, timeout:5});
      }else{
        $.achtung({message: jsonResponse.message, timeout:5, 'className' : 'achtungFail'});
      }
      achtungHideLoader();
    }
  });
}

if(!ace.vars['touch']) {
      $('.chosen-select').chosen({allow_single_deselect:true}); 
  //resize the chosen on window resize

  $(window)
  .off('resize.chosen')
  .on('resize.chosen', function() {
    $('.chosen-select').each(function() {
        var $this = $(this);
        $this.next().css({'width': $this.parent().width()});
    })
  }).trigger('resize.chosen');

}

$('#resep_batal').on('change', function() {
  $('#btn_search_data').click();
});

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

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data" autocomplete="off">
      
      <input type="hidden" name="kode_profit" id="kode_profit" value="2000">
      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

      <div class="form-group">
        <label class="control-label col-md-2">Pencarian berdasarkan</label>
        <div class="col-md-2">
          <select name="search_by" class="form-control">
            <option value="no_mr">No MR</option>
            <option value="nama_pasien">Nama Pasien</option>
          </select>
        </div>
        <label class="control-label col-md-1">Keyword</label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="keyword" id="keyword">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d</label>
          <div class="col-md-2" style="margin-lef:-10px">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Poli/Klinik</label>
        <div class="col-md-3">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1,'status_aktif' => 1), 'where_in' => array('col' => 'validasi', 'val' => array('0100','0300','0500')) ), '' , 'bagian', 'bagian', 'chosen-select form-control', '', '') ?>
        </div>
        <label class="control-label col-md-1">Dokter</label>
          <div class="col-md-4">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'dokterHidden', 'dokterHidden', 'chosen-select form-control', '', '') ?>
          </div>
      </div>

      <div class="col-md-12" style="margin-left: 10px">
          <label class="inline" style="margin-top: 4px;margin-left: -20px;">
        <input type="checkbox" class="ace" name="resep_batal" id="resep_batal" value="1">
        <span class="lbl" style="font-weight: bold; font-style: italic"> Tampilkan resep batal</span>
          </label>
      </div>
      
      <div class="form-group">
        <label class="col-md-2 ">&nbsp;</label>
        <div class="col-md-10" style="margin-left: 5px">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
        </div>
      </div>
      <hr class="separator">
      <!-- div.dataTables_borderWrap -->
      <div class="row">
        <div class="col-md-12">
          <table id="dynamic-table" base-url="farmasi/E_resep_rj/get_data" data-id="flag=<?php echo $flag?>" url-detail="farmasi/Farmasi_pesan_resep/getDetail" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th class="center" width="50px"></th>
                <th width="40px" class="center"></th>
                <th width="40px"></th>
                <th style="width: 50px">Kode</th>
                <th style="width: 130px">Tgl Pesan</th>
                <th style="width: 100px">Jenis Resep</th>
                <th style="width: 100px">No MR</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Asal Unit</th>
                <th>Penjamin</th>
                <?php if($flag == 'RJ') : ?>
                <th width="180px">Diagnosa Akhir</th>
                <?php else: ?>
                  <th width="180px">Keterangan</th>
                <?php endif;  ?>
                <th width="90px">Status Resep</th>
                <?php if($flag == 'RJ') : ?>
                <th width="80px">Verif Apol</th>
                <?php endif;?>
                <th width="80px">Terima Resep</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>




