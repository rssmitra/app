<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <div class="row">
      <div class="col-md-4" style="background: white;min-height: 80px">
        <table width="100%" style="font-size:12px; ">
          <tr>
            <td colspan="2"><b>DATA PASIEN</b></td>
          </tr>
          <tr>
            <td width="120px">No MR</td>
            <td>: <?php echo $data->reg_data->no_mr?></td>
          </tr>
          <tr>
            <td>Nama Pasien</td>
            <td>: <?php echo $data->reg_data->nama_pasien?></td>
          </tr>
        </table>
      </div>

      <div class="col-md-4" style="background: white;min-height: 80px">
        <table>
          <tr>
            <td colspan="2"><b>DATA TRANSAKSI</b></td>
          </tr>
          <tr>
            <td width="120px">No Trx Farmasi</td>
            <td>: <?php echo $data->kode_trans_far?></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>: <?php echo $this->tanggal->formatDateTime($data->reg_data->tgl_trans)?></td>
          </tr>
        </table>
      </div>

    </div>

    <!-- hidden form -->
    <input type="hidden" id="perusahaan_penjamin" value="UMUM" name="perusahaan_penjamin">
    <input type="hidden" id="no_registrasi" value="<?php echo isset($data->reg_data->no_registrasi)?$data->reg_data->no_registrasi:''?>" name="no_registrasi">
    <input type="hidden" id="kode_trans_far" value="<?php echo isset($data->reg_data->kode_trans_far)?$data->reg_data->kode_trans_far:''?>" name="kode_trans_far">
    <input type="hidden" id="total_payment_all" value="" name="total_payment_all">
    <input type="hidden" id="total_payment" value="" name="total_payment">
    <input type="hidden" id="no_mr_val" value="<?php echo isset($data->reg_data->no_mr)?$data->reg_data->no_mr:''?>" name="no_mr_val">
    <input type="hidden" id="nama_pasien_val" value="<?php echo isset($data->reg_data->nama_pasien)?$data->reg_data->nama_pasien:''?>" name="nama_pasien_val">
    <input type="hidden" id="no_sep_val" value="<?php echo isset($data->reg_data->no_sep)?$data->reg_data->no_sep:''?>" name="no_sep_val">
    <input type="hidden" name="array_data_checked" id="array_data_checked">
    <input type="hidden" name="array_data_nk_checked" id="array_data_nk_checked">
    <input type="hidden" name="array_data_billing" id="array_data_billing">
    <input type="hidden" name="total_nk" id="total_nk">
    <input type="hidden" name="total_uang_muka" id="total_uang_muka" value="0">
    <input type="hidden" id="kode_perusahaan_val" value="<?php echo isset($data->reg_data->kode_perusahaan)?$data->reg_data->kode_perusahaan:''?>" name="kode_perusahaan_val">
    <input type="hidden" id="kode_kelompok_val" value="<?php echo isset($data->reg_data->kode_kelompok)?$data->reg_data->kode_kelompok:''?>" name="kode_kelompok_val">
    <input type="hidden" id="kode_kelompok_val" value="<?php echo isset($data->reg_data->kode_kelompok)?$data->reg_data->kode_kelompok:''?>" name="kode_kelompok_val">

  </div><!-- /.col -->
</div>
<hr>