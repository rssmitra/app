<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/registrasi/create_sep.js"></script>

<div class="page-header">
  <h1>
    PEMBUATAN SEP
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      Form Pembuatan Surat Eligibilitas Pasien (SEP)
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="col-xs-2">
      <div class="box box-primary">
          <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture">

          <h3 class="profile-username text-center"><div id="nama">Nama Peserta</div></h3>

          <p class="text-muted text-center" id="noKartuFromNik">No Kartu</p>

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <div id="nik">NIK</div>
            </li>
            <li class="list-group-item">
              <div id="tglLahir">Tanggal Lahir</div>
            </li>
            <li class="list-group-item">
              <div id="umur">Umur</div>
            </li>
            <li class="list-group-item">
              <div id="jenisPeserta">Jenis Peserta</div>
            </li>
            <li class="list-group-item">
              <div id="hakKelas">Hak Kelas</div>
            </li>
            <li class="list-group-item">
              <div id="statusPeserta">Status Kepesertaan</div>
            </li>
          </ul>

          <a href="#" class="btn btn-primary btn-block"><b>Selengkapnya</b></a>
        <!-- /.box-body -->
      </div>
    </div>
    <div class="col-xs-10">
      <div class="widget-body">
        <div class="widget-main no-padding">

          <form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'ws_bpjs/ws_index/insertSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
            <br>

            <div style="background-color:rgb(183, 234, 243); padding:5px 5px 2px 5px">
              <div class="form-group">
                <label class="control-label col-md-3">Cari Peserta Berdasarkan</label>
                <div class="col-md-6">
                  <div class="radio">
                        <label>
                          <input name="find_member_by" type="radio" class="ace" value="noKartu" />
                          <span class="lbl"> No Kartu BPS</span>
                        </label>
                        <!-- <label>
                          <input name="find_member_by" type="radio" class="ace" value="sep" />
                          <span class="lbl"> Nomor SEP </span>
                        </label> -->
                        <label>
                          <input name="find_member_by" type="radio" class="ace" value="noRujukan" />
                          <span class="lbl"> Nomor Rujukan </span>
                        </label>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="form-group" id="searchBySEP" style="display:none">
              <label class="control-label col-md-3">Nomor SEP </label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="noSEPVal" name="noSEPVal">
              </div>
              <div class="col-md-2">
                <a href="#" class="btn btn-xs btn-primary" id="btnSearchSep" style="margin-left:-20px">Cari SEP</a>
              </div>
            </div>
            
            <div id="byJenisFaskesId">
              <div class="form-group">
                <label class="control-label col-md-3">Tanggal SEP</label>
                <div class="col-md-3">
                  <div class="input-group">
                      <input name="tglSEP" id="tglSEP" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
                       <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3">Asal Rujukan</label>
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
            </div>

            <div class="form-group" id="noRujukanField" style="display:none">
              <label class="control-label col-md-3">No Rujukan </label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="noRujukanVal" name="noRujukanVal">
              </div>
              <div class="col-md-2">
                <a href="#" class="btn btn-xs btn-primary" id="btnSearchNoRujukan" style="margin-left:-20px">Cari Rujukan</a>
              </div>
            </div>

            <div class="form-group" id="searchByNoKartu" style="display:none">
              <label class="control-label col-md-3">Masukan No Kartu BPJS </label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="noKartu" name="noKartu">
              </div>
              <div class="col-md-2">
                <a href="#" class="btn btn-xs btn-primary" id="btnSearchMember" style="margin-left:-20px">Cari No Kartu</a>
              </div>
            </div>

            <div id="formDetailInsertSEP" style="display:none;padding-top:10px">
            
            <p><b>Form Hasil Pencarian Data</b></p>

              <div class="form-group">
                <label class="control-label col-md-3">Nomor Kartu BPJS </label>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
                </div>
              </div>
              <div class="form-group">
                  <label class="col-md-3 control-label">PPK Asal Rujukan</label>
                  <div class="col-md-5 col-sm-5 col-xs-12">
                      <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" readonly/>
                      <input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
                  </div>
              </div>


              <div class="form-group">
                <label class="control-label col-md-3">No MR </label>
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

              <!-- Form Rujukan, tidak ditampilkan untuk poli IGD -->

              <div id="formRujukan">

                <div class="form-group">
                  <label class="control-label col-md-3">No Rujukan </label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="noRujukan" name="noRujukan" readonly>
                  </div>

                  <label class="control-label col-md-1">Tanggal</label>
                  <div class="col-md-2">
                    <div class="input-group">
                        <input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text" readonly>
                         <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                      </div>
                  </div>

                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Jenis Pelayanan</label>
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
                    <label class="control-label col-md-3">Kelas Rawat</label>
                    <div class="col-md-4">
                        <select name="kelasRawat" id="select_option" class="form-control">
                          <option value="">- Silahkan Pilih -</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis </label>
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
                <div class="form-group">
                  <label class="control-label col-md-3">Dokter DPJP</label>
                  <div class="col-md-5">
                      <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                      <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">No.Surat Kontrol/SKDP</label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="noSuratSKDP" name="noSuratSKDP">
                  </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                        <input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">
                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">No Telp </label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="noTelp" name="noTelp">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Catatan </label>
                  <div class="col-md-6">
                    <textarea class="form-control" name="catatan" id="catatan" style="height:50px !important" placeholder="Masukan catatan"></textarea>
                  </div>
                </div>

              </div>

              <p>Apakah merupakan kasus kecelakaan ?</p>
              <div class="form-group">
                  <label class="control-label col-md-3">Kecelakaan Lalin?</label>
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
                  <label class="control-label col-md-3">Penjamin KLL</label>
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

              <div id="showFormPenjaminKLL" style="display:none;background-color:rgb(183, 234, 243)">
                  
                <div style="padding:10px 10px 10px 10px">

                  <div class="form-group">
                    <label class="control-label col-md-3">Penjamin</label>
                    <div class="col-md-9">

                      <?php echo $this->master->custom_selection_radio($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'penjamin_ws_bpjs') ), '' , 'penjamin', 'penjamin', 'form-control', '', '') ?>

                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3">Tanggal Kejadian</label>
                    <div class="col-md-3">
                      <div class="input-group">
                          <input name="tglKejadian" id="tglKejadian" value="<?php echo isset($value->tglKejadian)?$this->tanggal->formatDateForm($value->tglKejadian):''?>" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
                           <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                        </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3">Provinsi</label>
                    <div class="col-md-4">
                      <?php echo $this->master->custom_selection($params = array('table' => 'provinces', 'id' => 'id', 'name' => 'name', 'where' => array()), isset($value)?$value->kdPropinsi:'' , 'provinceId', 'provinceId', 'form-control', '', '') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3">Kab/Kota</label>
                    <div class="col-md-4">
                      <?php echo $this->master->get_change($params = array('table' => 'regencies', 'id' => 'id', 'name' => 'name', 'where' => array()), isset($value)?$value->kdKabupaten:'' , 'regencyId', 'regencyId', 'form-control', '', '') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3">Kecamatan</label>
                    <div class="col-md-4">
                      <?php echo $this->master->get_change($params = array('table' => 'districts', 'id' => 'id', 'name' => 'name', 'where' => array()), isset($value)?$value->kdKecamatan:'' , 'districtId', 'districtId', 'form-control', '', '') ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3">Keterangan Kejadian </label>
                    <div class="col-md-6">
                      <textarea class="form-control" name="keteranganKejadian" style="height:50px !important" placeholder="Masukan keterangan kejadian"> <?php echo isset($value->keteranganLakaLantas)?$value->keteranganLakaLantas:''?> </textarea>
                    </div>
                  </div>
                  
                </div>

              </div>


              <div class="form-group">
                <label class="control-label col-md-3">Pengguna </label>
                <div class="col-md-2">
                  <input type="text" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3">&nbsp;</label>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary btn-sm">
                        <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                        Submit
                      </button>
                </div>
              </div>

            </div>

          </form>

        </div>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


