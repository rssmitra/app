<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/registrasi/create_sep.js"></script>

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
      <div class="col-xs-2">
        <div class="box box-primary">
            <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture">

            <h3 class="profile-username text-center"><div id="nama"><?php echo ($value->nama)?$value->nama:''?></div></h3>

            <p class="text-muted text-center" id="noKartuFromNik"><?php echo ($value->noKartu)?$value->noKartu:''?></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div id="nik"><?php echo ($peserta->nik)?$peserta->nik:''?></div>
              </li>
              <li class="list-group-item">
                <div id="tglLahir"><?php echo ($peserta->tglLahir)?$peserta->tglLahir:''?></div>
              </li>
              <li class="list-group-item">
                <div id="umur"><?php echo ($peserta->umur->umurSaatPelayanan)?$peserta->umur->umurSaatPelayanan:''?></div>
              </li>
              <li class="list-group-item">
                <div id="jenisPeserta"><?php echo ($peserta->jenisPeserta->keterangan)?$peserta->jenisPeserta->keterangan:''?></div>
              </li>
              <li class="list-group-item">
                <div id="hakKelas"><?php echo ($peserta->hakKelas->keterangan)?$peserta->hakKelas->keterangan:''?></div>
              </li>
              <li class="list-group-item">
                <div id="statusPeserta"><?php echo ($peserta->statusPeserta->keterangan)?$peserta->statusPeserta->keterangan:''?></div>
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

              <div class="form-group">
                <label class="control-label col-md-3">Tanggal SEP</label>
                <div class="col-md-3">
                  <div class="input-group">
                      <input name="tglSEP" id="tglSEP" value="<?php echo $this->tanggal->formatDateForm($value->tglSep)?>" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
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
                          <input name="jenis_faskes" type="radio" class="ace" value="1" <?php echo ($value->asalRujukan==1)?'checked':''?> />
                          <span class="lbl"> Faskes 1 / Puskesmas</span>
                        </label>
                        <label>
                          <input name="jenis_faskes" type="radio" class="ace" value="2" <?php echo ($value->asalRujukan==2)?'checked':''?>/>
                          <span class="lbl"> Faskes 2 / RS </span>
                        </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3">Cari Peserta Berdasarkan</label>
                <div class="col-md-6">
                  <div class="radio">
                        <label>
                          <input name="find_member_by" type="radio" class="ace" value="noKartu" <?php echo ($value->find_member_by=='noKartu')?'checked':''?> />
                          <span class="lbl"> No Kartu BPS</span>
                        </label>
                        <label>
                          <input name="find_member_by" type="radio" class="ace" value="noRujukan" <?php echo ($value->find_member_by=='noRujukan')?'checked':''?>/>
                          <span class="lbl"> Nomor Rujukan </span>
                        </label>
                  </div>
                </div>
              </div>

              <div class="form-group" id="noRujukanField" <?php echo ($value->find_member_by=='noRujukan')?'':'style="display:none"'?> >
                <label class="control-label col-md-3">No Rujukan </label>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="noRujukanVal" name="noRujukanVal" value="<?php echo ($value->noRujukan)?$value->noRujukan:''?>">
                </div>
                <div class="col-md-2">
                  <a href="#" class="btn btn-xs btn-primary" id="btnSearchNoRujukan" style="margin-left:-20px">Cari Rujukan</a>
                </div>
              </div>

              <div class="form-group" id="searchByNoKartu" <?php echo ($value->find_member_by=='noKartu')?'':'style="display:none"'?> >
                <label class="control-label col-md-3">No Kartu BPJS </label>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="noKartu" name="noKartu" value="<?php echo ($value->noKartu)?$value->noKartu:''?>">
                </div>
                <div class="col-md-2">
                  <a href="#" class="btn btn-xs btn-primary btnSearchMember" style="margin-left:-20px">Cari No Kartu</a>
                </div>
              </div>

              <div id="showResultData">

                <div class="form-group">
                  <label class="control-label col-md-3">Nomor Kartu BPJS </label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly value="<?php echo ($value->noKartu)?$value->noKartu:''?>" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Nomor SEP </label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" id="noSep" name="noSep" readonly value="<?php echo ($value->noSep)?$value->noSep:''?>" >
                  </div>
                </div>
              </div>
              <br>
              <div id="formDetailInsertSEP">

                <div class="form-group">
                  <label class="control-label col-md-3">No MR </label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="noMR" name="noMR" value="<?php echo ($value->noMr)?$value->noMr:''?>" >
                  </div>
                  
                  <div class="col-md-2">
                    <div class="checkbox">
                      <label>
                        <input name="cob" type="checkbox" class="ace" value="<?php echo ($value->cob)?$value->cob:''?>">
                        <span class="lbl"> Peserta COB</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis </label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo ($value->poli)?$value->poli:''?>"/>
                        <input type="hidden" name="kodePoliHidden" value="<?php echo ($value->kodePoli)?$value->kodePoli:''?>" id="kodePoliHidden">
                    </div>

                    <div class="col-md-2">
                    <div class="checkbox">
                      <label>
                        <input name="eksekutif" type="checkbox" class="ace" value="<?php echo ($value->poliEksekutif)?$value->poliEksekutif:''?>">
                        <span class="lbl"> Eksekutif</span>
                      </label>
                    </div>
                  </div>

                </div>

                <!-- Form Rujukan, tidak ditampilkan untuk poli IGD -->
                <div id="formRujukan">
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">PPK Asal Rujukan</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo ($peserta->provUmum->nmProvider)?$peserta->provUmum->nmProvider:''?>" readonly/>
                            <input type="hidden" name="kodeFaskesHidden" id="kodeFaskesHidden" value="<?php echo ($peserta->provUmum->kdProvider)?$peserta->provUmum->kdProvider:''?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3">Tanggal Rujukan</label>
                      <div class="col-md-3">
                        <div class="input-group">
                            <input name="tglRujukan" id="tglKunjungan" value="<?php echo ($value->tglRujukan)?$this->tanggal->formatDateForm($value->tglRujukan):''?>" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
                             <span class="input-group-addon">
                          <i class="ace-icon fa fa-calendar"></i>
                        </span>
                          </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3">No Rujukan </label>
                      <div class="col-md-3">
                        <input type="text" class="form-control" id="noRujukan" name="noRujukan" value="<?php echo ($value->noRujukan)?$value->noRujukan:''?>" readonly >
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3">No.Surat Kontrol/SKDP</label>
                      <div class="col-md-3">
                        <input type="text" class="form-control" id="noSuratSKDP" name="noSuratSKDP" value="<?php echo ($value->noSuratSKDP)?$value->noSuratSKDP:''?>" >
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="control-label col-md-3">Dokter DPJP</label>
                      <div class="col-md-5">
                          <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo ($value->namaDokterDPJP)?$value->namaDokterDPJP:''?>" />
                          <input type="hidden" name="KodedokterDPJP" value="<?php echo ($value->kodeDokterDPJP)?$value->kodeDokterDPJP:''?>" id="KodedokterDPJP">
                      </div>
                    </div>
                
                    <div class="form-group">
                      <label class="control-label col-md-3">Jenis Pelayanan</label>
                      <div class="col-md-6">
                        <div class="radio">
                            <label>
                              <input name="jnsPelayanan" type="radio" class="ace" value="1" <?php echo ($value->jnsPelayanan=='R.Inap')?'checked':''?> />
                              <span class="lbl"> Rawat Inap</span>
                            </label>
                            <label>
                              <input name="jnsPelayanan" type="radio" class="ace" value="2" <?php echo ($value->jnsPelayanan=='R.Jalan')?'checked':''?>/>
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

                </div>

                <div class="form-group">
                    <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo ($value->diagnosa)?$value->diagnosa:''?>" />
                        <input type="hidden" name="kodeDiagnosaHidden" value="<?php echo ($value->kodeDiagnosa)?$value->kodeDiagnosa:''?>" id="kodeDiagnosaHidden">
                    </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">No Telp </label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="noTelp" name="noTelp" value="<?php echo ($peserta->mr->noTelepon)?$peserta->mr->noTelepon:''?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3">Catatan </label>
                  <div class="col-md-6">
                    <textarea class="form-control" name="catatan" id="catatan" style="height:50px !important" placeholder="Masukan catatan"><?php echo ($value->catatan)?$value->catatan:''?></textarea>
                  </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3">Kecelakaan Lalin?</label>
                    <div class="col-md-6">
                      <div class="radio">
                        <label>
                          <input name="lakalantas" type="radio" class="ace" value="1" <?php echo ($value->lakalantas==1)?'checked':''?> />
                          <span class="lbl"> Ya</span>
                        </label>
                        <label>
                          <input name="lakalantas" type="radio" class="ace" value="0" <?php echo ($value->lakalantas==0)?'checked':''?> />
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
                    <div class="col-md-10">

                      <?php echo $this->master->custom_selection_checkbox($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'penjamin_ws_bpjs') ), '' , 'penjamin', 'penjamin', 'form-control', '', '') ?>

                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3">Tanggal Kejadian</label>
                    <div class="col-md-2">
                      <div class="input-group">
                          <input name="tglKejadian" id="tglKejadian" value="<?php echo ($value->tglKejadian)?$this->tanggal->formatDateForm($value->tglKejadian):''?>" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
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
                      <textarea class="form-control" name="keteranganKejadian" style="height:50px !important" placeholder="Masukan keterangan kejadian"> <?php echo ($value->keteranganLakaLantas)?$value->keteranganLakaLantas:''?> </textarea>
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
                    <input type="hidden" name="proses" value="update">
                    <button type="submit" class="btn btn-primary btn-sm">
                      <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                      Submit
                    </button>
                  </div>
                </div>

              </div>

              <h4>Keterangan : </h4>

              Fungsi : Insert SEP <br>

              Method : POST <br>

              Format : Json <br>

              Content-Type: Application/x-www-form-urlencoded <br>

            </form>
          </div>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


