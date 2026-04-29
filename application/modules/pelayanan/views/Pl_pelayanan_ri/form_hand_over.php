<script type="text/javascript">
jQuery(function($) {

    $('#btn_open_handover_modal').click(function(e){
        e.preventDefault();
        $('#modal_handover_form').modal('show');
    });

    $('#btn_save_handover').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: $('#form_pelayanan').attr('action'),
            data: $('#form_pelayanan').serialize(),
            dataType: "json",
            type: "POST",
            complete: function(xhr) {
                var data = xhr.responseText;
                var jsonResponse = JSON.parse(data);
                if(jsonResponse.status === 200){
                    $('#modal_handover_form').modal('hide');
                    $('#btn_form_hand_over').click();
                    $.achtung({message: jsonResponse.message, timeout:5});
                }else{
                    $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                }
                achtungHideLoader();
            }
        });
    });

    /* ================================================================
       AI VOICE ASSISTANT
    ================================================================ */
    var recognition    = null;
    var isRecording    = false;
    var finalTranscript = '';

    // Toggle panel voice
    $('#btn_toggle_voice_ai').click(function(){
        var $panel = $('#vai-wrapper');
        if ($panel.is(':visible')) {
            $panel.slideUp(200);
            $(this).html('<i class="fa fa-microphone"></i> AI Voice Input').removeClass('active');
        } else {
            $panel.slideDown(220);
            $(this).html('<i class="fa fa-microphone"></i> Tutup AI Voice').addClass('active');
            initRecognition();
        }
    });

    // Close voice panel
    $('#vai-close').click(function(){
        stopRecording();
        $('#vai-wrapper').slideUp(200);
        $('#btn_toggle_voice_ai').html('<i class="fa fa-microphone"></i> AI Voice Input').removeClass('active');
    });

    // Reset to record section
    $('#btn_vai_rekam_ulang').click(function(){
        finalTranscript = '';
        $('#vai-transcript').val('');
        $('#vai-preview-section').hide();
        $('#vai-record-section').show();
    });

    function initRecognition() {
        var SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SR) {
            $('#vai-browser-warn').show();
            $('#vai-record-controls').hide();
            return;
        }
        if (recognition) return;

        recognition = new SR();
        recognition.lang            = 'id-ID';
        recognition.continuous      = true;
        recognition.interimResults  = true;
        recognition.maxAlternatives = 1;

        recognition.onstart = function() {
            isRecording = true;
            setRecordingUI(true);
        };

        recognition.onresult = function(event) {
            var interim = '';
            for (var i = event.resultIndex; i < event.results.length; i++) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript + ' ';
                } else {
                    interim += event.results[i][0].transcript;
                }
            }
            $('#vai-transcript').val(finalTranscript + interim);
        };

        recognition.onerror = function(e) {
            if (e.error === 'no-speech') return;
            isRecording = false;
            setRecordingUI(false);
            showVaiAlert('danger', 'Mikrofon error: ' + e.error + '. Pastikan izin mikrofon diizinkan.');
        };

        recognition.onend = function() {
            if (isRecording) {
                // restart supaya tetap merekam (browser membatasi durasi)
                try { recognition.start(); } catch(ex) {}
            } else {
                setRecordingUI(false);
            }
        };
    }

    $('#btn_vai_start').click(function(){
        if (!recognition) { initRecognition(); }
        if (!recognition) return;
        finalTranscript = '';
        $('#vai-transcript').val('');
        isRecording = true;
        try { recognition.start(); } catch(ex) {}
    });

    $('#btn_vai_stop').click(function(){
        stopRecording();
    });

    function stopRecording() {
        isRecording = false;
        if (recognition) { try { recognition.stop(); } catch(ex) {} }
        setRecordingUI(false);
    }

    function setRecordingUI(active) {
        if (active) {
            $('#btn_vai_start').hide();
            $('#btn_vai_stop').show();
            $('#vai-status').html('<span class="vai-pulse"></span> Merekam...');
            $('#vai-transcript').prop('readonly', true);
        } else {
            $('#btn_vai_start').show();
            $('#btn_vai_stop').hide();
            $('#vai-status').html('<i class="fa fa-circle-o" style="color:#bbb"></i> Siap merekam');
            $('#vai-transcript').prop('readonly', false);
        }
    }

    // Proses dengan AI
    $('#btn_vai_process').click(function(){
        var text = $.trim($('#vai-transcript').val());
        if (!text) {
            showVaiAlert('warning', 'Transkripsi kosong. Rekam suara terlebih dahulu atau ketik manual.');
            return;
        }
        stopRecording();

        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses AI...');
        $('#vai-process-alert').hide();

        $.ajax({
            url: 'pelayanan/Pl_ai_assistant/structure_handover',
            type: 'POST',
            dataType: 'json',
            data: { transcription: text },
            success: function(res) {
                if (res.status === 200) {
                    populatePreview(res.data);
                    $('#vai-record-section').hide();
                    $('#vai-preview-section').show();
                } else {
                    showVaiAlert('danger', res.message || 'Gagal memproses AI.');
                }
            },
            error: function() {
                showVaiAlert('danger', 'Koneksi gagal. Coba lagi.');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-magic"></i> Proses dengan AI');
            }
        });
    });

    // Isi form dari preview
    $('#btn_vai_apply').click(function(){
        var getValue = function(name) {
            return $.trim($('#pvw_' + name).val());
        };

        // S
        $('[name="diagnosa_masuk"]').val(getValue('diagnosa_masuk'));
        $('[name="nama_pasien"]').val(getValue('nama_pasien'));
        $('[name="no_rm"]').val(getValue('no_rm'));
        $('[name="umur"]').val(getValue('umur'));
        $('[name="ruangan"]').val(getValue('ruangan'));
        // B
        $('[name="keluhan"]').val(getValue('keluhan'));
        $('[name="riwayat_penyakit"]').val(getValue('riwayat_penyakit'));
        $('[name="alergi"]').val(getValue('alergi'));
        $('[name="terapi_dpjp"]').val(getValue('terapi_dpjp'));
        // A
        var kesadaran = getValue('kesadaran');
        if (kesadaran) $('[name="kesadaran"]').val(kesadaran);
        $('[name="td"]').val(getValue('td'));
        $('[name="nadi"]').val(getValue('nadi'));
        $('[name="nafas"]').val(getValue('nafas'));
        $('[name="suhu"]').val(getValue('suhu'));
        $('[name="fisik"]').val(getValue('fisik'));
        $('[name="lab"]').val(getValue('lab'));
        $('[name="iv_lines"]').val(getValue('iv_lines'));
        $('[name="xray"]').val(getValue('xray'));
        // R
        $('[name="tindakan"]').val(getValue('tindakan'));
        $('[name="instruksi_dokter"]').val(getValue('instruksi_dokter'));

        // Collapse voice panel & scroll to form
        $('#vai-wrapper').slideUp(200);
        $('#btn_toggle_voice_ai').html('<i class="fa fa-microphone"></i> AI Voice Input').removeClass('active');
        $('#modal_handover_form .modal-body').animate({ scrollTop: 300 }, 400);
        $.achtung({ message: 'Data dari AI berhasil dimasukkan ke dalam formulir.', timeout: 4 });
    });

    function populatePreview(data) {
        $.each(data, function(key, val) {
            $('#pvw_' + key).val(val);
        });
    }

    function showVaiAlert(type, msg) {
        $('#vai-process-alert')
            .removeClass('alert-danger alert-warning alert-success')
            .addClass('alert-' + type)
            .html('<i class="fa fa-exclamation-circle"></i> ' + msg)
            .show();
    }

    // Reset saat modal ditutup
    $('#modal_handover_form').on('hidden.bs.modal', function(){
        stopRecording();
        finalTranscript = '';
        recognition = null;
        $('#vai-transcript').val('');
        $('#vai-wrapper').hide();
        $('#vai-preview-section').hide();
        $('#vai-record-section').show();
        $('#btn_toggle_voice_ai').html('<i class="fa fa-microphone"></i> AI Voice Input').removeClass('active');
    });

});
</script>

<style>
    /* ── Hand Over – Professional Theme ── */
    .ho-header-panel {
        background: linear-gradient(135deg, #1a3a5c 0%, #2c6fad 100%);
        color: #fff;
        border: none;
        border-radius: 4px 4px 0 0;
        padding: 10px 15px;
    }
    .ho-header-panel .ho-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
        line-height: 30px;
    }
    .ho-info-box {
        background: #eef4fb;
        border-left: 4px solid #2c6fad;
        border-radius: 3px;
        padding: 10px 14px;
        font-size: 12px;
        color: #2c4f7c;
        margin-bottom: 0;
    }
    .ho-history-header {
        background: #f7f9fb;
        border-bottom: 2px solid #d4dfe9;
        padding: 9px 15px;
    }
    .ho-history-header span {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        color: #2c4f7c;
    }
    .ho-table thead tr th {
        background: #1a3a5c;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        border: none !important;
        padding: 9px 10px;
        vertical-align: middle;
    }
    .ho-table tbody tr:hover { background: #f0f6ff !important; }
    .ho-table tbody td {
        font-size: 12px;
        color: #333;
        vertical-align: middle;
        padding: 8px 10px;
        border-bottom: 1px solid #e8edf3;
    }
    .ho-empty-state {
        text-align: center;
        padding: 24px 0;
        color: #8a9bb0;
        font-size: 13px;
    }
    .ho-empty-state i { font-size: 28px; display: block; margin-bottom: 6px; }

    /* Modal */
    #modal_handover_form .modal-header {
        background: linear-gradient(135deg, #1a3a5c 0%, #2c6fad 100%);
        border-radius: 4px 4px 0 0;
        padding: 12px 16px;
    }
    #modal_handover_form .modal-title {
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.4px;
    }
    #modal_handover_form .modal-header .close {
        color: #fff;
        opacity: 0.8;
        margin-top: 0;
        font-size: 20px;
    }
    #modal_handover_form .modal-header .close:hover { opacity: 1; }
    #modal_handover_form .modal-footer {
        background: #f7f9fb;
        border-top: 1px solid #dce5ef;
    }

    /* AI Voice button */
    #btn_toggle_voice_ai {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 3px;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.45);
        color: #fff;
        cursor: pointer;
        transition: background 0.2s;
        vertical-align: middle;
    }
    #btn_toggle_voice_ai:hover, #btn_toggle_voice_ai.active {
        background: rgba(255,255,255,0.32);
    }

    /* ── Voice AI Wrapper ── */
    #vai-wrapper {
        background: #f0f6ff;
        border: 1px solid #bad4f0;
        border-radius: 5px;
        margin-bottom: 16px;
        overflow: hidden;
    }
    .vai-header {
        background: linear-gradient(90deg, #1565c0 0%, #1976d2 100%);
        color: #fff;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .vai-header .vai-close {
        cursor: pointer;
        opacity: 0.7;
        font-size: 16px;
        line-height: 1;
    }
    .vai-header .vai-close:hover { opacity: 1; }
    .vai-body { padding: 14px 16px; }

    /* Recording controls */
    #vai-status {
        font-size: 12px;
        color: #555;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .vai-pulse {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #e53935;
        animation: vai-blink 1s infinite;
    }
    @keyframes vai-blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.2; }
    }
    #vai-transcript {
        width: 100%;
        min-height: 90px;
        border: 1px solid #b0c4de;
        border-radius: 4px;
        padding: 8px 10px;
        font-size: 12px;
        line-height: 1.6;
        background: #fff;
        resize: vertical;
        color: #333;
    }
    #vai-transcript:focus { outline: none; border-color: #1976d2; box-shadow: 0 0 0 2px rgba(25,118,210,0.15); }
    .vai-hint { font-size: 11px; color: #7a92ab; margin-top: 5px; line-height: 1.5; }
    #vai-process-alert { margin-top: 10px; font-size: 12px; padding: 8px 12px; border-radius: 3px; }

    /* Preview section */
    #vai-preview-section { animation: fadeInDown 0.25s ease; }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .vai-preview-notice {
        background: #e8f5e9;
        border-left: 4px solid #43a047;
        border-radius: 3px;
        padding: 8px 12px;
        font-size: 12px;
        color: #2e7d32;
        margin-bottom: 12px;
    }
    .vai-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 5px 10px;
        border-radius: 3px 3px 0 0;
        margin-bottom: 0;
    }
    .vai-section-s .vai-section-title { background: #e8f4fd; color: #1565c0; border-bottom: 2px solid #1976d2; }
    .vai-section-b .vai-section-title { background: #e8f5e9; color: #2e7d32; border-bottom: 2px solid #388e3c; }
    .vai-section-a .vai-section-title { background: #fff3e0; color: #e65100; border-bottom: 2px solid #ef6c00; }
    .vai-section-r .vai-section-title { background: #fce4ec; color: #ad1457; border-bottom: 2px solid #c2185b; }
    .vai-section-block {
        border: 1px solid #dce5ef;
        border-radius: 4px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .vai-field-table { width: 100%; border-collapse: collapse; }
    .vai-field-table tr + tr td { border-top: 1px solid #eef1f5; }
    .vai-field-table td { padding: 5px 10px; vertical-align: middle; }
    .vai-field-label {
        width: 170px;
        font-size: 11px;
        font-weight: 600;
        color: #4a5568;
        white-space: nowrap;
    }
    .vai-field-input input,
    .vai-field-input select,
    .vai-field-input textarea {
        width: 100%;
        font-size: 12px;
        border: 1px solid #cdd5e0;
        border-radius: 3px;
        padding: 4px 8px;
        color: #333;
        background: #fff;
    }
    .vai-field-input input:focus,
    .vai-field-input select:focus,
    .vai-field-input textarea:focus {
        outline: none;
        border-color: #2c6fad;
        box-shadow: 0 0 0 2px rgba(44,111,173,0.12);
    }
    .vai-field-input textarea { resize: vertical; min-height: 56px; }
    .vai-vital-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .vai-vital-item { display: flex; align-items: center; gap: 5px; }
    .vai-vital-item label { font-size: 11px; font-weight: 600; color: #555; margin: 0; white-space: nowrap; }
    .vai-vital-item input { width: 88px !important; }

    /* SBAR Section Panels */
    .sbar-panel { border: 1px solid #dce5ef; border-radius: 4px; margin-bottom: 14px; }
    .sbar-panel:last-child { margin-bottom: 0; }
    .sbar-panel-heading {
        padding: 8px 14px;
        border-radius: 3px 3px 0 0;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sbar-panel-heading .sbar-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }
    .sbar-s .sbar-panel-heading { background: #e8f4fd; color: #1565c0; border-bottom: 2px solid #1976d2; }
    .sbar-s .sbar-badge { background: #1976d2; }
    .sbar-b .sbar-panel-heading { background: #e8f5e9; color: #2e7d32; border-bottom: 2px solid #388e3c; }
    .sbar-b .sbar-badge { background: #388e3c; }
    .sbar-a .sbar-panel-heading { background: #fff3e0; color: #e65100; border-bottom: 2px solid #ef6c00; }
    .sbar-a .sbar-badge { background: #ef6c00; }
    .sbar-r .sbar-panel-heading { background: #fce4ec; color: #ad1457; border-bottom: 2px solid #c2185b; }
    .sbar-r .sbar-badge { background: #c2185b; }
    .sbar-panel-body { padding: 14px 16px 6px; }
    .sbar-panel-body .form-group { margin-bottom: 12px; }
    .sbar-panel-body label.control-label { font-size: 12px; font-weight: 600; color: #4a5568; padding-top: 7px; }
    .sbar-panel-body .form-control { font-size: 12px; border-color: #cdd5e0; border-radius: 3px; }
    .sbar-panel-body .form-control:focus { border-color: #2c6fad; box-shadow: 0 0 0 2px rgba(44,111,173,0.15); }
    .sbar-panel-body textarea.form-control { resize: vertical; height: 100px !important; }
    .vital-group { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
    .vital-item { display: flex; align-items: center; gap: 5px; }
    .vital-item label { font-size: 11px; font-weight: 600; color: #555; margin: 0; white-space: nowrap; }
    .vital-item .form-control { width: 90px; font-size: 12px; }
</style>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" style="border: none; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
            <div class="ho-header-panel">
                <div class="row">
                    <div class="col-xs-8">
                        <p class="ho-title"><i class="fa fa-exchange"></i>&nbsp; Hand Over Pasien &mdash; Metode SBAR</p>
                    </div>
                    <div class="col-xs-4 text-right" style="padding-top: 2px;">
                        <button type="button" class="btn btn-xs btn-warning" id="btn_open_handover_modal">
                            <i class="fa fa-plus"></i> Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-body" style="padding: 12px 15px 10px;">
                <div class="ho-info-box">
                    <i class="fa fa-info-circle"></i>&nbsp;
                    Klik tombol <strong>Tambah Data</strong> untuk mengisi formulir hand over pasien dalam format <strong>SBAR</strong>
                    (<em>Situation, Background, Assessment, Recommendation</em>).
                </div>
            </div>
        </div>

        <div class="panel panel-default" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-color: #dce5ef;">
            <div class="ho-history-header">
                <span><i class="fa fa-history"></i>&nbsp; Riwayat Hand Over Pasien</span>
            </div>
            <div class="panel-body" style="padding: 0;">
                <table class="table ho-table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th width="36px" class="text-center">No.</th>
                            <th width="140px">Tanggal &amp; Jam</th>
                            <th>Catatan Hand Over</th>
                            <th class="text-center" width="130px">Perawat</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $has_handover = false;
                    $no = 0;
                    if (count($askep) > 0) {
                        foreach($askep as $row){
                            if($row->jenis_catatan == 'hand_over'){
                                $has_handover = true;
                                $no++;
                                echo "<tr>";
                                echo "<td class='text-center'>".$no."</td>";
                                echo "<td><span style='font-size:12px;color:#1a3a5c;font-weight:600;'>".$this->tanggal->formatDateDmy($row->tgl_askep)."</span><br><small class='text-muted'>".$this->tanggal->formatTime($row->jam_askep)."</small></td>";
                                echo "<td><pre style='white-space:pre-wrap;margin:0;border:none;background:transparent;padding:0;font-family:inherit;font-size:12px;line-height:1.6;'>".htmlspecialchars($row->catatan_askep)."</pre></td>";
                                echo "<td class='text-center'><span style='font-size:12px;'>".$row->created_by."</span></td>";
                                echo "</tr>";
                            }
                        }
                    }
                    if (!$has_handover) {
                        echo "<tr><td colspan='4'><div class='ho-empty-state'><i class='fa fa-folder-open-o'></i>Belum ada data hand over tercatat.</div></td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ===== Modal Form Hand Over ===== -->
<div class="modal fade" id="modal_handover_form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border: none; border-radius: 5px; box-shadow: 0 8px 32px rgba(0,0,0,0.18);">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-2px;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="display:inline-block; margin-right:12px;">
                    <i class="fa fa-stethoscope"></i>&nbsp; Formulir Hand Over Pasien &mdash; Metode SBAR
                </h4>
                <button type="button" id="btn_toggle_voice_ai" title="Gunakan asisten suara AI untuk mengisi formulir">
                    <i class="fa fa-microphone"></i> AI Voice Input
                </button>
            </div>

            <div class="modal-body" style="max-height: 74vh; overflow-y: auto; padding: 16px 20px;">

                <!-- ══ AI VOICE ASSISTANT PANEL ══ -->
                <div id="vai-wrapper" style="display:none;">
                    <div class="vai-header">
                        <span><i class="fa fa-microphone"></i>&nbsp; Asisten Voice-to-Form &mdash; AI (Claude)</span>
                        <span class="vai-close" id="vai-close" title="Tutup panel">&times;</span>
                    </div>
                    <div class="vai-body">

                        <!-- STEP 1: Rekam -->
                        <div id="vai-record-section">
                            <div id="vai-browser-warn" class="alert alert-warning" style="display:none; font-size:12px; padding:8px 12px;">
                                <i class="fa fa-exclamation-triangle"></i>
                                Browser Anda tidak mendukung Web Speech API. Gunakan Chrome atau Edge terbaru.
                            </div>

                            <div id="vai-status">
                                <i class="fa fa-circle-o" style="color:#bbb;"></i> Siap merekam
                            </div>

                            <p style="font-size:12px; color:#4a5568; margin-bottom:6px;">
                                <strong>Cara penggunaan:</strong> Bacakan data pasien sesuai urutan label pada formulir
                                (mis. <em>"Diagnosa masuk: demam berdarah dengue. Nama pasien: Ahmad Fauzi. No RM: 001234. Umur: 35 tahun..."</em>).
                                AI akan memetakan setiap informasi ke field yang sesuai.
                            </p>

                            <div id="vai-record-controls">
                                <button type="button" class="btn btn-sm btn-danger" id="btn_vai_start">
                                    <i class="fa fa-microphone"></i> Mulai Rekam
                                </button>
                                <button type="button" class="btn btn-sm btn-default" id="btn_vai_stop" style="display:none;">
                                    <i class="fa fa-stop"></i> Hentikan
                                </button>
                            </div>

                            <textarea id="vai-transcript" placeholder="Transkripsi suara akan muncul di sini secara otomatis, atau ketik manual..."></textarea>
                            <p class="vai-hint">
                                <i class="fa fa-info-circle"></i>
                                Teks transkripsi dapat diedit sebelum diproses AI.
                                Pastikan transkripsi sudah lengkap sebelum menekan tombol proses.
                            </p>

                            <div id="vai-process-alert" class="alert" style="display:none;"></div>

                            <div style="margin-top: 10px; text-align: right;">
                                <button type="button" class="btn btn-sm btn-primary" id="btn_vai_process">
                                    <i class="fa fa-magic"></i> Proses dengan AI
                                </button>
                            </div>
                        </div><!-- /#vai-record-section -->

                        <!-- STEP 2: Preview hasil AI -->
                        <div id="vai-preview-section" style="display:none;">
                            <div class="vai-preview-notice">
                                <i class="fa fa-check-circle"></i>
                                <strong>AI berhasil mengekstrak data.</strong>
                                Periksa dan koreksi hasil di bawah sebelum memasukkan ke formulir.
                            </div>

                            <!-- S -->
                            <div class="vai-section-block vai-section-s">
                                <div class="vai-section-title"><span style="background:#1976d2;color:#fff;border-radius:50%;width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;margin-right:6px;">S</span>Situation</div>
                                <table class="vai-field-table">
                                    <tr>
                                        <td class="vai-field-label">Diagnosa Masuk</td>
                                        <td class="vai-field-input"><input type="text" id="pvw_diagnosa_masuk"></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Nama Pasien</td>
                                        <td class="vai-field-input"><input type="text" id="pvw_nama_pasien"></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">No. Rekam Medis</td>
                                        <td class="vai-field-input"><input type="text" id="pvw_no_rm"></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Umur (Tahun)</td>
                                        <td class="vai-field-input"><input type="text" id="pvw_umur" style="width:100px;"></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Ruang / Kamar</td>
                                        <td class="vai-field-input"><input type="text" id="pvw_ruangan"></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- B -->
                            <div class="vai-section-block vai-section-b">
                                <div class="vai-section-title"><span style="background:#388e3c;color:#fff;border-radius:50%;width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;margin-right:6px;">B</span>Background</div>
                                <table class="vai-field-table">
                                    <tr>
                                        <td class="vai-field-label">Keluhan Saat Ini</td>
                                        <td class="vai-field-input"><textarea id="pvw_keluhan"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Riwayat Penyakit</td>
                                        <td class="vai-field-input"><textarea id="pvw_riwayat_penyakit"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Alergi</td>
                                        <td class="vai-field-input"><input type="text" id="pvw_alergi"></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Terapi dari DPJP</td>
                                        <td class="vai-field-input"><textarea id="pvw_terapi_dpjp"></textarea></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- A -->
                            <div class="vai-section-block vai-section-a">
                                <div class="vai-section-title"><span style="background:#ef6c00;color:#fff;border-radius:50%;width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;margin-right:6px;">A</span>Assessment</div>
                                <table class="vai-field-table">
                                    <tr>
                                        <td class="vai-field-label">Tingkat Kesadaran</td>
                                        <td class="vai-field-input">
                                            <select id="pvw_kesadaran">
                                                <option value="">-- Pilih --</option>
                                                <option value="Compos Mentis">Compos Mentis</option>
                                                <option value="Apatis">Apatis</option>
                                                <option value="Somnolen">Somnolen</option>
                                                <option value="Sopor">Sopor</option>
                                                <option value="Koma">Koma</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Tanda-Tanda Vital</td>
                                        <td class="vai-field-input">
                                            <div class="vai-vital-row">
                                                <div class="vai-vital-item"><label>TD</label><input type="text" id="pvw_td" placeholder="mmHg"></div>
                                                <div class="vai-vital-item"><label>Nadi</label><input type="text" id="pvw_nadi" placeholder="x/mnt"></div>
                                                <div class="vai-vital-item"><label>Nafas</label><input type="text" id="pvw_nafas" placeholder="x/mnt"></div>
                                                <div class="vai-vital-item"><label>Suhu</label><input type="text" id="pvw_suhu" placeholder="°C" style="width:70px;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Pemeriksaan Fisik</td>
                                        <td class="vai-field-input"><textarea id="pvw_fisik"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Hasil Laboratorium</td>
                                        <td class="vai-field-input"><textarea id="pvw_lab"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">IV Lines / Cairan</td>
                                        <td class="vai-field-input"><textarea id="pvw_iv_lines"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Radiologi (X-Ray)</td>
                                        <td class="vai-field-input"><textarea id="pvw_xray"></textarea></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- R -->
                            <div class="vai-section-block vai-section-r">
                                <div class="vai-section-title"><span style="background:#c2185b;color:#fff;border-radius:50%;width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;margin-right:6px;">R</span>Recommendation</div>
                                <table class="vai-field-table">
                                    <tr>
                                        <td class="vai-field-label">Tindakan yang Sudah Dilakukan</td>
                                        <td class="vai-field-input"><textarea id="pvw_tindakan"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td class="vai-field-label">Instruksi Dokter</td>
                                        <td class="vai-field-input"><textarea id="pvw_instruksi_dokter"></textarea></td>
                                    </tr>
                                </table>
                            </div>

                            <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                                <button type="button" class="btn btn-sm btn-default" id="btn_vai_rekam_ulang">
                                    <i class="fa fa-undo"></i> Rekam Ulang
                                </button>
                                <button type="button" class="btn btn-sm btn-success" id="btn_vai_apply">
                                    <i class="fa fa-check-circle"></i> Masukan ke Formulir
                                </button>
                            </div>
                        </div><!-- /#vai-preview-section -->

                    </div><!-- /.vai-body -->
                </div><!-- /#vai-wrapper -->

                <!-- ══ FORM SBAR ══ -->

                <!-- S – Situation -->
                <div class="sbar-panel sbar-s">
                    <div class="sbar-panel-heading">
                        <span class="sbar-badge">S</span> Situation &mdash; Kondisi Saat Ini
                    </div>
                    <div class="sbar-panel-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Diagnosa Masuk <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="diagnosa_masuk" class="form-control" placeholder="Masukkan diagnosa masuk">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Nama Pasien <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" name="nama_pasien" class="form-control" value="<?php echo isset($value->nama_pasien) ? htmlspecialchars($value->nama_pasien) : ''; ?>" placeholder="Nama lengkap pasien">
                            </div>
                            <label class="control-label col-sm-1" style="text-align:right;">No. RM</label>
                            <div class="col-sm-2">
                                <input type="text" name="no_rm" class="form-control" value="<?php echo isset($no_mr) ? htmlspecialchars($no_mr) : ''; ?>" placeholder="No. RM">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Umur</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="number" name="umur" class="form-control" placeholder="0" min="0">
                                    <span class="input-group-addon" style="font-size:11px;padding:6px 8px;">Thn</span>
                                </div>
                            </div>
                            <label class="control-label col-sm-2" style="text-align:right;">Ruang / Kamar</label>
                            <div class="col-sm-5">
                                <input type="text" name="ruangan" class="form-control" placeholder="Nama ruang / nomor kamar">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- B – Background -->
                <div class="sbar-panel sbar-b">
                    <div class="sbar-panel-heading">
                        <span class="sbar-badge">B</span> Background &mdash; Latar Belakang Klinis
                    </div>
                    <div class="sbar-panel-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Keluhan Saat Ini</label>
                            <div class="col-sm-9">
                                <textarea name="keluhan" class="form-control" rows="3" placeholder="Keluhan utama pasien saat ini"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Riwayat Penyakit</label>
                            <div class="col-sm-9">
                                <textarea name="riwayat_penyakit" class="form-control" rows="3" placeholder="Riwayat penyakit yang relevan"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Alergi</label>
                            <div class="col-sm-9">
                                <input type="text" name="alergi" class="form-control" placeholder="Obat / makanan / zat yang menimbulkan alergi (tulis &quot;Tidak Ada&quot; jika nihil)">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Terapi dari DPJP</label>
                            <div class="col-sm-9">
                                <textarea name="terapi_dpjp" class="form-control" rows="3" placeholder="Terapi atau instruksi DPJP"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- A – Assessment -->
                <div class="sbar-panel sbar-a">
                    <div class="sbar-panel-heading">
                        <span class="sbar-badge">A</span> Assessment &mdash; Penilaian Klinis
                    </div>
                    <div class="sbar-panel-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Tingkat Kesadaran</label>
                            <div class="col-sm-4">
                                <select name="kesadaran" class="form-control">
                                    <option value="Compos Mentis">Compos Mentis</option>
                                    <option value="Apatis">Apatis</option>
                                    <option value="Somnolen">Somnolen</option>
                                    <option value="Sopor">Sopor</option>
                                    <option value="Koma">Koma</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Tanda-Tanda Vital</label>
                            <div class="col-sm-9">
                                <div class="vital-group">
                                    <div class="vital-item"><label>TD</label><input type="text" name="td" class="form-control" placeholder="mmHg" style="width:90px;"></div>
                                    <div class="vital-item"><label>Nadi</label><input type="text" name="nadi" class="form-control" placeholder="x/mnt" style="width:80px;"></div>
                                    <div class="vital-item"><label>Nafas</label><input type="text" name="nafas" class="form-control" placeholder="x/mnt" style="width:80px;"></div>
                                    <div class="vital-item"><label>Suhu</label><input type="text" name="suhu" class="form-control" placeholder="°C" style="width:70px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Pemeriksaan Fisik</label>
                            <div class="col-sm-9">
                                <textarea name="fisik" class="form-control" rows="3" placeholder="Ringkasan hasil pemeriksaan fisik"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Hasil Laboratorium</label>
                            <div class="col-sm-9">
                                <textarea name="lab" class="form-control" rows="3" placeholder="Hasil laboratorium yang penting / bermakna klinis"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">IV Lines / Cairan</label>
                            <div class="col-sm-9">
                                <textarea name="iv_lines" class="form-control" rows="3" placeholder="Terapi cairan, akses IV, dan infus yang sedang berjalan"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Radiologi (X-Ray / CT)</label>
                            <div class="col-sm-9">
                                <textarea name="xray" class="form-control" rows="3" placeholder="Hasil pemeriksaan radiologi yang relevan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- R – Recommendation -->
                <div class="sbar-panel sbar-r" style="margin-bottom: 0;">
                    <div class="sbar-panel-heading">
                        <span class="sbar-badge">R</span> Recommendation &mdash; Rekomendasi &amp; Tindak Lanjut
                    </div>
                    <div class="sbar-panel-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Tindakan yang Sudah Dilakukan</label>
                            <div class="col-sm-9">
                                <textarea name="tindakan" class="form-control" rows="4" placeholder="Uraikan tindakan yang telah dikerjakan pada pasien"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3">Instruksi Dokter</label>
                            <div class="col-sm-9">
                                <textarea name="instruksi_dokter" class="form-control" rows="4" placeholder="Instruksi atau rencana tindak lanjut dari dokter yang harus dilaksanakan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-sm btn-primary" id="btn_save_handover">
                    <i class="fa fa-save"></i> Simpan Data Hand Over
                </button>
            </div>
        </div>
    </div>
</div>
