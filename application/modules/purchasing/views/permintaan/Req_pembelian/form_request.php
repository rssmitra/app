<script>
$(document).ready(function(){

    $('#form_permintaan_brg_lainnya').ajaxForm({
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
          load_request_form();
        }else{
          $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
        }
        achtungHideLoader();
      }
    }); 

    var flag = ( $('#flag_string').val() ) ? $('#flag_string').val() : '' ;
    var search_by = $('select[name="search_by"]').val();
    var keyword = $('#inputKeyWord').val();

    $( "#inputKeyWord" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            search_selected_brg(flag, search_by, keyword);       
          }         
          return false;                
        }       
    });  

})

</script>

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <b>BARANG LAINNYA</b><br>
      <form class="form-horizontal" method="post" id="form_permintaan_brg_lainnya" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process_other?flag='.$string.'')?>" enctype="multipart/form-data" style="margin-top: -10px">
        <br>
        <!-- input form hidden -->
        <input type="hidden" name="flag" id="flag_string" value="<?php echo $string?>">
        <input type="hidden" name="id_tc_permohonan" id="id_tc_permohonan" value="<?php echo isset($value)?$value->id_tc_permohonan:''; ?>">
        
        <div class="form-group">
          <label class="control-label col-md-2">Nama Barang</label>
          <div class="col-md-4">
              <input class="form-control" name="nama_brg" id="nama_brg" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Jumlah Usulan</label>
          <div class="col-md-1">
              <input class="form-control" name="qty" id="qty" type="text" value=""/>
          </div>
          <label class="control-label col-md-1">Satuan</label>
          <div class="col-md-1">
              <input class="form-control" name="satuan" id="satuan" type="text" value=""/>
          </div>
          <label class="control-label col-md-1">Est Harga</label>
          <div class="col-md-1">
              <input class="form-control" name="est_harga" id="est_harga" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Spesifikasi Barang</label>
          <div class="col-md-8">
            <textarea name="spesifikasi_brg" class="form-control" style="height: 70px !important;" <?php echo ($flag=='read')?'readonly':''?>></textarea>
          </div>
        </div>
        <br>
        <b>REFERENSI DARI MARKETPLACE</b><br>

        <div class="form-group" style="padding-top: 10px;">
          <label class="control-label col-md-2">Link Shoppe</label>
          <div class="col-md-4">
              <input class="form-control" name="link_shopee" id="link_shopee" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Link Tokopedia</label>
          <div class="col-md-4">
              <input class="form-control" name="link_tokopedia" id="link_tokopedia" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Link Lazada</label>
          <div class="col-md-4">
              <input class="form-control" name="link_lazada" id="link_lazada" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Link Blibli</label>
          <div class="col-md-4">
              <input class="form-control" name="link_blibli" id="link_blibli" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Link Bukalapak</label>
          <div class="col-md-4">
              <input class="form-control" name="link_bukalapak" id="link_bukalapak" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Link Lainnya</label>
          <div class="col-md-4">
              <input class="form-control" name="link_lainnya" id="link_lainnya" type="text" value=""/>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">&nbsp;</label>
          <div class="col-md-5">
            <button type="submit" id="btnSave" name="submit" value="header" class="btn btn-sm btn-info">
              <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
              Simpan
            </button> 
          </div>
        </div>

      </form>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->



