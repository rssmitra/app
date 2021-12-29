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

$(document).ready(function(){
  
    $('#form_permintaan').ajaxForm({
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
          
          $('#form_div').hide();
          $('#div_table').show();
          $('#page-area-content').load('purchasing/pendistribusian/Distribusi_permintaan/form/'+jsonResponse.id+'?flag='+jsonResponse.flag+'');

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

        search_selected_brg(flag, search_by, keyword);

        e.preventDefault();

    });

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

function search_selected_brg(flag, search_by, keyword){

  $('#show_detail_selected_brg').html('');
  $('#find_result_barang').html('');

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrgPermintaanUnit', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by}, //Forms name
      dataType  : 'json',
      success   : function(data) {
        
        $('#show_detail_selected_brg').html(data.html);

        // if( data.total_item > 1 ){
        //   $('#find_result_barang').html(data.html);
        //   showModal(); 
        // }
      }
  })

}

function sum_ttl_permintaan(kode_brg, satuan){
  var input = $('#input_'+kode_brg).val() ;
  var rasio = $('#input_rasio_'+kode_brg).val() ;
  var ttl_konversi = input / rasio;
  var modulus = input % rasio;
  var satuan_kecil = $('#select_satuan_'+kode_brg).val() ;;
  var txtModulus = (modulus == 0) ? '' : modulus+' '+satuan_kecil ;
  $('#konversi_'+kode_brg+'').text( ttl_konversi.toFixed()+' '+ satuan + ' + ' +txtModulus);
}

function showModal(){  
  $("#result_text").text('Result for keyword "'+$('#inputKeyWord').val()+'"');  
  $("#modal_search_barang").modal();  
}

function click_edit(){
    $('#form_div').show();
    $('#div_table').hide();
    $('#div_daftar_permintaan_brg').hide();
}

function get_detail_permintaan_brg(){
  preventDefault();
  $('#div_daftar_permintaan_brg').show();
  $('#div_daftar_permintaan_brg').load('purchasing/pendistribusian/Distribusi_permintaan/get_detail_permintaan_brg/'+$('#id_tc_permintaan_inst').val()+'?flag='+$('#flag_string').val()+'');
}

function hide_this_div( div ){
  preventDefault();
  $('#'+div+'').hide();
}

</script>
<style type="text/css">
  .dropdown-item{
    height : 100px;
    width: 300px;
  }
</style>
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
          <div class="widget-body">
            <div class="widget-main no-padding">

              <a onclick="getMenu('purchasing/pendistribusian/Distribusi_permintaan?flag=<?php echo $string?>')" href="#" class="btn btn-sm btn-success" style="margin-left: -0px">
                  <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                  Kembali ke daftar
              </a>

              <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/pendistribusian/Distribusi_permintaan/process')?>" enctype="multipart/form-data" style="margin-top: -10px" autocomplete="off">
                <br>
                <!-- input form hidden -->
                <input type="hidden" name="flag" id="flag_string" value="<?php echo $string?>">
                <input type="hidden" name="id_tc_permintaan_inst" id="id_tc_permintaan_inst" value="<?php echo isset($value)?$value->id_tc_permintaan_inst:''; ?>">
                
                <div id="div_table" <?php echo isset( $value ) ? '' : 'style="display:none"' ?> >
                  <table class="table">
                    <thead>
                      <tr style="background-color: #428bca; color: white">
                        <td>ID</td>
                        <td>Bagian/Unit</td>
                        <td>Nomor Permintaan</td>
                        <td>Tanggal</td>
                        <td>Jenis Permintaan</td>
                        <td>Total Barang</td>
                        <td>Catatan</td>
                        <td>&nbsp;</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo isset($value)?$value->id_tc_permintaan_inst:''; ?></td>
                        <td><strong><?php echo isset($value)?$value->bagian_minta:''; ?></strong></td>
                        <td><?php echo isset($value)?$value->nomor_permintaan:''; ?></td>
                        <td><?php echo isset($value)?$this->tanggal->formatDate($value->tgl_permintaan):''; ?></td>
                        <td><?php echo isset($value)?$value->jenis_permintaan:''; ?></td>
                        <td align="center"><a href="#" onclick="get_detail_permintaan_brg()"><span class="badge badge-primary" id="total_brg"><?php echo isset($total_brg) ? count($total_brg) : 0?></span></a></td>
                        <td><?php echo isset($value)?$value->catatan:''; ?></td>
                        <td><a href="#" onclick="click_edit(<?php echo isset($value)?$value->id_tc_permintaan_inst:''; ?>)" class="btn btn-xs btn-small btn-success">Edit</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div id="div_daftar_permintaan_brg" style="display:none" ></div>

                <div id="form_div" <?php echo isset( $value ) ? 'style="display:none"' : '' ?> >

                  <div class="form-group">
                    <label class="control-label col-md-2">ID</label>
                    <div class="col-md-1">
                      <input name="id" id="id" value="<?php echo isset($value)?$value->id_tc_permintaan_inst:''?>" placeholder="Auto" class="form-control" type="text" readonly>
                    </div>
                    <label class="control-label col-md-2">Nomor Permintaan</label>
                    <div class="col-md-2">
                      <input name="nomor_permintaan" id="nomor_permintaan" value="<?php echo isset($value)?$value->nomor_permintaan:$nomor_permintaan; ?>" placeholder="Auto" class="form-control" type="text" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-2">Bagian/Unit</label>
                      <div class="col-md-5">
                      <?php 
                          echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), isset($value)?$value->kode_bagian_minta:'' , 'kode_bagian_minta', 'kode_bagian_minta', 'form-control', '', '') ?>
                      </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">Tanggal Permintaan</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control date-picker" name="tgl_permintaan" id="tgl_permintaan" type="text" data-date-format="yyyy-mm-dd" <?php echo ($flag=='read')?'readonly':''?> value="<?php echo isset($value)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_permintaan):date('Y-m-d')?>"/>
                        <span class="input-group-addon">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    </div>

                    <label class="control-label col-md-2">Jenis Permintaan</label>
                    <div class="col-md-2">
                      <div class="radio">
                        <label>
                          <input name="jenis_permintaan" type="radio" class="ace" value="2" <?php echo isset($value) ? ($value->jenis_permintaan == '2') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                          <span class="lbl"> Rutin</span>
                        </label>
                        <label>
                          <input name="jenis_permintaan" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->jenis_permintaan == '1') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                          <span class="lbl"> Cito</span>
                        </label>
                      </div>
                    </div>

                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">Keterangan</label>
                    <div class="col-md-5">
                      <textarea name="catatan" style="height: 50px !important; width: 300px" <?php echo ($flag=='read')?'readonly':''?>><?php echo isset($value)?$value->catatan:''?></textarea>
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

                </div>

                <hr class="separator">

                <div id="pencarian_brg_div" <?php echo isset( $value ) ? '' : 'style="display:none"' ?>>
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
              <br>

              <!-- <div class="form-actions center">
                
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
              </div> -->

            </form>
          </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->

<!-- MODAL SEARCH PASIEN -->

<div id="modal_search_barang" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">

    <div class="modal-content">

      <div class="modal-header no-padding">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text">Results for ""</span>

        </div>

      </div>

      <div class="modal-body no-padding">

        <div id="find_result_barang">

      </div>

      <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>


