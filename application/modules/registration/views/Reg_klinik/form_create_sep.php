<div id="formDetailInsertSEP">
    
    <p><b>HASIL PENCARIAN NOMOR RUJUKAN</b></p>

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
        
        <div class="col-md-7">
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
                <input type="text" class="form-control" id="noRujukanView" name="noRujukan" readonly>
            </div>

            <label class="control-label col-md-2">Tanggal</label>
            <div class="col-md-2">
                <div class="input-group">
                    <input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
                </span>
                </div>
            </div>

        </div>

        

        <div class="form-group">
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa </label>
            <div class="col-md-8 col-sm-8 col-xs-12">
                <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" style="text-transform: uppercase" readonly/>
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
            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis </label>
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" readonly/>
                <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">
            </div>

            <div class="col-md-3">
            <div class="checkbox">
                <label>
                <input name="eksekutif" type="checkbox" class="ace" value="1">
                <span class="lbl"> Eksekutif</span>
                </label>
            </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3">Dokter DPJP </label>
            <div class="col-md-6">
            <input id="show_dpjp" class="form-control" name="show_dpjp" type="text" readonly/>
            </div>
        </div>
        
        <div id="message-result"></div>

        <div class="form-group">
            <label class="col-md-3">&nbsp;</label>
            <div class="col-md-6">
                <button type="button" id="btnCreateSep" class="btn btn-inverse btn-sm">
                    <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                    Buat SEP
                </button>
            </div>
        </div>

    </div>
</div>