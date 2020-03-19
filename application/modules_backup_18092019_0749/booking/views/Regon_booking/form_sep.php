<!-- form create SEP -->
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/registrasi/create_sep.js"></script>

<!-- <div class="page-header">
  <h1>
    Pembuatan SEP
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      Surat Eligibiltas Pasien
    </small>
  </h1>
</div> -->
<!-- /.page-header -->

<form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'ws_bpjs/ws_index/insertSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
    <br>

    <div class="form-group">
      <label class="control-label col-md-2">Tanggal SEP</label>
      <div class="col-md-2">
        <div class="input-group">
            <input name="tglSEP" id="tglSEP" value="<?php echo date('d/m/Y')?>" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
             <span class="input-group-addon">
              <i class="ace-icon fa fa-calendar"></i>
            </span>
            
          </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Cari Peserta Berdasarkan</label>
      <div class="col-md-6">
        <div class="radio">
              <label>
                <input name="find_member_by" type="radio" class="ace" value="noKartu" />
                <span class="lbl"> No Kartu BPS</span>
              </label>
              <label>
                <input name="find_member_by" type="radio" class="ace" value="nik" />
                <span class="lbl"> NIK </span>
              </label>
        </div>
      </div>
    </div>

    <div class="form-group" id="searchByNoKartu" style="display:none">          
     <label class="control-label col-md-2"><b>NO KARTU BPJS</b></label>            
     <div class="col-md-4">            
       <div class="input-group">
         <input type="text" class="form-control" id="noKartu" name="noKartu">
         <span class="input-group-btn">
           <button type="button" class="btn btn-primary btn-sm btnSearchMember">
             <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
             Search
           </button>
         </span>
       </div>
     </div>
   </div>        

   <div class="form-group" id="searchByNik" style="display:none">          
     <label class="control-label col-md-2"><b>NO KTP / NIK</b></label>            
     <div class="col-md-4">            
       <div class="input-group">
         <input type="text" class="form-control" id="noNik" name="noNik">
         <span class="input-group-btn">
           <button type="button" class="btn btn-primary btn-sm btnSearchMember">
             <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
             Search
           </button>
         </span>
       </div>
     </div>
   </div> 

    <!-- <div class="form-group" >
      <label class="control-label col-md-2">No Kartu BPJS </label>
      <div class="col-md-3">
        <input type="text" class="form-control" id="noKartu" name="noKartu">
      </div>
      <div class="col-md-2">
        <a href="#" class="btn btn-xs btn-primary btnSearchMember" style="margin-left:-20px">Search</a>
      </div>
    </div> -->

    <!-- <div class="form-group" id="searchByNik" style="display:none">
      <label class="control-label col-md-2">NIK </label>
      <div class="col-md-3">
        <input type="text" class="form-control" id="noNik" name="noNik">
      </div>
      <div class="col-md-2">
        <a href="#" class="btn btn-xs btn-primary btnSearchMember" style="margin-left:-20px">Search</a>
      </div>
    </div> -->

    <div id="showResultData" style="display:none">
      <div class="form-group">
        <label class="control-label col-md-2">Nomor Kartu BPJS </label>
        <div class="col-md-3">
          <input type="text" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-12">
        <table class="table table-bordered table-hover">
          <thead>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No Kartu</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Peserta</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">NIK</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Tanggal Lahir</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Umur</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Jenis Peserta</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Hak Kelas</th>
            <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Status Kepesertaan</th>
          </thead>
          <tbody>
            <td><div id="noKartuFromNik">No Kartu</div></td>
            <td><div id="nama">Nama Peserta</div></td>
            <td><div id="nik">NIK</div></td>
            <td><div id="tglLahir">Tanggal Lahir</div></td>
            <td><div id="umur">Umur</div></td>
            <td><div id="jenisPeserta">Jenis Peserta</div></td>
            <td><div id="hakKelas">Hak Kelas</div></td>
            <td><div id="statusPeserta">Status Kepesertaan</div></td>
          </tbody>
        </table>
        </div>
      </div>
    </div>
    

    <div class="form-group">
      <label class="control-label col-md-2">No MR </label>
      <div class="col-md-2">
        <input type="text" class="form-control" id="noMR" name="noMR">
      </div>
      
      <div class="col-md-2">
        <div class="checkbox">
          <label>
            <input name="cob" type="checkbox" class="ace" value="1">
            <span class="lbl"> Peserta COB</span>
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Poli </label>
        <div class="col-md-5 col-sm-5 col-xs-12">
            <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" />
            <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">
        </div>

        <div class="col-md-2">
        <div class="checkbox">
          <label>
            <input name="eksekutif" type="checkbox" class="ace" value="1">
            <span class="lbl"> Eksekutif</span>
          </label>
        </div>
      </div>

    </div>

    <!-- Form Rujukan, tidak ditampilkan untuk poli IGD -->
    <div id="formRujukan">
        <div class="form-group">
          <label class="control-label col-md-2">Asal Rujukan</label>
          <div class="col-md-6">
            <div class="radio">
                  <label>
                    <input name="jenis_faskes" type="radio" class="ace" value="1" />
                    <span class="lbl"> Faskes 1 / Puskesmas</span>
                  </label>
                  <label>
                    <input name="jenis_faskes" type="radio" class="ace" value="2" />
                    <span class="lbl"> Faskes 2 / RS </span>
                  </label>
            </div>
          </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">PPK Asal Rujukan</label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
                <input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
            </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Rujukan</label>
          <div class="col-md-2">
            <div class="input-group">
                <input name="tglRujukan" id="tglRujukan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
                 <span class="input-group-addon">
              <i class="ace-icon fa fa-calendar"></i>
            </span>
              </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">No Rujukan </label>
          <div class="col-md-2">
            <input type="text" class="form-control" id="noRujukan" name="noRujukan">
          </div>
        </div>
    
        <div class="form-group">
          <label class="control-label col-md-2">Jenis Pelayanan</label>
          <div class="col-md-6">
            <div class="radio">
                  <label>
                    <input name="jnsPelayanan" type="radio" class="ace" value="1" />
                    <span class="lbl"> Rawat Inap</span>
                  </label>
                  <label>
                    <input name="jnsPelayanan" type="radio" class="ace" value="2" />
                    <span class="lbl"> Rawat Jalan </span>
                  </label>
            </div>
          </div>
        </div>

        <div class="form-group" id="selectKelasRawatInap" style="display:none">
            <label class="control-label col-md-2">Kelas Rawat</label>
            <div class="col-md-4">
                <select name="kelasRawat" id="select_option" class="form-control">
                  <option value="">- Silahkan Pilih -</option>
                </select>
            </div>
        </div>

    </div>
    <div class="form-group">
        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Diagnosa </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" />
            <input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">No Telp </label>
      <div class="col-md-2">
        <input type="text" class="form-control" id="noTelp" name="noTelp">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Catatan </label>
      <div class="col-md-6">
        <textarea class="form-control" name="catatan" style="height:50px !important" placeholder="Masukan catatan"></textarea>
      </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2">Kecelakaan Lalin?</label>
        <div class="col-md-6">
          <div class="radio">
                <label>
                  <input name="lakalantas" type="radio" class="ace" value="1" />
                  <span class="lbl"> Ya</span>
                </label>
                <label>
                  <input name="lakalantas" type="radio" class="ace" value="0" />
                  <span class="lbl"> Tidak </span>
                </label>
          </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-2">Penjamin KLL</label>
        <div class="col-md-6">
          <div class="radio">
                <label>
                  <input name="penjaminKLL" type="radio" class="ace" value="1" />
                  <span class="lbl"> Ya</span>
                </label>
                <label>
                  <input name="penjaminKLL" type="radio" class="ace" value="0" />
                  <span class="lbl"> Tidak </span>
                </label>
          </div>
        </div>
    </div>

    <!-- penjamin kll -->
    <div id="showFormPenjaminKLL" style="display:none">

      <div class="form-group">
        <label class="control-label col-md-2">Penjamin</label>
        <div class="col-md-8">
          <div class="checkbox">
            <label>
              <input name="penjamin" type="checkbox" class="ace" value="1">
              <span class="lbl"> Jasa Raharja</span>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input name="penjamin" type="checkbox" class="ace" value="2">
              <span class="lbl"> BPJS Ketenagakerjaan</span>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input name="penjamin" type="checkbox" class="ace" value="3">
              <span class="lbl"> TASPEN PT</span>
            </label>
          </div>
          <div class="checkbox">
            <label>
              <input name="penjamin" type="checkbox" class="ace" value="4">
              <span class="lbl"> ASABRI PT</span>
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Kejadian</label>
        <div class="col-md-2">
          <div class="input-group">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
              <input name="tglKejadian" id="tglKejadian" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
            </div>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Lokasi Kejadian </label>
        <div class="col-md-4">
          <input type="text" class="form-control" id="lokasiLaka" name="lokasiLaka" value="">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Keterangan Kejadian </label>
        <div class="col-md-6">
          <textarea class="form-control" name="" style="height:50px !important" placeholder="Masukan keterangan kejadian"></textarea>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Pengguna </label>
      <div class="col-md-2">
        <input type="text" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-2">
        &nbsp; <button type="submit" class="btn btn-primary btn-sm">
              <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
              Submit
            </button>
      </div>
    </div>

</form>



<!-- end form create SEP -->