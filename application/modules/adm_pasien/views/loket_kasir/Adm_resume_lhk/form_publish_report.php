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
  
    $('#form_publish_report').ajaxForm({
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
          getMenuTabs('adm_pasien/loket_kasir/Adm_resume_lhk/get_data?method=tunai&from_tgl=<?php echo $date?>&flag='+$('#flag').val()+'', 'tab_content_data');
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $( "#pembulatan" ).keypress(function(event) {  
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){           
            $('#total_akhir').focus();       
          }         
          return false;                
        }       
    });  

})

function sumPembulatan(){
  var total = formatNumberFromCurrency($('#total_pendapatan').val());
  // change total pendapatan
  var pembulatan = parseInt($('#pembulatan').val()) + parseInt(total);
  var result = formatMoney(pembulatan);
  $('#total_akhir').val(result);
}
</script>
<style type="text/css">
  .dropdown-item{
    height : 100px;
    width: 300px;
  }
</style>


<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <?php
        foreach($resume as $row_resume) {
          $arr_tunai[] = $row_resume['tunai'];
          $arr_debet[] = $row_resume['debet'];
          $arr_kredit[] = $row_resume['kredit'];
          $arr_piutang[] = $row_resume['piutang'];
          $arr_nk_karyawan[] = $row_resume['nk_karyawan'];
          $arr_nk_perusahaan[] = $row_resume['piutang'];
          $arr_potongan[] = $row_resume['potongan'];
          $total = $row_resume['bill'] + $row_resume['potongan'];
          $arr_bill[] = $total;
        }
      ?>
      <span style="font-size: 16px ;"><?php echo strtoupper($title)?></span>
      <form class="form-horizontal" method="post" id="form_publish_report" action="adm_pasien/loket_kasir/Adm_resume_lhk/process_publish" enctype="multipart/form-data" >
        <br>
        <?php if($is_published == true) :?>
        <span style="margin-left:61%;position:absolute;transform: rotate(0deg) !important; margin-top: -2%" class="stamp is-approved">Published</span>
        <?php endif; ?>

        <!-- input form hidden -->
        <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

        <div class="form-group">
          <label class="control-label col-md-2">Kode Laporan</label>
          <div class="col-md-2">
            <input name="kode_laporan" id="kode_laporan" value="" placeholder="Auto" class="form-control" type="text" readonly>
          </div>
          <label class="control-label col-md-1">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="tanggal_transaksi" id="tanggal_transaksi" type="text" data-date-format="yyyy-mm-dd" value="<?php echo $date?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Petugas Kasir</label>
          <div class="col-md-2">
            <input name="id" id="id" value="<?php echo $this->session->userdata('user')->fullname?>" class="form-control" type="text">
          </div>
        </div>

        <br>
        <span style="font-size: 14px ;">Rincian Pendapatan</span>

        <table class="table" style="width:80%">
          <thead>
            <tr>
              <th>Tunai</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>NK Perusahaan</th>
              <th>NK Karyawan</th>
              <th>Potongan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo number_format(array_sum($arr_tunai))?></td>
              <td><?php echo number_format(array_sum($arr_debet))?></td>
              <td><?php echo number_format(array_sum($arr_kredit))?></td>
              <td><?php echo number_format(array_sum($arr_nk_perusahaan))?></td>
              <td><?php echo number_format(array_sum($arr_nk_karyawan))?></td>
              <td><?php echo number_format(array_sum($arr_potongan))?></td>
            </tr>
          </tbody>
        </table>

        <input name="tunai" id="tunai" style="text-align: right" value="<?php echo number_format(array_sum($arr_tunai))?>" readonly class="form-control" type="hidden">
        <input name="debet" id="debet" style="text-align: right" value="<?php echo number_format(array_sum($arr_debet))?>" readonly class="form-control" type="hidden">
        <input name="kredit" id="kredit" style="text-align: right" value="<?php echo number_format(array_sum($arr_kredit))?>" readonly class="form-control" type="hidden">
        <input name="nk_perusahaan" id="nk_perusahaan" style="text-align: right" value="<?php echo number_format(array_sum($arr_nk_perusahaan))?>" readonly class="form-control" type="hidden">
        <input name="nk_karyawan" id="nk_karyawan" style="text-align: right" value="<?php echo number_format(array_sum($arr_nk_karyawan))?>" readonly class="form-control" type="hidden">
        <input name="potongan" id="potongan" style="text-align: right" value="<?php echo number_format(array_sum($arr_potongan))?>" readonly class="form-control" type="hidden">

        <br>
        <span style="font-size: 14px ;">Resume Rincian</span>

        <div class="form-group">
          <label class="control-label col-md-2">Total Pendapatan</label>
          <div class="col-md-2">
            <input name="total_pendapatan" id="total_pendapatan" style="text-align: right" value="<?php echo number_format(array_sum($arr_bill))?>" readonly class="form-control" type="text">
          </div>
          <label class="control-label col-md-1">Pembulatan</label>
          <div class="col-md-2">
            <input name="pembulatan" id="pembulatan" value="<?php echo isset($publish_data->pembulatan)?number_format($publish_data->pembulatan):0?>" class="form-control" type="text" onkeyup="sumPembulatan()">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Total Akhir</label>
          <div class="col-md-2">
            <input name="total_akhir" id="total_akhir" style="text-align: right" value="<?php echo isset($publish_data->total_stlh_pembulatan)?number_format($publish_data->total_stlh_pembulatan):0?>" class="form-control" type="text" readonly>
          </div>
          <span class="col-md-8" style="margin-top: 3px; font-style: italic;">"Setelah ditambah dengan pembulatan"</span>
        </div>

        <div class="form-group">
          <label class="control-label col-md-2">Keterangan</label>
          <div class="col-md-5">
            <textarea class="form-control" name="keterangan" style="height: 50px !important"><?php echo isset($publish_data->keterangan)?$publish_data->keterangan:'Laporan Harian Kasir (LHK)'?></textarea>
          </div>
        </div>
        <br>
        <div class="clearfix"></div>

        <?php if($is_published == false) :?>

          <div class="form-actions center">
            <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
              <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
              Submit
            </button>
          </div>

        <?php endif; ?>
      </form>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


