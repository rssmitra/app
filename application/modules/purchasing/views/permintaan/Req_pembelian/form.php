<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){

    // load template
    load_template();

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
          $('#page-area-content').load('purchasing/permintaan/Req_pembelian/form/'+jsonResponse.id+'?flag='+jsonResponse.flag+'');

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

  $.ajax({
      type      : 'POST',
      url       : 'Templates/References/getRefBrg',
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by},
      dataType  : 'json',
      success   : function(data) {
        $('#show_detail_selected_brg').html(data.html);
      }
  })

}

function load_request_form(){
  preventDefault();
  $('#show_detail_selected_brg').load('purchasing/permintaan/Req_pembelian/load_request_form/'+$('#id_tc_permohonan').val()+'?flag='+$('#flag_string').val()+'');
}

function load_template(){
  $('#load_template').load('purchasing/permintaan/Req_pembelian/load_template?flag='+$('#flag_string').val()+'');
}

function loadDataFromTemplate(temp_name){
  $('#show_detail_selected_brg').load('purchasing/permintaan/Req_pembelian/load_data_from_template?name='+temp_name+'&flag='+$('#flag_string').val()+'');
}

function sum_ttl_permintaan(kode_brg, satuan){
  var input = $('#input_'+kode_brg).val() ;
  var rasio = $('#input_rasio_'+kode_brg).val() ;
  var ttl_konversi = input * rasio;
  $('#konversi_'+kode_brg+'').text( ttl_konversi+' '+ satuan);
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
  $('#show_detail_selected_brg').load('purchasing/permintaan/Req_pembelian/get_detail_permintaan_brg/'+$('#id_tc_permohonan').val()+'?flag='+$('#flag_string').val()+'');
}

function hide_this_div( div ){
  preventDefault();
  $('#'+div+'').html('');
}

function proses_persetujuan(id){
    preventDefault();

    if( confirm('Apakah anda yakin?') ){
      $.ajax({
        url: 'purchasing/permintaan/Req_pembelian/process_persetujuan?flag=<?php echo $string?>',
        type: "post",
        data: {ID: id},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();
        },
        success: function(data) {
          achtungHideLoader();
          preventDefault();
          getMenu('purchasing/permintaan/Req_pembelian?flag=<?php echo $string?>')
        }
      });
    }else{
      return false;
    }

  }

  if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true});

        $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
          $('.chosen-select').each(function() {
              var $this = $(this);
              $this.next().css({'width': $this.parent().width()});
          })
        }).trigger('resize.chosen');

  }

</script>

<style>
  .frm-card { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin-bottom: 12px; }
  .frm-card-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .frm-card-hdr small { font-weight: 400; opacity: .85; }
  .frm-card-body { padding: 16px 14px; background: #fff; }
  .frm-section-title { font-size: 12px; font-weight: 700; color: #1a4f8a; margin: 10px 0 6px; padding-bottom: 4px; border-bottom: 1px solid #d0dce8; }
  .frm-info-tbl { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 10px; }
  .frm-info-tbl thead tr { background: #2c6fad; color: #fff; }
  .frm-info-tbl thead td { padding: 7px 10px; font-weight: 600; border: 1px solid #1e5590; }
  .frm-info-tbl tbody td { padding: 6px 10px; border: 1px solid #d0dce8; }
</style>

<div class="frm-card">
  <div class="frm-card-hdr">
    <i class="fa fa-shopping-cart"></i> <?php echo $title?>
    <small><i class="fa fa-angle-double-right"></i> <?php echo $breadcrumbs?></small>
  </div>
  <div class="frm-card-body">

    <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process?flag='.$string.'')?>" enctype="multipart/form-data">

      <input type="hidden" name="flag" id="flag_string" value="<?php echo $string?>">
      <input type="hidden" name="id_tc_permohonan" id="id_tc_permohonan" value="<?php echo isset($value)?$value->id_tc_permohonan:''; ?>">

      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 6px;">
        <a onclick="getMenu('purchasing/permintaan/Req_pembelian?flag=<?php echo $string?>')" href="#" class="btn btn-sm btn-success">
          <i class="fa fa-arrow-left"></i> Kembali ke daftar
        </a>
        <?php if( isset($value) AND !empty($value->id_tc_permohonan) ) : ?>
        <div>
          <?php
            if( $this->session->userdata('user')->user_id != 1){
              $kainst = ($_GET['flag'] == 'non_medis') ? $this->master->get_ttd_data('ttd_ka_gdg_nm', 'reff_id') : $this->master->get_ttd_data('ttd_ka_gdg_m', 'reff_id');
              if ($kainst == $this->session->userdata('user')->user_id) {
                  echo '<a onclick="proses_persetujuan('.$value->id_tc_permohonan.')" href="#" class="btn btn-sm btn-primary">
                  <i class="fa fa-send"></i> Simpan dan Kirim ke Pengadaan
                  </a>';
              }
            }else{
              echo '<a onclick="proses_persetujuan('.$value->id_tc_permohonan.')" href="#" class="btn btn-sm btn-primary">
              <i class="fa fa-send"></i> Simpan dan Kirim ke Pengadaan
              </a>';
            }
          ?>
        </div>
        <?php endif; ?>
      </div>

      <div id="div_table" <?php echo isset( $value ) ? '' : 'style="display:none"' ?> >
        <table class="frm-info-tbl">
          <thead>
            <tr>
              <td>ID</td>
              <td>Kode Permintaan</td>
              <td>Tanggal</td>
              <td>Unit/Bagian</td>
              <td>Jenis Permintaan</td>
              <td>Total Barang</td>
              <td>Keterangan</td>
              <td>&nbsp;</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo isset($value)?$value->id_tc_permohonan:''; ?></td>
              <td><?php echo isset($value)?$value->kode_permohonan:''; ?></td>
              <td><?php echo isset($value)?$this->tanggal->formatDate($value->tgl_permohonan):''; ?></td>
              <td><?php echo isset($value)?$value->nama_bagian:''; ?></td>
              <td><?php echo isset($value)?$value->jenis_permohonan_name:''; ?></td>
              <td class="center"><a href="#" onclick="get_detail_permintaan_brg()"><span class="badge badge-primary" id="total_brg"><?php echo isset($total_brg) ? count($total_brg) : 0?></span></a></td>
              <td><?php echo isset($value)?$value->keterangan_permohonan:''; ?></td>
              <td><a href="#" onclick="click_edit(<?php echo isset($value)?$value->id_tc_permohonan:''; ?>)" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Edit</a></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div id="div_daftar_permintaan_brg" style="display:none"></div>

      <div id="form_div" <?php echo isset( $value ) ? 'style="display:none"' : '' ?>>

        <div class="form-group">
          <label class="control-label col-md-2">ID</label>
          <div class="col-md-1">
            <input name="id" id="id" value="<?php echo isset($value)?$value->id_tc_permohonan:''?>" placeholder="Auto" class="form-control" type="text" readonly>
          </div>
          <label class="control-label col-md-2">Kode Permohonan</label>
          <div class="col-md-2">
            <input name="kode_permohonan" id="kode_permohonan" value="<?php echo isset($value)?$value->kode_permohonan:$kode_permohonan; ?>" placeholder="Auto" class="form-control" type="text" readonly>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Bagian/Unit</label>
          <div class="col-md-5">
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '' , 'kode_bagian_pemohon', 'kode_bagian_pemohon', 'chosen-select form-control', '', '') ?>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Permintaan</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="tgl_permohonan" id="tgl_permohonan" type="text" data-date-format="yyyy-mm-dd" <?php echo ($flag=='read')?'readonly':''?> value="<?php echo isset($value)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_permohonan):date('Y-m-d')?>"/>
              <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
            </div>
          </div>

          <label class="control-label col-md-2">Jenis Permintaan</label>
          <div class="col-md-4">
            <div class="radio">
              <label>
                <input name="flag_jenis" type="radio" class="ace" value="2" <?php echo isset($value) ? ($value->flag_jenis == '2') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                <span class="lbl"> Rutin</span>
              </label>
              <label>
                <input name="flag_jenis" type="radio" class="ace" value="3" <?php echo isset($value) ? ($value->flag_jenis == '3') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                <span class="lbl"> Non Rutin</span>
              </label>
              <label>
                <input name="flag_jenis" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->flag_jenis == '1') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                <span class="lbl"> Cito</span>
              </label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Keterangan</label>
          <div class="col-md-5">
            <textarea name="ket_acc" style="height: 50px !important; width: 300px" <?php echo ($flag=='read')?'readonly':''?>><?php echo isset($value)?$value->keterangan_permohonan:''?></textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">&nbsp;</label>
          <div class="col-md-5">
            <button type="submit" id="btnSave" name="submit" value="header" class="btn btn-sm btn-info">
              <i class="fa fa-save"></i> Simpan
            </button>
          </div>
        </div>

      </div>

      <hr class="separator">

      <!-- load template -->
      <div id="load_template" style="margin-bottom: 10px"></div>

      <div id="pencarian_brg_div" <?php echo isset( $value ) ? '' : 'style="display:none"' ?>>
        <div class="frm-section-title"><i class="fa fa-search"></i> Pencarian Data Barang</div>
        <div class="form-group">
          <label class="control-label col-md-2">Pencarian berdasarkan</label>
          <div class="col-md-2">
            <select name="search_by" id="search_by" class="form-control">
              <option value="a.nama_brg">Nama Barang</option>
              <option value="a.kode_brg">Kode Barang</option>
            </select>
          </div>
          <label class="control-label col-md-1">Kata Kunci</label>
          <div class="col-md-3">
            <input id="inputKeyWord" class="form-control" name="kata_kunci" type="text" placeholder="Masukan keyword minimal 3 karakter" />
          </div>
          <div class="col-md-4">
            <a href="#" id="btn_search_brg" class="btn btn-sm btn-primary">
              <i class="fa fa-search"></i> Cari Barang
            </a>
            <a href="#" id="btn_search_brg_other" onclick="load_request_form()" class="btn btn-sm btn-primary">
              <i class="fa fa-plus"></i> Tambah Barang Lainnya
            </a>
          </div>
        </div>
        <hr>
        <div id="show_detail_selected_brg"></div>
      </div>

    </form>
  </div>
</div>
