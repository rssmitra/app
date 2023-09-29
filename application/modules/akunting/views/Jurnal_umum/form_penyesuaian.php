<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script>

$(document).ready(function(){
    
    $('.format_number').number( true, 2 );

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
        }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    
    <?php $nox = 0; foreach($jurnal as $row_dt_jurnal) : $nox++; ?>
    var tagId = 'no_acc_<?php echo $nox; ?>';
    var tagName = 'nama_acc_<?php echo $nox; ?>';
    $('#'+tagId+'').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "Templates/References/getAccountCoa",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          $('#'+tagId+'').val(val_item);
          $('#'+tagName+'').val(label_item);
          console.log(tagId);
        }
    });
    <?php endforeach; ?>

  })
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
            
          <form class="form-horizontal" method="post" id="form-default" action="<?php echo site_url('master_data/Global_parameter/process')?>" enctype="multipart/form-data" autocomplete="off">
              <br>
              <span style="font-weight: bold;font-size: 16px "><?php echo isset($value)?$value->no_bukti:''?></span><br>
              <?php echo $value->no_mr.' - '.$value->nama_pasien;?><br>
              Total. Rp. <?php echo isset($value)?number_format($value->total):''?><br>
              Tgl. <?php echo isset($value)?$this->tanggal->formatDateTime($value->tgl_transaksi):date('Y-m-d H:i:s')?><br>
              <?php echo isset($value)?$value->uraian_transaksi:''?>

              <!-- hidden form -->
              <input name="id" id="id" value="<?php echo isset($value)?$value->id_ak_tc_transaksi:0?>" placeholder="Auto" class="form-control" type="hidden">

              <hr>
              <table class="table">
                <thead>
                  <tr style="background: cadetblue;">
                    <th style="width: 50px"></th>
                    <th style="width: 100px">Kode Akun</th>
                    <th>Nama Akun</th>
                    <th style="width: 100px">Debit</th>
                    <th style="width: 100px">Kredit</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $count_dt = count($jurnal);
                    $no = 0;
                    foreach($jurnal as $row_dt_jurnal) : $no++; ?>
                    <tr id="row_<?php echo $no; ?>">
                      <td style="vertical-align: middle" align="center">
                        <a href="#"><i class="fa fa-times-circle-o bigger-150 red"></i></a>
                      </td>
                      <td style="vertical-align: middle">
                        <input type="text" name="acc_no" class="typeahead-input" value="<?php echo $row_dt_jurnal->acc_no?>" id="no_acc_<?php echo $no?>" style="width: 100%; border: 0px">
                      </td>
                      <td style="vertical-align: middle">
                        <input type="text" name="nama_acc" class="typeahead-input" value="<?php echo $row_dt_jurnal->acc_nama?>" id="nama_acc_<?php echo $no?>" style="width: 100%; border: 0px">
                      </td>
                      <td style="vertical-align: middle" align="right">
                        <?php $nominal_debet =  ($row_dt_jurnal->tipe_tx == 'D') ? number_format($row_dt_jurnal->nominal) : 0; ?>
                        <input type="text" name="debet" class="format_number" value="<?php echo $nominal_debet?>" id="form-<?php echo $no?>" style="width: 100%; border: 0px; text-align: right">
                      </td>
                      <td style="vertical-align: middle" align="right">
                        <?php $nominal_kredit =  ($row_dt_jurnal->tipe_tx == 'K') ? number_format($row_dt_jurnal->nominal) : 0; ?>
                        <input type="text" name="debet" class="format_number" value="<?php echo $nominal_kredit?>" id="form-<?php echo $no?>" style="width: 100%; border: 0px; text-align: right">
                      </td>
                    </tr>
                  <?php
                    $arr_debet[] = ($row_dt_jurnal->tipe_tx == 'D') ? $row_dt_jurnal->nominal : 0;
                    $arr_kredit[] = ($row_dt_jurnal->tipe_tx == 'K') ? $row_dt_jurnal->nominal : 0;
                    endforeach;
                  ?>

                  <?php for ($i=count($jurnal); $i < 8; $i++) :?>
                    <tr>
                      <td style="vertical-align: middle" align="center"></td>
                      <td style="vertical-align: middle">
                        <input type="text" name="acc_no" value="" class="typeahead-input" style="width: 100%; border: 0px">
                      </td>
                      <td style="vertical-align: middle"></td>
                      <td style="vertical-align: middle" align="right">
                        <input type="text" name="debet" value="" style="width: 100%; border: 0px; text-align: right" class="format_number">
                      </td>
                      <td style="vertical-align: middle" align="right">
                        <input type="text" name="kredit" value="" style="width: 100%; border: 0px; text-align: right" class="format_number">
                      </td>
                    </tr>
                  <?php endfor; ?>
                <tr style="font-weight: bold">
                  <td align="right" colspan="3">TOTAL</td>
                  <td align="right"><?php echo number_format(array_sum($arr_debet))?></td>
                  <td align="right"><?php echo number_format(array_sum($arr_kredit))?></td>
                </tr>
                <tr>
                  <td colspan="3"></td>
                  <?php if(array_sum($arr_debet) == array_sum($arr_kredit)) :?>
                    <td colspan="2" align="center" style="background: green; color: white">
                          <i class="ace-icon fa fa-check bigger-120"></i>
                          Balance
                    </td>
                  <?php endif; ?>

                  <?php if(array_sum($arr_debet) != array_sum($arr_kredit)) :?>
                    <td colspan="2" align="center" style="background: red; color: white">
                          <i class="ace-icon fa fa-times-circle bigger-120"></i>
                          Not Balance
                    </td>
                  <?php endif; ?>

                </tr>
                </tbody>
              </table>
              
              <br>
              <div class="pull-right">
                <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                    <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                    Simpan Perubahan
                </button>
              </div>


          </form>

        </div>
      </div>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->
