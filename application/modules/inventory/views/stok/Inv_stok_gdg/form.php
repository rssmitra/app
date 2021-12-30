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

$(document).ready(function(){
  
    $('#form-add-stok-depo').ajaxForm({
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
          $('#page-area-content').load('inventory/stok/Inv_stok_depo?_=' + (new Date()).getTime());
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $('#btn_search_brg').click(function (e) {   

        if ( $('#inputKeyWord').val()=='' ) {
          alert('Silahkan Masukan Kata Kunci !'); return false;
        }

        if ( $('#kode_bagian').val()=='' ) {
          alert('Silahkan pilih Bagian/Unit !'); $('#kode_bagian').focus(); return false;
        }

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

    $( "#inputKeyWord" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){    
            $('#btn_search_brg').click();
          }         
          return false;                
        }       
    });
    
    $( "#kode_bagian" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode == 13){
          event.preventDefault();         
          $( "#inputKeyWord" ).focus();
          return false;                
        }       
    }); 
    
  
})

function search_selected_brg(flag, search_by, keyword){

  $('#show_detail_selected_brg').html('');
  $('#find_result_barang').html('');

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrgDepo', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by, kode_bagian: $('#kode_bagian').val() }, //Forms name
      dataType  : 'json',
      success   : function(data) {
        
        $('#show_detail_selected_brg').html(data.html);

      }
  })

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

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->

    <form class="form-horizontal" method="post" id="form-add-stok-depo" action="<?php echo site_url('inventory/stok/Inv_stok_depo/process')?>" enctype="multipart/form-data">
      <br>
        <!-- input hiddep -->
        <input type="hidden" name="flag_string" id="flag_string" value="medis">

        <div class="form-group">
          <label class="control-label col-md-2">Bagian Unit</label>
          <div class="col-md-4">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '' , 'kode_bagian', 'kode_bagian', 'form-control', '', ($flag=='read')?'readonly':'') ?>
          </div>
        </div>

        <hr class="separator">

        <div id="pencarian_brg_div">
          <b>PENCARIAN DATA BARANG</b><br>
          <div class="form-group">
            <label class="control-label col-md-2">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" id="search_by" class="form-control">
                <option value="">-Silahkan Pilih-</option>
                <option value="a.nama_brg" selected>Nama Barang</option>
                <option value="a.kode_brg">Kode Barang</option>
              </select>
            </div>

            <label class="control-label col-md-1">Kata Kunci</label>
            <div class="col-md-3">
              <input id="inputKeyWord" class="form-control" name="kata_kunci" type="text" placeholder="Masukan keyword minimal 3 karakter" />
            </div>

            <div class="col-md-2" style="margin-left:-1%">
              <a href="#" id="btn_search_brg" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Cari Barang
              </a>
            </div>
          </div>
          <hr>
          <div id="show_detail_selected_brg"></div>
        </div>
               
        <div class="form-actions center">
          <a onclick="getMenu('inventory/stok/Inv_stok_depo')" href="#" class="btn btn-sm btn-success">
            <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
            Kembali ke daftar
          </a>
          <?php if($flag != 'read'):?>
          <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
            <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
            Reset
          </button>
          <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Submit
          </button>
          <?php endif; ?>
        </div>

    </form>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


