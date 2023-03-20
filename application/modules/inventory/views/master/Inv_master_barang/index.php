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

  $( "#keyword_form" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          $('#btn_search_data').click();    
        }         
        return false;                
      }       
  });

  $('select[name="kode_golongan"]').change(function () {  
    /*flag string*/
    flag_string = $('#flag_string').val();
    if ( $(this).val() ) {     
      
        $.getJSON("<?php echo site_url('Templates/References/getSubGolongan') ?>/" + $(this).val() + '?flag=' +flag_string, '', function (data) {   
            $('#kode_sub_gol option').remove();         
            $('<option value="">-Pilih Sub Golongan-</option>').appendTo($('#kode_sub_gol'));  
            $.each(data, function (i, o) {   
                $('<option value="' + o.kode_sub_gol + '">' + o.nama_sub_golongan.toUpperCase() + '</option>').appendTo($('#kode_sub_gol'));  
            });   
        });   
    } else {    
        $('#kode_sub_gol option').remove();
    }    
});

  $("#button_print_multiple_kartu_stok").click(function(event){
        event.preventDefault();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        print_data_label(''+searchIDs+'');
        console.log(searchIDs);
  });
  
  function print_data_label(myid){
  
    $.ajax({
      url: base_url+'/print_multiple?'+params,
      type: "post",
      data: {ID:myid, flag: params},
      dataType: "json",
      beforeSend: function() {
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        // response
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        PopupCenter(''+base_url+'/print_multiple_preview?'+jsonResponse.queryString+'&tipe=kartu_stok', 'PRINT PREVIEW', 1000, 550);
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
  
  function print_label(myid){
  
    $.ajax({
      url: base_url+'/print_multiple?'+params,
      type: "post",
      data: {ID:myid, flag: params},
      dataType: "json",
      beforeSend: function() {
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        // response
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        PopupCenter(''+base_url+'/print_multiple_preview?'+jsonResponse.queryString+'', 'PRINT PREVIEW', 1000, 550);
      }

    });
    
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

    <form class="form-horizontal" method="post" id="form_search" action="pelayanan/Pl_pelayanan_ri/find_data">

        <center>
            <h4>MASTER DATA <?php echo strtoupper($title)?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Master Data <?php echo $title?> aktif </small></h4>
        </center>
      
        <br>
        <!-- hidden form -->
        <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag_string;?>">

        <div class="form-group">
          <label class="control-label col-md-2">Golongan</label>
          <div class="col-md-3">
              <?php 
                $table_gol = ( $flag_string == 'medis' ) ? 'mt_golongan' : 'mt_golongan_nm' ;
                echo $this->master->custom_selection($params = array('table' => $table_gol, 'id' => 'kode_golongan', 'name' => 'nama_golongan', 'where' => array()), '' , 'kode_golongan', 'kode_golongan', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-2">Sub Golongan</label>
          <div class="col-md-3" id="dokter_by_klinik">
              <?php 
                $table_sub_gol = ( $flag_string == 'medis' ) ? 'mt_sub_golongan' : 'mt_sub_golongan_nm' ;
                echo $this->master->custom_selection($params = array('table' => $table_sub_gol, 'id' => 'kode_sub_gol', 'name' => 'nama_sub_golongan', 'where' => array()), '' , 'kode_sub_gol', 'kode_sub_gol', 'form-control', '', '') ?>
          </div>
        </div>
        <?php if( $flag_string == 'medis' ) : ?>
          <div class="form-group">
            <label class="control-label col-md-2">Kategori</label>
            <div class="col-md-2">
                <?php 
                  echo $this->master->custom_selection($params = array('table' => 'mt_kategori', 'id' => 'kode_kategori', 'name' => 'nama_kategori', 'where' => array()), '' , 'kode_kategori', 'kode_kategori', 'form-control', '', '') ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2">Rak/Lemari</label>
            <div class="col-md-3">
                <?php 
                  $flag_label = ( $flag_string == 'medis' ) ? 'rak_medis' : 'rak_non_medis' ;
                  echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => $flag_label)), '' , 'rak', 'rak', 'form-control', '', '') ?>
            </div>

            <label class="control-label col-md-1">PRB</label>
            <div class="col-md-1">
                <select name="prb" id="prb" class="form-control" >
                  <option value="">- Semua -</option>
                  <option value="Y">Ya</option>
                  <option value="N">Tidak</option>
                </select>
            </div>

            <label class="control-label col-md-1">Kronis</label>
            <div class="col-md-1">
                <select name="kronis" id="kronis" class="form-control">
                  <option value="">- Semua -</option>
                  <option value="Y">Ya</option>
                  <option value="N">Tidak</option>
                </select>
            </div>
          </div>


        <?php endif; ?>
        <div class="form-group">
          <label class="control-label col-md-2">Status Aktif</label>
            <div class="col-md-2">
                <select name="is_active" id="is_active">
                  <option value="" selected>- Silahkan Pilih -</option>
                  <option value="1" selected>AKTIF</option>
                  <option value="0">NON AKTIF</option>
                </select>
            </div>
            <div class="col-md-8" style="margin-left: -3%">
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

        <div class="clearfix" style="margin-bottom:-5px">
          <?php echo $this->authuser->show_button('inventory/master/Inv_master_barang?flag='.$flag_string.'','C','',7)?>
          <?php echo $this->authuser->show_button('inventory/master/Inv_master_barang?flag='.$flag_string.'','D','',5)?>
          <a href="" class="btn btn-xs btn-inverse" id="button_print_multiple"><i class="fa fa-print"></i> Label Barcode</a>

          <a href="" class="btn btn-xs btn-inverse" id="button_print_multiple_kartu_stok"><i class="fa fa-print"></i> Label Kartu Stok</a>

        </div>

        <hr class="separator">

        <div style="margin-top:-27px">

          <table id="dynamic-table" base-url="inventory/master/Inv_master_barang" data-id="flag=<?php echo $flag_string?>" url-detail="inventory/master/Inv_master_barang/show_detail" class="table table-bordered table-hover">
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
                <th width="150px">Kode & Nama Barang</th>
                <th>Gol & Sub Golongan</th>
                <th>Satuan<br>Besar/Kecil</th>
                <th>Rasio</th>
                <th>Harga Beli</th>
                <th width="180px">Spesifikasi</th>
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


<script src="<?php echo base_url().'assets/js/custom/als_datatable_with_detail_custom_url.js'?>"></script>



