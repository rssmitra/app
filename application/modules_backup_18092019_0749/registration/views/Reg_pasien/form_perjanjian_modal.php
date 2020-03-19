<form class="form-horizontal" method="post" id="form_daftar_perjanjian" action="#" enctype="Application/x-www-form-urlencoded" autocomplete="off">

    <table class="table table-bordered table-hover">

      <thead>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No MR</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">NIK</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Pasien</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">JK</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Umur</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Alamat</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Penjamin Pasien</th>

      </thead>

      <tbody>

        <td><?php echo $value->no_mr?></td>

        <input type="hidden" value="<?php echo $value->no_mr?>" name="noMrHidden" id="noMrHidden">

        <td><?php echo $value->no_ktp?></td>

        <td><?php echo $value->nama_pasien?></td>

        <td align="center"><?php echo $value->jen_kelamin?></td>

        <td><?php echo $value->no_mr?></td>

        <td><?php echo $value->almt_ttp_pasien?></td>

        <td><?php echo $value->nama_perusahaan?></td>

      </tbody>
      <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

    </table>

    <div class="form-group">
      <label class="control-label col-md-2">Tanggal Perjanjian</label>
      <div class="col-md-3">
        <div class="input-group">
            <input name="tgl_lhr" id="tgl_lhr" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm($value->tgl_lhr)?>">
             <span class="input-group-addon">
              <i class="ace-icon fa fa-calendar"></i>
            </span>
            
          </div>
      </div>
      <label class="control-label col-md-1">Jam</label>
      <div class="col-md-2">
        <div class="input-group bootstrap-timepicker">
            <input id="timepicker1" type="text" class="form-control" />
            <span class="input-group-addon">
              <i class="fa fa-clock-o bigger-110"></i>
            </span>
          </div>
      </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label">Poliklinik</label>
        <div class="col-md-5 col-sm-5 col-xs-12">
            <input id="inputKeyPoli" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
            <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Dokter</label>
      <div class="col-md-3">
        <select name="kode_dokter" id="kode_dokter" class="form-control">
          <option value="">-Silahkan Pilih-</option>
        </select>
      </div>
    </div>

    <div class="form-actions center">
      <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>

      <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Submit
      </button>
    </div>

</form>

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/registrasi/daftar_perjanjian.js"></script>


<!-- end form create SEP