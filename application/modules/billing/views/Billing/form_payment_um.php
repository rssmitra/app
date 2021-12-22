<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<!-- jquery number -->
<script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
<script type="text/javascript">

$(document).ready(function(){

  $('.format_number').number( true, 2 );
  
  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: 'yyyy-mm-dd'
    })
    //show datepicker when clicking on the icon
    .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#form_billing_kasir_um').ajaxForm({
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

        if (jsonResponse.billing_um > 0) {

          getMenuTabs('billing/Billing/payment_um_view/'+jsonResponse.no_registrasi+'/RI?flag=&ID='+jsonResponse.kode_ri+'');

        }

      }else{
        $.achtung({message: jsonResponse.message, timeout:5});
      }
      achtungHideLoader();
    }
  }); 
  
  $( ".uang_dibayarkan" ).keypress(function(event) {  
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){          
        event.preventDefault();         
        if($(this).valid()){           
          // $('#jumlah_nk').focus();    
        }         
        return false;                
      }   

      
  });

  $('input[name=metode_tunai]').change(function(){
      preventDefault();
      if($(this).is(':checked')){
        $('#div_tunai').show();
        cek_sisa_belum_bayar('uang_dibayarkan');
      } else {
        $('#div_tunai').hide();
        $('#uang_dibayarkan').val(0);
        sum_total_pembayaran();
      }
      
  });

  $('input[name=metode_debet]').change(function(){
      preventDefault();
      if($(this).is(':checked')){
        $('#div_debet').show();
        cek_sisa_belum_bayar('jumlah_bayar_non_tunai');
      } else {
        $('#div_debet').hide();
        $('#jumlah_bayar_non_tunai').val(0);
        sum_total_pembayaran();
      }
      
  });
    
})

function sum_total_pembayaran(){

  preventDefault();

  var cash = $('#uang_dibayarkan').val();
  var sum_class = sumClass('uang_dibayarkan');

  console.log(sum_class + ' sum_class Uang Dibayarkan - sum_total_pembayaran');
  // console.log(total);
  $('#total_payment').val(sum_class);

}

function cek_sisa_belum_bayar(div_id){
  var total_deposit = $('#total_deposit').val();
  var sum_class = sumClass('uang_dibayarkan');
  var sisa_blm_bayar = parseInt(total_deposit) - parseInt(sum_class);
  if (parseInt(sisa_blm_bayar) > 0) {
    var blm_dibayarkan = sisa_blm_bayar;
  }else{
    var blm_dibayarkan = 0;
  }
  $('#'+div_id+'').val(blm_dibayarkan);
  sum_total_pembayaran();
}

</script>

<style>
  .blink_me {
  animation: blinker 1s linear infinite;
  }

  @keyframes blinker {
    50% {
      opacity: 0;
    }
  }
</style>

<form class="form-horizontal" method="post" id="form_billing_kasir_um" action="<?php echo site_url('billing/Billing/process_um')?>" enctype="multipart/form-data" autocomplete="off">

<!-- hidden form -->
<input type="hidden" value="<?php echo count($result->kasir_data)?>" id="count_kasir">

<input name="total_deposit" id="total_deposit" value="<?php echo isset($deposit->nilai_deposit)?$deposit->nilai_deposit : 0;?>" class="form-control" style="text-align: right" type="hidden">
<input name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($deposit->no_kunjungan)?$deposit->no_kunjungan : 0;?>" class="form-control" style="text-align: right" type="hidden">

<input type="hidden" id="total_payment" value="<?php echo isset($deposit->nilai_deposit)?$deposit->nilai_deposit : 0;?>" name="total_payment">
<input type="hidden" value="<?php echo $result->reg_data->kode_kelompok; ?>" id="id_kel">
<input type="hidden" id="perusahaan_penjamin" value="<?php echo isset($result->reg_data->nama_perusahaan)?$result->reg_data->nama_perusahaan:'UMUM'?>" name="perusahaan_penjamin">
<input type="hidden" id="no_registrasi" value="<?php echo $no_registrasi?>" name="no_registrasi">
<input type="hidden" id="no_mr_val" value="<?php echo isset($result->reg_data->no_mr)?$result->reg_data->no_mr:''?>" name="no_mr_val">
<input type="hidden" id="nama_pasien_val" value="<?php echo isset($result->reg_data->nama_pasien)?$result->reg_data->nama_pasien:''?>" name="nama_pasien_val">
<input type="hidden" id="kode_perusahaan_val" value="<?php echo isset($result->reg_data->kode_perusahaan)?$result->reg_data->kode_perusahaan:''?>" name="kode_perusahaan_val">
<input type="hidden" id="kode_kelompok_val" value="<?php echo isset($result->reg_data->kode_kelompok)?$result->reg_data->kode_kelompok:''?>" name="kode_kelompok_val">
<input type="hidden" id="nama_dokter_val" value="<?php echo isset($result->reg_data->nama_pegawai)?$result->reg_data->nama_pegawai:$result->trans_data[0]->nama_dokter?>" name="nama_dokter_val">
<input type="hidden" id="kode_bag_val" value="<?php echo isset($result->reg_data->kode_bagian_masuk)?$result->reg_data->kode_bagian_masuk:''?>" name="kode_bag_val">
<input type="hidden" id="kode_bag_ri" value="<?php echo isset($deposit->bag_pas)?$deposit->bag_pas:''?>" name="kode_bag_ri">


<div class="row">
  <div class="col-xs-12">
    <div class="pull-left">
      <table class="table">
        <tr>
          <?php if(count($um) > 0) :?>
            <?php 
              foreach($um as $row_um) : 
                $total_um[] = $row_um->jumlah;
            ?>
              <td style="padding: 10px">
                  No. <?php echo $row_um->no_kuitansi; ?> <br><i><?php echo $this->tanggal->formatDateTime($row_um->tgl_bayar); ?></i><br>
                  Total Bayar<br>
                  <span style="font-size: 14px; font-weight: bold"> <i class="fa fa-check-circle green"></i> <?php echo number_format($row_um->jumlah); ?></span>
            </td>
            <?php endforeach; ?>
          <?php 
            endif;
            $nilai_deposit = isset($deposit->nilai_deposit) ? $deposit->nilai_deposit : 0;
            $sisa_deposit = $nilai_deposit - array_sum($total_um);
          ?>
        </tr>
      </table>
    </div>
    <div class="pull-right">
      Nilai Deposit<br>
      <span style="font-size: 20px; font-weight: bold"> <?php echo isset($deposit->nilai_deposit)?number_format($deposit->nilai_deposit) : 0;?></span>
    </div>
  </div>
</div>

<div class="row" id="div_form_payment">
  
  <div class="col-xs-12">

    <!-- <div class="form-group">
      <label class="control-label col-md-3">Pembayar (a.n)</label>
      <div class="col-md-9">
        <input name="pembayar" id="pembayar" value="<?php echo $result->reg_data->nama_pasien?>" class="form-control" type="text">
      </div>
    </div> -->

    <!-- <div class="form-group" id="">
      <label class="control-label col-md-3">Kategori Pasien</label>
      <div class="col-md-9" id="kode_kelompok_form">
      <?php ($result->reg_data->kode_perusahaan == 120) ? $state='disabled' : $state='';
        echo $this->master->custom_selection($params = array('table' => 'mt_nasabah', 'id' => 'kode_kelompok', 'name' => 'nama_kelompok', 'where' => array()), $result->reg_data->kode_kelompok , 'kode_penjamin_pasien', 'kode_penjamin_pasien', 'form-control', 'onchange=statusKaryawan(value);', $state) ?>
      </div>
    </div> -->

    <hr>

    <div class="form-group">                        
        <label class="control-label col-md-3">Tanggal Transaksi</label>        
        <div class="col-md-2">
            <div class="input-group">
                <input name="tgl_trans_kasir" id="tgl_trans_kasir"  class="form-control date-picker" type="text" value="<?php echo isset($result->reg_data->tgl_jam_keluar)?$this->tanggal->formatDateTimeToSqlDate($result->reg_data->tgl_jam_keluar):$this->tanggal->formatDateTimeToSqlDate($result->reg_data->tgl_jam_masuk);?>">
                <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Nama Pasien</label>
      <div class="col-md-9" style="margin-left: 8px; padding-top: 5px">
        <b><?php echo $result->reg_data->nama_pasien?></b>
      </div>
    </div>
    
    <div class="form-group" id="metode_pembayaran_form">
      <label class="control-label col-md-3">Metode Pembayaran</label>
      <div class="col-md-9" style="padding-top: 3px;padding-left: 20px;padding-right: -20px;">
        <label>
          <input name="metode_tunai" id="tunai" type="checkbox" class="ace" value="1" checked>
          <span class="lbl"> Tunai &nbsp;&nbsp; </span>
        </label>
        <label>
          <input name="metode_debet" id="debet" type="checkbox" class="ace" value="2">
          <span class="lbl"> Non Tunai (Kartu Debet/Kredit) &nbsp;&nbsp; </span>
        </label>
      </div>
    </div>

    
    <div id="div_tunai">
      <hr class="separator">
      <p><b>PEMBAYARAN TUNAI</b></p>

      <div class="form-group">
        <label class="control-label col-md-3">Uang Yang Dibayarkan</label>
        <div class="col-md-9">
          <!-- hidden total yang harus dibayarkan -->
          <input name="uang_dibayarkan_tunai" id="uang_dibayarkan" class="format_number uang_dibayarkan form-control" type="text" style="text-align: right" value="<?php echo $sisa_deposit; ?>">
        </div>
      </div>
    </div>
    
    <div id="div_debet" style="display:none">
      <hr>
      <p><b>PEMBAYARAN NON TUNAI</b></p>
      
      <div class="form-group">
        <label class="control-label col-md-3">Jenis kartu</label>
        <div class="col-md-9" style="padding-top: 3px; margin-left: 5px">
          <label>
            <input type="radio" class="ace" name="jenis_kartu" value="debet" checked>
            <span class="lbl"> Debet</span>
          </label>

          <label>
            <input type="radio" class="ace" name="jenis_kartu" value="kredit">
            <span class="lbl"> Kredit</span>
          </label>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Jumlah Pembayaran Non Tunai</label>
        <div class="col-md-9">
        <input name="jumlah_bayar_non_tunai" id="jumlah_bayar_non_tunai" value="" class="format_number uang_dibayarkan form-control" style="text-align: right" type="text" oninput="sum_total_pembayaran()">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Bank</label>
        <div class="col-md-9">
          <?php echo $this->master->custom_selection_with_label($params = array('table' => 'mt_bank', 'id' => 'kode_bank', 'name' => 'nama_bank', 'label' => 'acc_no', 'where' => array() ), '' , 'kd_bank_non_tunai', 'kd_bank_non_tunai', 'form-control', '', '') ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Nomor Kartu</label>
        <div class="col-md-9">
          <input name="nomor_kartu_non_tunai" id="nomor_kartu_non_tunai" value="" class="form-control" type="text">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-3">Nomor Batch</label>
        <div class="col-md-9">
          <input name="nomor_batch_non_tunai" id="nomor_batch_non_tunai" value="" class="form-control" type="text">
        </div>
      </div>
    </div>

    <hr class="separator">
    <p><b>PETUGAS KASIR</b></p>
    <div class="form-group">
      <label class="control-label col-md-2">Shift</label>
      <div class="col-md-3">
        <select class="form-control" name="shift">
          <option value="1">Pagi</option>
          <option value="2">Siang</option>
          <option value="3">Malam</option>
        </select>
      </div>
      <label class="control-label col-md-2">Loket</label>
      <div class="col-md-2">
        <select class="form-control" name="loket">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
      </div>
      <div class="col-md-4" style="margin-left:-4%">
        <input type="text" class="form-control" name="petugas" style="width: 100% !important" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
      </div>
    </div>
    
    
    <div class="form-actions center">
      <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
        <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
        Batal
      </button>
      <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Submit
      </button>
    </div>

  </div><!-- /.col -->

</div>

</form>
