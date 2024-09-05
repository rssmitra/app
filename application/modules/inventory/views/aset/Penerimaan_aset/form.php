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

    // profile supplier
    get_profile_supplier($('#supplier_id_hidden').val());

    // get barang po
    get_barang_po();

    $('#form_penerimaan_brg').ajaxForm({
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
          
          if (jsonResponse.action=='header') {
            $('#section_form_penerimaan_barang').hide();
            $('#section_table_penerimaan_brg').show();
            // show result form
            show_penerimaan_brg_dt(jsonResponse.id);
          }else{
            var redirect = "purchasing/penerimaan/Penerimaan_brg/preview_penerimaan?ID="+jsonResponse.id+"&flag="+jsonResponse.flag+"";
            $('#page-area-content').load(redirect);
          }
          

          // id penerimaan brg
          $('#id').val(jsonResponse.id);
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

function show_penerimaan_brg_dt(id){
  $('#section_table_penerimaan_brg').load('purchasing/penerimaan/Penerimaan_brg/show_penerimaan_brg/'+id+'?flag='+$('#flag_string').val()+'');
  // drop attr disabled
  $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', false);
}

function get_profile_supplier(id){
  $.getJSON("<?php echo site_url('Templates/References/getSupplierById') ?>/" + id, '', function (response) {
      // detail supplier
      $('#dikirim').val(response.namasupplier);
      $('#detail_supplier').html('<strong><span style="font-size: 14px">'+response.namasupplier+'</span></strong><br>'+response.alamat+'<br>No. Telp : '+response.telpon1+'');
  });
}

function get_barang_po(){
  $('#section_barang_po').load('purchasing/penerimaan/Penerimaan_brg/get_barang_po_penerimaan?id='+$('#id_tc_po').val()+'&flag='+$('#flag_string').val()+'');

  if( $('#id').val() == '' ){
    $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', true);
  }else{
    $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', false);
  }
  
}

function search_selected_brg(flag, search_by, keyword){

  $.ajax({ //Process the form using $.ajax()
      type      : 'POST', //Method type
      url       : 'Templates/References/getRefBrg', //Your form processing file URL
      data      : {keyword: $('#inputKeyWord').val(), flag: flag, search_by: search_by}, //Forms name
      dataType  : 'json',
      success   : function(data) {
          $('#show_detail_selected_brg').html(data.html);
      }
  })

}

function updatePenerimaan(){
  preventDefault();
  $('#table_brg_penerimaan input[type=checkbox]').prop('checked', false);
  $('#table_brg_penerimaan input[type=checkbox]').attr('disabled', true);
  $('#section_form_penerimaan_barang').show();
  $('#section_table_penerimaan_brg').hide();
}

</script>
<div class="page-header">
  <h1>
    <?php echo $title?>
  </h1>
</div>

<div class="row">

  <div class="col-xs-3">
    <address><div id="detail_supplier"></div></address>
  </div>
  
  <div class="col-xs-9">
    <table class="table table-bordered table-hovered">
      <tr>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">ID PO</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">Nomor PO</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">Tanggal</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">Estimasi Kirim</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">SIK AA</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">Diajukan Oleh</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">Disetujui Oleh</td>
        <td style="background-color: #ffb752;color: #0a0a0a;font-weight: bold; border: 1px solid #cecaca; border-collapse: collapse">Pembayaran</td>
      </tr>

      <tr>
        <td><?php echo isset($value->id_tc_po)?$value->id_tc_po:''?></td>
        <td><?php echo isset($value->no_po)?$value->no_po:''?></td>
        <td><?php echo isset($value->tgl_po)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_po): date('Y-m-d') ?></td>
        <td><?php echo isset($value->tgl_kirim)?$this->tanggal->formatDateTimeToSqlDate($value->tgl_kirim): date('Y-m-d') ?></td>
        <td><?php echo isset($value->sipa)?$value->sipa:''?></td>
        <td><?php echo isset($value->diajukan_oleh)?$value->diajukan_oleh:''?></td>
        <td><?php echo isset($value->disetujui_oleh)?$value->disetujui_oleh:''?></td>
        <td><?php echo isset($value->term_of_pay)?$value->term_of_pay:''?></td>
      </tr>

    </table>
  </div>

</div>
<hr>
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal" method="post" id="form_penerimaan_brg" action="<?php echo site_url('purchasing/penerimaan/Penerimaan_brg/process')?>" enctype="multipart/form-data" style="margin-top: -10px" autocomplete="off">

            <!-- input form hidden -->
            <input name="supplier_id_hidden" id="supplier_id_hidden" value="<?php echo isset($value->kodesupplier)?$value->kodesupplier:''?>" class="form-control" type="hidden">

            <input name="id_tc_po" id="id_tc_po" value="<?php echo isset($value->id_tc_po)?$value->id_tc_po:''?>" class="form-control" type="hidden">

            <input name="no_po" id="no_po" value="<?php echo isset($value->no_po)?$value->no_po:''?>" class="form-control" type="hidden">

            <input type="hidden" name="flag" id="flag_string" value="<?php echo $flag?>">
            <input type="hidden" name="kode_bagian" id="kode_bagian" value="<?php echo ($flag=='non_medis') ? '070101' : '060201' ; ?>">
            
            <!-- form create penerimaan barang -->
            <div id="section_form_penerimaan_barang" style="<?php if(isset($existing->id_penerimaan)) { echo 'display:none'; } ?>">
              <b>FORM PENERIMAAN BARANG</b>
              <div class="form-group">
                <label class="control-label col-md-2">ID</label>
                <div class="col-md-1">
                  <input name="id" id="id" value="<?php echo isset($existing->id_penerimaan)?$existing->id_penerimaan : ''?>" class="form-control" type="text" placeholder="Auto" readonly>
                </div>
                <label class="control-label col-md-1">Tanggal</label>
                <div class="col-md-2">
                  <div class="input-group" style="width: 150px;">
                    <input class="form-control date-picker" name="tgl_penerimaan" id="tgl_penerimaan" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($existing->tgl_penerimaan)?$this->tanggal->formatDateTimeToSqlDate($existing->tgl_penerimaan) : date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-2">Nomor Penerimaan</label>
                <div class="col-md-2">
                  <input name="kode_penerimaan" id="kode_penerimaan" value="<?php echo isset($existing->kode_penerimaan)?$existing->kode_penerimaan : $format_nomor_penerimaan; ?>" class="form-control" type="text" placeholder="Auto" readonly>
                </div>
                <label class="control-label col-md-2">No Faktur</label>
                <div class="col-md-2">
                  <input name="no_faktur" id="no_faktur" value="<?php echo isset($existing->no_faktur)?$existing->no_faktur : ''; ?>" class="form-control" type="text">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-2">Penerima</label>
                <div class="col-md-2">
                  <input name="petugas" id="petugas" value="<?php echo isset($existing->petugas)?$existing->petugas:$this->session->userdata('user')->fullname?>" class="form-control" type="text">
                </div>
                <label class="control-label col-md-2">Pengirim (a.n)</label>
                <div class="col-md-2">
                  <input name="dikirim" id="dikirim" value="<?php echo isset($existing->pengirim)?$existing->pengirim:''?>" class="form-control" type="text">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-2">Diketahui</label>
                <div class="col-md-2">
                  <input name="diketahui" id="diketahui" value="<?php echo isset($existing->diketahui)?$existing->diketahui: ($flag=='medis') ? $this->master->get_ttd_data('ttd_ka_gdg_m','label') : $this->master->get_ttd_data('ttd_ka_gdg_nm','label') ?>" class="form-control" type="text">
                </div>
                <label class="control-label col-md-2">Disetujui</label>
                <div class="col-md-2">
                  <input name="disetujui" id="disetujui" value="<?php echo isset($existing->disetujui)?$existing->disetujui: ($flag=='medis') ? $this->master->get_ttd_data('ttd_kasubag_pengadaan','label') : $this->master->get_ttd_data('ttd_kasubag_pengadaan','label') ?>" class="form-control" type="text">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-2">Keterangan</label>
                <div class="col-md-5">
                  <textarea class="form-control" style="height:50px !important" name="keterangan"><?php echo isset($existing->keterangan)?$existing->keterangan:'Sesuai PO'?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-2">&nbsp;</label>
                <div class="col-md-5" style="padding-top: 3px; margin-left: 7px">
                <button type="submit" name="submit" value="header" class="btn btn-xs btn-info">
                  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                  Submit
                </button>
                </div>
              </div>
            </div>

            <script stype="text-javascript">
              $(document).ready(function(){
                show_penerimaan_brg_dt(<?php echo $existing->id_penerimaan?>);
              })
            </script>

            <!-- form show result penerimaan barang -->
            <div id="section_table_penerimaan_brg"></div>

            <div id="section_table_brg">
              <div class="pull-right">
                <a onclick="getMenu('purchasing/penerimaan/Penerimaan_brg/view_data?flag=<?php echo $flag?>', 'tabs_form_po')" href="#" class="btn btn-xs btn-success">
                  <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                  Kembali ke daftar
                </a>
                <button type="reset" id="btnReset" class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                  Reset
                </button>
                <button type="submit" name="submit" class="btn btn-xs btn-info" value="penerimaan_selesai">
                  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                  Penerimaan Barang Selesai
                </button>
              </div>

              <div id="section_barang_po"></div>

            </div>
            
          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


