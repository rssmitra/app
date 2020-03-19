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
  
    $('#form_csm_klaim').ajaxForm({
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
          $('#page-area-content').load('casemix/Csm_klaim?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('#btn_view_data').click(function (e) {
          e.preventDefault();
          $.ajax({
          url: 'casemix/Csm_klaim/find_klaim_by_waktu_input',
          type: "post",
          data: $('#form_csm_klaim').serialize(),
          dataType: "json",
          beforeSend: function() {
            /*cek field*/
            if($('#csm_klaim_dari_tgl').val()=='' || $('#csm_klaim_sampai_tgl').val()==''){
              alert('Masukan Waktu Input!'); return false;
            }
          },
          success: function(data) {
            $('#csm_klaim_total_ri').val(data.total_ri);
            $('#csm_klaim_total_rj').val(data.total_rj);
            $('#csm_klaim_total_rp').val(format_money(data.total_rp,''));
            $('#csm_klaim_total_rp_hidden').val(data.total_rp);
            $('#csm_klaim_total_dokumen').val(data.total_dok);
            $('#response_data').show('fast');
            $('#btnSave').show('fast');
          }
        });
    });

})


function format_money(n, currency) {
  return currency + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
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
          <div class="widget-body">
            <div class="widget-main no-padding">
              <form class="form-horizontal" method="post" id="form_csm_klaim" action="<?php echo site_url('casemix/Csm_klaim/process')?>" enctype="multipart/form-data">
                <br>

                <div class="form-group">
                  <label class="control-label col-md-2">ID</label>
                  <div class="col-md-1">
                    <input name="id" id="id" value="<?php echo isset($value)?$value->csm_klaim_id:0?>" placeholder="Auto" class="form-control" type="text" readonly>
                  </div>
                  
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Periode Klaim</label>
                  <div class="col-md-6">
                    <select name="csm_klaim_bulan">
                      <option value="">-Silahkan Pilih-</option>
                      <?php 
                        for($i=1;$i<13;$i++):
                          $selected_month = isset($value)?($value->csm_klaim_bulan==$i)?'selected':'':'';
                          echo '<option value="'.$i.'" '.$selected_month.'>'.$this->tanggal->getBulan($i).'</option>';
                        endfor;
                      ?>
                    </select>
                    <select name="csm_klaim_tahun">
                      <option value="">-Silahkan Pilih-</option>
                      <?php 
                        for($j=date('Y')-3;$j<date('Y')+1;$j++):
                          $selected_year = isset($value)?($value->csm_klaim_tahun==$j)?'selected':'':'';
                          echo '<option value="'.$j.'" '.$selected_year.'>'.$j.'</option>';
                        endfor;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Waktu Input</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control date-picker" name="csm_klaim_dari_tgl" id="csm_klaim_dari_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->csm_klaim_dari_tgl:''?>"/>
                        <span class="input-group-addon">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    </div>
                    <label class="control-label col-md-1">s/d</label>
                    <div class="col-md-2">
                      <div class="input-group">
                        <input class="form-control date-picker" name="csm_klaim_sampai_tgl" id="csm_klaim_sampai_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->csm_klaim_sampai_tgl:''?>"/>
                        <span class="input-group-addon">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    </div>
                    <a href="#" id="btn_view_data" name="view_data" class="btn btn-xs btn-primary">
                    Lihat Data 
                    <i class="ace-icon fa fa-play icon-on-right bigger-110"></i>
                  </a>
                </div>

                <div class="form-group" id="response_data" style="<?php echo isset($value)?'display:block':'display:none'?>">
                  <label class="control-label col-md-2">&nbsp;</label>
                  <div class="col-md-10">
                    <b>RESUME KLAIM</b>
                    <table class="">
                      <tr>
                        <td>Total Rawat Inap (RI)</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="csm_klaim_total_ri" id="csm_klaim_total_ri" style="width:80px" readonly value="<?php echo isset($value)?$value->csm_klaim_total_ri:''?>"></td>
                        <td>Total Rawat Jalan (RJ)</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="csm_klaim_total_rj" id="csm_klaim_total_rj" style="width:80px" readonly value="<?php echo isset($value)?$value->csm_klaim_total_rj:''?>"></td>
                      </tr>
                      <tr>
                        <td>Total Dokumen</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="csm_klaim_total_dokumen" id="csm_klaim_total_dokumen" style="width:80px" readonly value="<?php echo isset($value)?$value->csm_klaim_total_dokumen:''?>"></td>
                        <td>Total Klaim (Rp)</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="csm_klaim_total_rp" id="csm_klaim_total_rp" style="width:150px" readonly value="<?php echo isset($value)?number_format($value->csm_klaim_total_rp):''?>"></td>
                        <input type="hidden" name="csm_klaim_total_rp_hidden" id="csm_klaim_total_rp_hidden" style="width:150px" value="<?php echo isset($value)?$value->csm_klaim_total_rp:''?>">
                      </tr>
                    </table>
                    <br>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2">Petugas</label>
                  <div class="col-md-2">
                  <input type="text" name="created_by" readonly value="<?php echo $this->session->userdata('user')->fullname?>">
                  </div>
                  <label class="control-label col-md-1">Tanggal</label>
                  <div class="col-md-4">
                  <input type="text" name="created_date" readonly value="<?php echo $this->tanggal->formatDate(date('Y-m-d'))?>">
                  </div>
                </div>

                <div class="form-actions center">

                  <a onclick="getMenu('casemix/Csm_klaim')" href="#" class="btn btn-xs btn-success">
                    <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                    Kembali ke daftar
                  </a>
                  <?php if($flag != 'read'):?>
                  <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                    <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                    Reset
                  </button>
                  <button type="submit" style="<?php echo isset($value)?'':'display:none'?>" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Submit
                  </button>
                <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


