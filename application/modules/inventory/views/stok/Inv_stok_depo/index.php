<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

  $("#button_print_multiple_kartu_stok").click(function(event){
        event.preventDefault();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        print_data_label(''+searchIDs+'');
        console.log(searchIDs);
  });

  $('select[name="kode_bagian"]').change(function () {
      if ($(this).val()) {
          $.getJSON("<?php echo site_url('Templates/References/getRakUnit') ?>/" + $(this).val(), '', function (data) {
              $('#rak option').remove();
              $('<option value="">-Pilih Semua-</option>').appendTo($('#rak'));
              $.each(data, function (i, o) {
                  $('<option value="' + o.value + '">' + o.label + '</option>').appendTo($('#rak'));
              });

          });
      } else {
          $('#rak option').remove()
      }
  });

  function print_data_label(myid){
  
    $.ajax({
      url: 'inventory/master/Inv_master_barang/print_multiple?flag=medis&kode_bagian='+$('#kode_bagian').val()+'',
      type: "post",
      data: {ID:myid, flag: 'medis' },
      dataType: "json",
      beforeSend: function() {
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        // response
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        PopupCenter('inventory/master/Inv_master_barang//print_multiple_preview?'+jsonResponse.queryString+'&tipe=kartu_stok&kode_bagian='+$('#kode_bagian').val()+'', 'PRINT PREVIEW', 1000, 550);
      }

    });
    
  }

  function setStatusAktifBrg(kode_brg, kode_bag){
    var val_id = $('#stat_on_off_'+kode_brg+'_'+kode_brg+'').val();

    $.ajax({
        url: "inventory/stok/Inv_stok_depo/set_status_brg",
        data: {kode_bagian : kode_bag, kode_brg : kode_brg, value : val_id },
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            /*reload table*/
            $('#stat_on_off_'+kode_brg+'_'+kode_brg+'').val(jsonResponse.status_aktif);
            if(jsonResponse.status_aktif == 1){
              $('#status_aktif_'+kode_brg+'_'+kode_bag+'').html('<span class="label label-sm label-success">Active</span>');
            }else{
              $('#status_aktif_'+kode_brg+'_'+kode_bag+'').html('<span class="label label-sm label-danger">Not Active</span>');
            }
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
          } 
          achtungHideLoader();
        }
    });
  }

  function updateStokMinimum(kode_depo_stok, kode_bag){
    var val_stok_min = $('#stok_min_val_'+kode_depo_stok+'').val();

    $.ajax({
        url: "inventory/stok/Inv_stok_depo/udpate_stok_minimum",
        data: {kode : kode_depo_stok, value : val_stok_min, kode_bagian : kode_bag },
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            /*reload table*/
            if( parseInt(jsonResponse.stok_min) >= parseInt($('#stok_akhir_val_'+kode_depo_stok+'').val()) ){
              $('#stok_akhir_div_'+kode_depo_stok+'').html('<span style="color: red; font-size: 20px; font-weight: bold">'+$('#stok_akhir_val_'+kode_depo_stok+'').val()+'</span>');
            }else{
              $('#stok_akhir_div_'+kode_depo_stok+'').html('<span style="color: green; font-size: 20px; font-weight: bold">'+$('#stok_akhir_val_'+kode_depo_stok+'').val()+'</span>');

            }
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
          } 
          achtungHideLoader();
        }
    });
  }

  function updateStokMaksimum(kode_depo_stok, kode_bag){
    var val_stok_max = $('#stok_max_val_'+kode_depo_stok+'').val();

    $.ajax({
        url: "inventory/stok/Inv_stok_depo/udpate_stok_maksimum",
        data: {kode : kode_depo_stok, value : val_stok_max, kode_bagian : kode_bag },
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
          var data=xhr.responseText;  
          var jsonResponse = JSON.parse(data);  
          if(jsonResponse.status === 200){  
            $.achtung({message: jsonResponse.message, timeout:5}); 
            /*reload table*/
          }else{          
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
          } 
          achtungHideLoader();
        }
    });
  }


  function checkAll(elm) {

    if($(elm).prop("checked") == true){
      $('.ace').each(function(){
          $(this).prop("checked", true);
      });
    }else{
      $('.ace').prop("checked", false);
    }

  }

  function click_detail(kode_brg){
    getMenu('inventory/stok/Inv_stok_depo/detail/'+kode_brg+'/'+$('#kode_bagian').val()+'');
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

    <form class="form-horizontal" method="post" id="form_search" action="inventory/stok/Inv_stok_depo/find_data" autocomplete="off">

        <center>
            <h4><?php echo strtoupper($title)?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah stok/depo sampai dengan tanggal hari ini <?php echo date('d/M/Y')?> </small></h4>
        </center>
      
        <br>

        <div class="form-group">
          <label class="control-label col-md-2">Pilih Depo/Unit</label>
          <div class="col-md-3">
            <?php 
              echo $this->master->get_depo_aktif($params = array('table' => 'mt_depo_stok', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('depo_group != ' => NULL)), '060101' , 'kode_bagian', 'kode_bagian', 'form-control', '', '') ?>
          </div>

          <label class="control-label col-md-1">Pabrikan</label>
          <div class="col-md-3">
            <?php 
              echo $this->master->custom_selection($params = array('table' => 'mt_pabrik', 'id' => 'id_pabrik', 'name' => 'nama_pabrik', 'where' => array()), '' , 'id_pabrik', 'id_pabrik', 'form-control', '', '') ?>
          </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Layanan</label>
            <div class="col-md-2">
            <?php 
                echo $this->master->custom_selection($params = array('table' => 'mt_layanan_obat', 'id' => 'kode_layanan', 'name' => 'nama_layanan', 'where' => array()), isset($value->kode_layanan)?$value->kode_layanan:'' , 'kode_layanan', 'kode_layanan', 'form-control', '', '') ?>
            </div>
            <label class="control-label col-md-1">PRB</label>
            <div class="col-md-1">
                <select name="prb" id="prb" class="form-control" >
                  <option value="">-</option>
                  <option value="Y">Ya</option>
                  <option value="N">Tidak</option>
                </select>
            </div>

            <label class="control-label col-md-1">Kronis</label>
            <div class="col-md-1">
                <select name="kronis" id="kronis" class="form-control">
                  <option value="">-</option>
                  <option value="Y">Ya</option>
                  <option value="N">Tidak</option>
                </select>
            </div>

            <label class="control-label col-md-1">Status</label>
            <div class="col-md-2">
                <select name="status" id="status" class="form-control">
                  <option value="">-</option>
                  <option value="1">Aktif</option>
                  <option value="0">Tidak Aktif</option>
                </select>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Tanggal Terakhir Stok</label>
            <div class="col-md-2">
            <div class="input-group">
                <input class="form-control date-picker" name="tgl" id="tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <label class="control-label col-md-1">Rak</label>
            <div class="col-md-2">
              <?php 
                  $flag_data = 'rak_medis';
                  echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => $flag_data, 'is_active' => 'Y', 'reff_id' => '060101')), '' , 'rak', 'rak', 'form-control', '', '') 
              ?>
            </div>
            <div class="col-md-4">
              <div class="checkbox" style="margin-top: -5px">
                    <label>
                      <input name="min_stok" id="min_stok" type="checkbox" class="ace" value="1" />
                      <span class="lbl"> Tampilkan < dari Stok Minimum</span>
                    </label>
                    <label>
              </div>
            </div>
        </div>
        

        <div class="form-group">
            <div class="col-md-4" style="margin-left: -1%">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Search
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reset
              </a>
            </div>
        </div>
        <hr>
        
        <div class="clearfix" style="margin-bottom:-5px">
          <?php echo $this->authuser->show_button('inventory/stok/Inv_stok_depo','C','',1)?>
          <?php echo $this->authuser->show_button('inventory/stok/Inv_stok_depo','D','',5)?>
          <a href="#" class="btn btn-xs btn-success" onclick="export_excel()" id="btn_export_excel"><i class="fa fa-file-excel-o"></i> Export Excel</a>
          <a href="" class="btn btn-xs btn-inverse" id="button_print_multiple_kartu_stok"><i class="fa fa-print"></i> Label Kartu Stok</a>
          <div class="pull-right tableTools-container"></div>
        </div>
        <hr class="separator">
        <!-- div.table-responsive -->
        
        <!-- div.dataTables_borderWrap -->
        <div style="margin-top:-27px">
          <table id="dynamic-table" base-url="inventory/stok/Inv_stok_depo" data-id="flag=medis" class="table table-striped table-bordered table-hover">
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
              <th width="30px">No</th>
              <th width="100px">Image</th>
              <th>Kode & Nama Barang</th>
              <th>Rasio</th>
              <th>Stok Min/Max</th>
              <th>Stok Akhir</th>
              <th>Satuan</th>
              <!-- <th>Harga Beli</th> -->
              <th>Mutasi Terakhir/ Expired</th>
              <th>Status</th>
              <th></th>
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



