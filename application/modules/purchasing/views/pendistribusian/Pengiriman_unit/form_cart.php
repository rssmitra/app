<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script>
jQuery(function($) {

  $('#flag_cart').val($("input[name='flag_gudang']:checked"). val());
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#form_cart').ajaxForm({
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
        $('#page-area-content').load('purchasing/pendistribusian/Pengiriman_unit/form_distribusi?flag='+$("input[name='flag_gudang']:checked"). val()+'');
        PopupCenter('purchasing/pendistribusian/Distribusi_permintaan/print_preview/'+jsonResponse.id+'?flag='+$("input[name='flag_gudang']:checked"). val()+'', 'Cetak Bukti Pengiriman Barang ke Unit', 900, 600);

      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  }); 

});



function submit_cart(){
  preventDefault();
  $('#form_cart').submit();
}

function show_hide_note(action){
  if (action == 'show') {
    $('#catatan_form').show();
    $('#add_note_span').hide();
    $('#hide_note_span').show();
    $('#catatan').val();
  }else{
    $('#catatan_form').hide();
    $('#add_note_span').show();
    $('#hide_note_span').hide();
    $('#catatan').val('');
  }
}

if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true}); 
    //resize the chosen on window resize

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


  <span style="font-weight: bold; font-size: 14px">Tujuan Distribusi ke Unit</span>
  <!-- hidden form -->
    <input type="hidden" name="flag" id="flag_cart" value="<?php echo isset($flag)?$flag:''?>">
    <input type="hidden" name="flag_form" id="flag_form" value="<?php echo isset($form)?$form:''?>">
    <div class="form-group">
      <label class="control-label col-md-2">Tgl Kirim</label>
      <div class="col-md-3">
        <div class="input-group">
          <input class="form-control date-picker" name="tgl_pengiriman" id="tgl_pengiriman" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
          <span class="input-group-addon">
            <i class="fa fa-calendar bigger-110"></i>
          </span>
        </div>
      </div>
    </div>


    <div class="form-group">
      <?php $form_label = ($form == 'distribusi') ? '<label class="control-label col-md-2">Tujuan Unit</label>' : '<label class="control-label col-md-2">Dari unit</label>' ; echo $form_label; ?>
      <div class="col-md-7">
        <?php 
          echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), isset($value)?$value->kode_bagian_minta:'' , 'kode_bagian_minta', 'kode_bagian_minta', 'chosen-select form-control', '', '') ?>
      </div>
      <div style="margin-top: 5px">
        <span id="add_note_span"><a href="#" class="" onclick="show_hide_note('show')"><i class="fa fa-edit bigger-120 green"></i> Add note</a></span>
      </div>
    </div>

    <div class="form-group" id="catatan_form" style="display: none">
      <label class="control-label col-md-2">Catatan</label>
      <div class="col-md-7">
        <textarea class="form-control" style="height: 50px !important; margin-bottom: 5px" id="catatan" name="catatan"></textarea>
      </div>
      <div style="margin-top: 5px">
        <span id="hide_note_span" style="display: none"><a href="#" class="" onclick="show_hide_note('hide')"><i class="fa fa-times-circle bigger-120 red"></i> Hide note</a></span>
      </div>
    </div>

    <table class="table table-bordered">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid #ababab; border-collapse: collapse">
            <th rowspan="2" style="text-align:center; width: 5%; border: 1px solid #ababab; border-collapse: collapse">No</th>
            <th rowspan="2" style="border: 1px solid #ababab; border-collapse: collapse; width: 55%">Kode dan Nama Barang</th>
            <th rowspan="2" style="text-align:center; width: 10%; border: 1px solid #ababab; border-collapse: collapse">Qty</th>
            <th rowspan="2" style="text-align:center; width: 10%; border: 1px solid #ababab; border-collapse: collapse">Satuan</th>
            <!-- <th rowspan="2" style="text-align:center; width: 10%; border: 1px solid #ababab; border-collapse: collapse">Harga Satuan</th> -->
            <th rowspan="2" style="text-align:center; width: 15%; border: 1px solid #ababab; border-collapse: collapse">Total Harga</th>
            <th rowspan="2" style="text-align:center; width: 2%; border: 1px solid #ababab; border-collapse: collapse"></th>
          </tr>
      </thead>
      <tbody>
          <?php 
            $arr_total = array();
            $count = count($cart_data);
            $no = 0;
            foreach($cart_data as $row_cart){
              $no++;
              // total harga
              $total_harga = $row_cart->qty * $row_cart->harga;
              $arr_total[] = $total_harga;
              echo '<tr id="tr_'.$row_cart->kode_brg.'">';
              echo '<td style="text-align:center; border-left: 1px solid #ababab; border-collapse: collapse">'.$no.'</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse"><a href="#" onclick="show_modal('."'".$row_cart->kode_brg."'".')">'.$row_cart->kode_brg.' - '.$row_cart->nama_brg.'</a> &nbsp; @'.number_format($row_cart->harga).',-</td>';
              echo '<td style="text-align:center; border-left: 1px solid #ababab; border-collapse: collapse">'.$row_cart->qty.'</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">'.$row_cart->satuan.'</td>';
              echo '<td style="text-align:right; border-right:1px solid #ababab;border-left: 1px solid #ababab; border-collapse: collapse">'.number_format($total_harga).',-</td>';
              echo '<td style="text-align:right; border-right:1px solid #ababab;border-left: 1px solid #ababab; border-collapse: collapse"><a href="#" onclick="delete_cart('."'".$row_cart->kode_brg."'".')"><i class="fa fa-times-circle red"></i></a></td>';
              echo '</tr>';
            }

            for ($i=$count; $i < 10; $i++) { 
              echo '<tr>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-right: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '</tr>';
            }

          ?>
          <tr>
            <td colspan="4" style="text-align:right; padding-right: 20px; border: 1px solid #ababab; border-collapse: collapse">Total </td>
            <td style="text-align:right; border: 1px solid #ababab; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
          </tr>
          <tr>
            <td colspan="5" style="text-align:right; border: 1px solid #ababab; border-collapse: collapse">Terbilang : 
            <b><i>" <?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total))); ?> Rupiah"</i></b>
            </td>
          </tr>
      </tbody>
    </table>
    <hr>
    <div class="center" style="padding-right: 10px; padding-bottom: 5px">

      <a href="#" id="btnSave" name="submit" class="btn btn-xs btn-danger">
        <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
        Reset Form
      </a>
      <a href="#" id="btnSave" onclick="submit_cart()" name="submit" class="btn btn-xs btn-info">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Kirim
      </a>
      
    </div>


