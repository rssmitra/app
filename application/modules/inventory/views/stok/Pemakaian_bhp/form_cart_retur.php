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
        $('#page-area-content').load('purchasing/pendistribusian/Pengiriman_unit/form_retur?flag='+$("input[name='flag_gudang']:checked"). val()+'');
        PopupCenter('purchasing/pendistribusian/Distribusi_permintaan/print_preview_retur/'+jsonResponse.id+'?flag='+$("input[name='flag_gudang']:checked"). val()+'', 'Cetak Bukti Pengiriman Barang ke Unit', 900, 600);

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
</script>

<?php $url = ($form == 'distribusi') ? 'purchasing/pendistribusian/Pengiriman_unit/process_pengiriman_brg_unit' : 'purchasing/pendistribusian/Pengiriman_unit/process_retur_brg_unit' ; ?>

<form class="form-horizontal" method="post" id="form_cart" action="<?php echo site_url().$url?>" enctype="multipart/form-data" style="margin-top: -10px">
  <div class="row" style="margin-top: -13px;">

  <!-- hidden form -->
    <input type="hidden" name="flag" id="flag_cart" value="<?php echo isset($flag)?$flag:''?>">
    <input type="hidden" name="flag_form" id="flag_form" value="<?php echo isset($form)?$form:''?>">
    <input type="hidden" name="dari_unit_hidden" id="dari_unit_hidden" value="<?php echo isset($_GET['unit'])?$_GET['unit']:''?>">
    <div class="form-group">
      <label class="control-label col-md-3">Tanggal Retur</label>
      <div class="col-md-4">
        <div class="input-group">
          <input class="form-control date-picker" name="tgl_retur" id="tgl_retur" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
          <span class="input-group-addon">
            <i class="fa fa-calendar bigger-110"></i>
          </span>
        </div>
      </div>
      <div style="margin-top: 5px">
        <span id="add_note_span"><a href="#" class="" onclick="show_hide_note('show')"><i class="fa fa-edit bigger-120 green"></i> Add note</a></span>
      </div>
    </div>


    <div class="form-group">
      
    </div>

    <div class="form-group" id="catatan_form" style="display: none">
      <label class="control-label col-md-3">Catatan</label>
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
            <!-- <th rowspan="2" style="text-align:center; width: 10%; border: 1px solid #ababab; border-collapse: collapse">Dari Unit</th> -->
            <th rowspan="2" style="text-align:center; width: 20%; border: 1px solid #ababab; border-collapse: collapse">Jumlah Retur</th>
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
              // echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">'.$row_cart->nama_bagian.'</td>';

              echo '<td style="text-align:center; border-left: 1px solid #ababab; border-collapse: collapse">'.$row_cart->qty.' '.$row_cart->satuan.'</td>';
              echo '<td style="text-align:right; border-right:1px solid #ababab;border-left: 1px solid #ababab; border-collapse: collapse"><a href="#" onclick="delete_cart('."'".$row_cart->kode_brg."'".')"><i class="fa fa-times-circle red"></i></a></td>';
              echo '</tr>';
            }

            for ($i=$count; $i < 10; $i++) { 
              echo '<tr>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              // echo '<td style="text-align:left; border-left: 1px solid #ababab; border-collapse: collapse">&nbsp;</td>';
              echo '</tr>';
            }

          ?>
          <tr>
            <td colspan="4" style="text-align:right; padding-right: 20px; border: 1px solid #ababab; border-collapse: collapse"></td>
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

  </div>
</form>
