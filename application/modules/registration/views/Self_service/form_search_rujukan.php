<section class="content-white testimonial" style="padding-bottom: 20px">
	<div class="container">
        <div class="facts-area" id="resultSearchKodeBooking" style="display: none">
            <div class="container">
                <style>
                    
                </style>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="section-title" style="padding-bottom: 25px !important">
                            <h2>Masukan Nomor Rujukan Puskesmas</h2>
                            <span></span>
                            <p>
                                Masukan Nomor Rujukan dari Puskesmas untuk mencetak Surat Eligibiltas Pasien (SEP)
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12  col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                        <div class="subscribe-content-area">
                            <form action="#">
                                <ul>
                                    <li>
                                        <div class="subscribe-field">
                                            <div class="form-group">
                                                <input type="text" placeholder="Masukan Nomor Rujukan Puskesmas" class="input-field" style="font-size:18px;">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="subscribe-btn" style="padding-left: 15px">
                                            <a href="#!" class="green-btn">
                                                Cari Data
                                                <img src="<?php echo base_url()?>assets/kiosk/symbols2.png" alt="symbol2">
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row" id="result-dt-rujukan" style="padding-top: 20px">
                    
                    <div class="col-md-3">
                        <div class="box box-primary">

                            <ul class="list-group list-group-unbordered">

                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">No Kartu BPJS : </small> <div id="noKartuFromNik">-</div>
                                </li>

                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">Nama Peserta : </small> <div id="nama">-</div>
                                </li>

                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">NIK : </small> <div id="nik">-</div>
                                </li>
                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">Tanggal Lahir : </small> <div id="tglLahir">-</div>
                                </li>
                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">Umur : </small> <div id="umur_p_bpjs">-</div>
                                </li>
                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">Jenis Peserta : </small> <div id="jenisPeserta">-</div>
                                </li>
                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">Hak Kelas : </small> <div id="hakKelas">-</div>
                                </li>
                                <li class="list-group-item">
                                <small style="color: blue; font-weight: bold; font-size:11px">Status Kepesertaan : </small> <div id="statusPeserta">-</div>
                                </li>
                            </ul>

                        </div>
                    </div>

                    <div class="col-sm-9 col-md-9 col-lg-9">
                        <div class="contact-info-right" style="padding-top: 0px !important">
                            
                            <div class="contact-area-contact-field" style="padding-top: 0px !important;">
                                <form action="#">
                                    <!-- form hidden -->
                                    <input name="tglSEP" id="tglSEP" value="<?php echo date('m/d/Y')?>" placeholder="mm/dd/YYYY" class="form-control date-picker" type="hidden">
                                    <input name="jenis_faskes" type="hidden" class="ace" value="1" checked/>
                                    <input type="hidden" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
                                    <input name="jnsPelayanan" type="hidden" class="ace" value="2" checked/>
                                    <input name="lakalantas" type="hidden" class="ace" value="0" checked/>
                                    <input name="penjaminKLL" type="hidden" class="ace" value="0" checked/>
                                    <input type="hidden" class="form-control" name="catatan" id="catatan" value="">
                                    <input type="hidden" class="form-control" id="noSuratSKDP" name="noSuratSKDP" value="">
                                    <input type="hidden" class="form-control" id="user" name="user" value="" readonly>
                                    <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="hidden" placeholder="Masukan keyword minimal 3 karakter" />
                                    <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">
                                    <input type="hidden" class="form-control" id="noRujukan" name="noRujukan" readonly>
                                    <input name="eksekutif" type="hidden" class="ace" value="0">
                                    <input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="hidden" readonly>
                                    
                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="single-form-field">
                                                <label>PPK Asal Rujukan</label>
                                                <div class="form-group">
                                                    <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" readonly/>
                                                    <input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="single-form-field">
                                                <label>Spesialis/SubSpesialis</label>
                                                <div class="form-group">
                                                    <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" readonly/>
                                                    <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">	
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="single-form-field">
                                                <label>Dokter DPJP</label>
                                                <div class="form-group">
                                                    <input id="show_dpjp" class="form-control" name="show_dpjp" type="text" readonly/>	
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="single-form-field">
                                                <label>No. Telp/Hp</label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="noTelp" name="noTelp">	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="single-form-field">
                                                <label>Diagnosa Awal</label>
                                                <div class="form-group">
                                                    <input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">
                                                            
                                                    <textarea id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" style="text-transform: uppercase" readonly></textarea>
                                                </div>
                                                <a href="#!" class="seo-btn">
                                                    PROSES PENDAFTARAN 
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
	</div>
</section>