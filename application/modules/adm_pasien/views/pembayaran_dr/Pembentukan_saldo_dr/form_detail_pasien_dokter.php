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
          $('#page-area-content').load('purchasing/permintaan/Req_pembelian?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
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
</script>
<style type="text/css">
    .user-info{
    max-width: 200px !important;
  }
  

</style>

<?php 
  $arr_total_bill = array(); 
  foreach($detail as $row_detail_dt) {
    $arr_total_bill[] = $row_detail_dt->billing;
  }
?>

<div class="row">
  <div class="page-header">  
      <ul class="nav ace-nav">

        <li class="light-blue" style="background-color: #f3ac41  !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: #f3ac41 !important; color: black">
            <span class="user-info">
              <b><?php echo isset($profil_dokter->nama_pegawai)?$profil_dokter->nama_pegawai:''?></b>
              <small><?php echo isset($profil_dokter->nama_bagian)?ucwords($profil_dokter->nama_bagian):''?></small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><span style="font-size: 12px;"> <?php echo $this->tanggal->formatDatedmY($from_tgl).' s.d '.$this->tanggal->formatDatedmY($to_tgl)?> </span></b>
              <small>Periode Tanggal</small></span>
          </a>
        </li>


        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><span style="font-size: 14px;" id="total_antrian"><?php echo count($detail)?></span></b>
              <small>Total Pasien</small></span>
          </a>
        </li>

        <li class="light-blue" style="background-color: lightgrey !important;color: black">
          <a data-toggle="dropdown" href="#" class="dropdown-toggle" style="background-color: lightgrey !important; color: black">
            <span class="user-info">
              <b><span style="font-size: 14px;" id="total_bill_dr_current"><?php echo number_format(array_sum($arr_total_bill))?></span></b>
              <small>Total Billing</small></span>
          </a>
        </li>
  </div>  
<div> 

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
          <form class="form-horizontal" method="post" id="form_permintaan" action="<?php echo site_url('purchasing/permintaan/Req_pembelian/process')?>" enctype="multipart/form-data" >
            <b>PEMBAYARAN JASA DOKTER</b>
            <div class="form-group">
              <label class="control-label col-md-2">Kode Pembayaran</label>
              <div class="col-md-2">
                <input name="id" id="id" value="<?php echo isset($value)?$value->id_tc_permohonan:''?>" placeholder="Auto" class="form-control" type="text" readonly>
              </div>
              <label class="control-label col-md-1">Tanggal</label>
              <div class="col-md-2" style="padding-top: 3px">
                <span style="margin-left: 5px"><?php echo date('d/m/Y H:i:s')?></span>
              </div>
              <label class="control-label col-md-1">Petugas</label>
              <div class="col-md-2" style="padding-top: 3px">
                <span style="margin-left: 5px"><?php echo $this->session->userdata('user')->fullname?></span>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Pajak Pendapatan (%)</label>
              <div class="col-md-1">
                <input name="id" id="id" value="10" placeholder="" class="form-control" type="text">
              </div>
              <label class="control-label col-md-2">Total Tagihan</label>
              <div class="col-md-2">
                <input name="id" id="id" value="<?php echo array_sum($arr_total_bill)?>"  class="form-control" type="text" style="text-align: right">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Keterangan</label>
              <div class="col-md-5">
                <textarea name="keterangan" class="form-control" style="height: 50px !important" id="">Pembayaran Jasa Dokter Tanggal Periode <?php echo $this->tanggal->formatDatedmY($from_tgl).' s.d '.$this->tanggal->formatDatedmY($to_tgl)?>
                </textarea>
              </div>
            </div>
            
            <table class="table table-bordered" style="width:100% !important; margin-top: 10px">
              <thead>
                <tr style="background-color: #87b87f;">
                  <th class="center">
                    <div class="center">
                      <label class="pos-rel">
                          <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                          <span class="lbl"></span>
                      </label>
                    </div>
                  </th>
                  <th class="center">No</th>
                  <th>No MR</th>
                  <th>Nama Pasien</th>
                  <th>Tgl Kunjungan</th>
                  <th width="200px">Tindakan</th>
                  <th>Unit/Bagian</th>
                  <th>Penjamin</th>
                  <th>Total Billing</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $arr_total = array(); 
                  $no_order = 0;
                  foreach($detail as $row_detail_dt) :
                    $no_order++;
                ?>
                  <tr>
                    <td align="center">
                      <div class="center">
                          <label class="pos-rel">
                              <input type="checkbox" class="ace" name="selected_id[]" value="<?php echo $row_detail_dt->no_kunjungan?>"/>
                              <span class="lbl"></span>
                          </label>
                      </div>
                    </td>
                    <td align="center"><?php echo $no_order?></td>
                    <td><?php echo $row_detail_dt->no_mr?></td>
                    <td><?php echo $row_detail_dt->nama_pasien_layan?></td>
                    <td><?php echo $this->tanggal->formatDateTime($row_detail_dt->tgl_jam)?></td>
                    <td><?php echo $row_detail_dt->nama_tindakan?></td>
                    <td><?php echo $row_detail_dt->nama_bagian?></td>
                    <td><?php echo $row_detail_dt->nama_perusahaan?></td>
                    <td align="right"><?php echo number_format($row_detail_dt->billing); ?></td>
                  </tr>
                <?php 
                  $arr_total[] = $row_detail_dt->billing; 
                  endforeach;
                ?>
                <tr>
                  <td colspan="8" align="right"><b>TOTAL</b></td>
                  <td align="right"><b><?php echo number_format(array_sum($arr_total))?>,-</b></td>
                </tr>
              </tbody>
              
            </table>

            
          <div class="form-actions center">
            <a onclick="getMenu('adm_pasien/pembayaran_dr/Pembentukan_saldo_dr')" href="#" class="btn btn-sm btn-success">
              <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
              Kembali ke daftar
            </a>
            <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
              <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
              Submit
            </button>
          </div>
        </form>
      </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


