<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">
    
    oTable = $('#dt_add_resep_obat').DataTable({ 
            
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "ordering": false,
        "searching": false,
        "bPaginate": false,
        "bInfo": false,
        "pageLength": 25,
        "ajax": {
            "url": "pelayanan/Pl_pelayanan/get_cart_resep/"+$('#no_kunjungan').val()+"",
            "type": "POST"
        }
    });

    var minutesCount = 0; 
    var secondCount = 0; 
    var centiSecondCount = 0;
    var minutes = document.getElementById("minutes");
    var second = document.getElementById("second");
    var centiSecond = document.getElementById("centiSecond");

    $('#pl_diagnosa').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getICD10",
                data: 'keyword=' + query,            
                dataType: "json",
                type: "POST",
                success: function (response) {
                result($.map(response, function (item) {
                        return item;
                    }));
                
                }
            });
        },
        afterSelect: function (item) {
        // do what is needed with item
        var label_item=item.split(':')[1];
        var val_item=item.split(':')[0];
        console.log(val_item);
        $('#pl_diagnosa').val(label_item);
        $('#pl_diagnosa_hidden').val(val_item);
        }

    });

    <?php for ( $ix = 0; $ix < 30; $ix++) :?>
        $('#keyword_obat<?php echo $ix?>').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "templates/references/getObatByBagianAutoComplete",
                    data: { keyword:query, bag: '060101'},            
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                    }
                });
            },
            afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            var label_item=item.split(':')[1];
            console.log(val_item);
            $('#keyword_obat<?php echo $ix?>').val(label_item);

            }
        });
    <?php endfor; ?>

    $("#check_resep").change(function() {
        if(this.checked) {
            $('#form_input_resep').show();
        }else{
            $('#form_input_resep').hide();
        }
    });

    counterfile = <?php $j=2;echo $j.";";?>

    function hapus_file(a, b)
    {
        preventDefault();
        if(b != 0){
            /*$.getJSON("<?php echo base_url('posting/delete_file') ?>/" + b, '', function(data) {
                document.getElementById("file"+a).innerHTML = "";
                greatComplate(data);
            });*/
        }else{
            y = a ;
            document.getElementById("file"+a).innerHTML = "";
        }
    }

    function tambah_file()
    {
        preventDefault();
        counternextfile = counterfile + 1;
        counterIdfile = counterfile + 1;
        if(counternextfile > 3){
            var marginTop = 'style="margin-top:5px"';
        }else{
            var marginTop = '';
        }
        var html = "<div id=\"file"+counternextfile+"\" class='clonning_form'>\
                        <table "+marginTop+">\
                            <tr>\
                                <td><input type='text' class='inputKeyObat form-control' name='keyword_obat[]' id='keyword_obat"+counternextfile+"' placeholder='Masukan keyword obat' value='' style='width:330px'></td>\
                                <td><input type='text' class='form-control' name='dosis' id='dosis' value=''  placeholder='EX. 3 x 1' style='width: 94px;margin-left: 10px;'></td>\
                                <td><input type='text' class='form-control' name='jumlah_obat' id='jumlah_obat' value=''  placeholder='ex. 10 TAB' style='text-transform: uppercase; width: 94px;margin-left: 10px;'></td>\
                                <td>\
                                <a style='margin-left: 4px' href='#' class='btn btn-xs btn-primary' onClick='tambah_file()'><i class='fa fa-plus'></i></a>\
                                <a href='#' onclick='hapus_file("+counternextfile+",0)' class='btn btn-xs btn-danger'><i class='fa fa-times'></i></a>\
                                </td>\
                            </tr>\
                        </table>\
                    </div>\
                    <div id=\"input_file"+counternextfile+"\"></div>";

        document.getElementById("input_file"+counterfile).innerHTML = html;
        counterfile++;
    }

    $('#callPatient').click(function (e) {  
      e.preventDefault();
      playAudio(1, 2);
      var params = {
        no_kunjungan : $('#no_kunjungan').val(),
        dokter : $('#kode_dokter_poli').val(),
        poli : $('#kode_bagian_val').val(),
      };
      $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/callPatient') ?>", params , function (response) {      
      })
    });

    $('#btn_submit_racikan, #btn_update_header_racikan').click(function (e) {  
      e.preventDefault();
      var formData = {
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_brg : '0',
            nama_brg : $('#nama_racikan').val(),
            jml_pesan : $('#jml_racikan').val(),
            satuan_obat : $('#satuan_racikan').val(),
            no_mr : $('#no_mr_resep').val(),
            jml_dosis : $('#dosis_start_r').val(),
            jml_dosis_obat : $('#dosis_end_r').val(),
            aturan_pakai : $('#anjuran_pakai_r').val(),
            keterangan : $('#catatan_r').val(),
            jml_hari : 0,
            tipe_obat : 'racikan',
            parent : '0',
        };
        $.ajax({
            url: "pelayanan/Pl_pelayanan/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  

                    oTable.ajax.url("pelayanan/Pl_pelayanan/get_cart_resep/"+$('#no_kunjungan').val()+"").load();

                    $('#btn_submit_racikan').hide();
                    $('#btn_update_header_racikan').show();
                    $('#data_obat_div').show();
                    $('#add_komposisi_obat').val(jsonResponse.newId);

                    // reset form
                    reset_form_resep();
                }else{          
                    $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                } 
                achtungHideLoader();
            }
        });
      
    });

    $('#add_komposisi_obat').click(function (e) {  
        e.preventDefault();
        var formData = {
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_brg : $('#inputKeyObatRacikanHidden').val(),
            nama_brg : $('#inputKeyObatRacikan').val(),
            jml_pesan : $('#jml_komposisi_obat').val(),
            satuan_obat : $('#satuan_racikan').val(),
            no_mr : $('#no_mr_resep').val(),
            jml_dosis : 0,
            jml_dosis_obat : 0,
            aturan_pakai : '',
            keterangan : '',
            jml_hari : 0,
            tipe_obat : 'racikan',
            parent : $(this).val(),
        };
        $.ajax({
            url: "pelayanan/Pl_pelayanan/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  

                    oTable.ajax.url("pelayanan/Pl_pelayanan/get_cart_resep/"+$('#no_kunjungan').val()+"").load();

                    $('#btn_submit_racikan').hide();
                    $('#btn_update_header_racikan').show();
                    $('#data_obat_div').show();
                    // reset form
                    reset_form_komposisi();
                    // show confirm
                    if(confirm('Apakah anda ingin menambah komposisi obat?')){
                        $('#inputKeyObatRacikan').focus();
                    }else{
                        return false;
                    }
                }else{          
                    $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                } 
                achtungHideLoader();
            }
        });
    });

    $('#inputKeyObat').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getObatByBagianAutoComplete",
                data: { keyword:query, bag: '060101'},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                  result($.map(response, function (item) {
                      return item;
                  }));
                }
            });
        },
        afterSelect: function (item) {
          // do what is needed with item
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#kode_brg_obat').val(val_item);
          $('#inputKeyObat').val(label_item);
          $('#jml_dosis').focus();

        }
    });

    function add_resep_obat(){

        preventDefault();  
        var formData = {
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_brg : $('#kode_brg_obat').val(),
            nama_brg : $('#inputKeyObat').val(),
            jml_hari : $('#jml_hari').val(),
            jml_pesan : $('#jml_pesan').val(),
            jml_dosis : $('#jml_dosis').val(),
            jml_dosis_obat : $('#jml_dosis_obat').val(),
            satuan_obat : $('#satuan_obat').val(),
            aturan_pakai : $('#aturan_pakai').val(),
            no_mr : $('#no_mr_resep').val(),
            keterangan : $('#keterangan_resep').val(),
            tipe_obat : 'non_racikan',
            parent : '0',
        };
        $.ajax({
            url: "pelayanan/Pl_pelayanan/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  

                    oTable.ajax.url("pelayanan/Pl_pelayanan/get_cart_resep/"+$('#no_kunjungan').val()+"").load();

                    $('#inputKeyObat').focus();
                    // reset form
                    reset_form_resep();
                }else{          
                    $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});  
                } 
                achtungHideLoader();
            }
        });

    }

    $( "#keterangan_resep" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#btn_add_resep_obat').click();            
          }          
          return false;                 
        }    
    });

    $( "#inputKeyObat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_dosis').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_dosis" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_dosis_obat').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_dosis_obat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#satuan_obat').focus();            
          }          
          return false;                 
        }    
    });

    $( "#satuan_obat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#aturan_pakai').focus();            
          }          
          return false;                 
        }    
    });

    $( "#aturan_pakai" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_hari').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_hari" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_pesan').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_pesan" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#keterangan_resep').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_komposisi_obat" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#add_komposisi_obat').click();            
          }          
          return false;                 
        }    
    });

    $('#inputKeyObatRacikan').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getObatByBagianAutoComplete",
              data: { keyword:query, bag: '060101', urgensi: 'biasa' },            
              dataType: "json",
              type: "POST",
              success: function (response) {
                result($.map(response, function (item) {
                    return item;
                }));
              }
          });
      },
      afterSelect: function (item) {
        // do what is needed with item
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#inputKeyObatRacikan').val(label_item);
        $('#inputKeyObatRacikanHidden').val(val_item);
        $('#jml_komposisi_obat').focus();
      }
    });

    function reset_form_resep(){
        preventDefault();
        var jml_hari = ( $('#kode_perusahaan_val').val() == 120 ) ? 30 : "";
        $('#id_pesan_resep_detail').val("");
        $('#kode_brg_obat').val("");
        $('#inputKeyObat').val("");
        $('#jml_pesan').val("");
        $('#jml_hari').val(jml_hari);
        $('#jml_dosis').val("");
        $('#jml_dosis_obat').val("");
        $('#satuan_obat').val("Tab");
        $('#aturan_pakai').val("Sesudah Makan");
        $('#keterangan_resep').val("");
    }

    function reset_form_komposisi(){
        preventDefault();
        $('#id_pesan_resep_detail').val("");
        $('#inputKeyObatRacikan').val("");
        $('#inputKeyObatRacikanHidden').val("");
        $('#jml_komposisi_obat').val("");
    }

    function reset_form_racikan(){
        preventDefault();
        $('#nama_racikan').val("");
        $('#jml_racikan').val("");
        $('#satuan_racikan').val("");
        $('#dosis_start_r').val("");
        $('#dosis_end_r').val("");
        $('#anjuran_pakai_r').val("");
        $('#catatan_r').val("");
        $('#id_pesan_resep_detail').val("");
        $('#inputKeyObatRacikan').val("");
        $('#inputKeyObatRacikanHidden').val("");

        $('#btn_submit_racikan').show();
        $('#btn_update_header_racikan').hide();
        $('#data_obat_div').hide();
        $('#add_komposisi_obat').val("");

    }

    function clickedit(id){
        preventDefault();
        activaTab('resep_non_racikan_tab');
        $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/getrowresep') ?>", {ID: id} , function (response) {      
            console.log(response);
            $('#id_pesan_resep_detail').val(response.id);
            $('#kode_brg_obat').val(response.kode_brg);
            $('#inputKeyObat').val(response.nama_brg);
            $('#jml_hari').val(response.jml_hari);
            $('#jml_pesan').val(response.jml_pesan);
            $('#jml_dosis').val(response.jml_dosis);
            $('#jml_dosis_obat').val(response.jml_dosis_obat);
            $('#satuan_obat').val(response.satuan_obat);
            $('#aturan_pakai').val(response.aturan_pakai);
            $('#keterangan_resep').val(response.keterangan);
        })
    }

    function clickeditracikan(id){
        preventDefault();
        activaTab('resep_racikan_tab');
        $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/getrowresep') ?>", {ID: id} , function (response) {      
            console.log(response);
            $('#id_pesan_resep_detail').val(response.id);
            $('#kode_brg_obat').val(response.kode_brg);
            $('#nama_racikan').val(response.nama_brg);
            $('#jml_racikan').val(response.jml_pesan);
            $('#dosis_start_r').val(response.jml_dosis);
            $('#dosis_end_r').val(response.jml_dosis_obat);
            $('#satuan_racikan').val(response.satuan_obat);
            $('#anjuran_pakai_r').val(response.aturan_pakai);
            $('#catatan_r').val(response.keterangan);
        })
    }

    function deleterow(id){
        preventDefault();
        if(confirm('Are you sure?')){
            $.ajax({
                url: "pelayanan/Pl_pelayanan/deleterowresep",
                data: { ID : id},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    oTable.ajax.url("pelayanan/Pl_pelayanan/get_cart_resep/"+$('#no_kunjungan').val()+"").load();
                    reset_form_racikan();
                    reset_form_resep();
                    reset_form_komposisi();
                }
            });
        }else{
            return false;
        }
        
    }

    function countJmlObat(){
        var jml_hari = parseInt($('#jml_hari').val());
        var jml_dosis = parseInt($('#jml_dosis').val());
        var jml_obat = parseInt($('#jml_dosis_obat').val());
        // jml obat
        var ttl_pesan = jml_hari * (jml_dosis * jml_obat);
        $('#jml_pesan').val(ttl_pesan);
    }

    function activaTab(tab){
        $('.tab-pane a[href="#' + tab + '"]').tab('show');
    }

    function save_template(){
        show_modal_medium('pelayanan/Pl_pelayanan/form_template_resep/'+$('#no_registrasi').val()+'/'+$('#no_kunjungan').val()+'', 'SIMPAN DATA OBAT SEBAGAI TEMPLATE RESEP');
    }

</script>

<style>
    .input-icon > input {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }
</style>

<!-- hidden form -->

<audio id="container" autoplay=""></audio>

<span>Waktu Pelayanan</span><br>
<div class="pull-left" style="font-size: 20px; font-weight: bold">
    <span id="minutes">00</span> : <span id="second">00</span> : <span id="centiSecond">00</span>
</div>
<div class="pull-right">
    <button type="button" class="btn btn-xs btn-inverse" id="startCount" onclick="startStopWatch()">Start <i class="fa fa-play"></i></button>
    <button type="button" class="btn btn-xs btn-inverse" id="pauseCount" onclick="pauseStopWatch()">Stop <i class="fa fa-pause"></i></button>
    <button type="button" class="btn btn-xs btn-success" id="callPatient">Panggil Pasien <i class="fa fa-bullhorn bigger-120"></i></button>
</div>
<br>
<div class="hr dotted"></div>

<input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>">
<input type="hidden" name="no_mr_resep" id="no_mr_resep" value="<?php echo $no_mr; ?>">
<p><b><i class="fa fa-edit"></i> ASSESMENT PASIEN </b></p>
<div class="form-group">
    <label class="control-label col-sm-3" for="">Tinggi Badan (cm)</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
    </div>
    <label class="control-label col-sm-3" for="">Berat Badan (Kg)</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Tekanan Darah</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
    </div>
    <label class="control-label col-sm-3" for="">Suhu Tubuh</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Nadi</label>
    <div class="col-sm-2">
       <input type="text" class="form-control" name="pl_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
    </div>
</div>

<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i>  DIAGNOSA DAN PEMERIKSAAN </b></p>

<div>
    <label for="form-field-8">Diagnosa (ICD10) <span style="color:red">* </span></label>
    <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Anamnesa <span style="color:red">* </span> <small>(minimal 8 karakter)</small> </label>
    <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
    <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>

<div class="row">
    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Pemeriksaan </label>
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
    </div>

    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Anjuran Dokter </label>
        <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>
    </div>
</div>

<br>
<p><b><i class="fa fa-file bigger-120"></i> e-RESEP FARMASI </b></p>
<div style="margin-top: 6px">
    <div class="checkbox" style="margin-left: -20px">
        <label>
        Apakah ada Resep Farmasi / Resep Dokter ? <span style="color:red">*</span>
        </label>
        <label>
            <?php 
                $checked_resep = ($this->Pl_pelayanan->check_resep_fr($value->kode_bagian, $value->no_registrasi) == true ) ? 'checked' : ''; 
            ?>
            <input name="check_resep" id="check_resep" type="radio" class="ace" value="1" <?php echo $checked_resep; ?>>
            <span class="lbl"> Ya</span>
        </label>
        <label>
            <?php 
                $checked_resep_no = ($this->Pl_pelayanan->check_resep_fr($value->kode_bagian, $value->no_registrasi) == false ) ? 'checked' : ''; 
            ?>
            <input name="check_resep" id="check_resep" type="radio" class="ace" value="0" <?php echo $checked_resep_no; ?>>
            <span class="lbl"> Tidak</span>
        </label>
    </div>
</div>

<div class="row" id="form_input_resep" <?php echo ($checked_resep == '')?'style="display: none"':''; ?>>
    <div class="col-md-12" style="margin-top: 6px">

        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#resep_non_racikan_tab">
                        <i class="green ace-icon fa fa-home bigger-120"></i>
                        Non Racikan
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#resep_racikan_tab">
                    <i class="green ace-icon fa fa-flask bigger-120"></i>
                    Racikan
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#template">
                    <i class="green ace-icon fa fa-list bigger-120"></i>
                    Template Resep
                    </a>
                </li>
            </ul>

            <div class="tab-content">                
                <input type="hidden" name="id_pesan_resep_detail" id="id_pesan_resep_detail" class="form-control">
                <div id="resep_non_racikan_tab" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <p style="font-weight: bold">Obat Non Racikan</p>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-sm btn-danger" onclick="reset_form_resep()"><i class="fa fa-refresh"></i> Reset Form</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Cari Obat</label>            
                        <div class="col-md-8">            
                        <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Keyword Obat" value="">
                        <input type="hidden" name="kode_brg" id="kode_brg_obat" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Dosis</label>
                        <div class="col-md-5">
                            <span class="input-icon">
                                <input name="jml_dosis" id="jml_dosis" type="text" style="width: 50px;text-align: center" value="1" onchange="countJmlObat()"/>
                            </span>

                            <span class="input-icon" style="padding-left: 4px">
                                <i class="fa fa-times bigger-150"></i>
                            </span>

                            <span class="input-icon">
                            <input name="jml_dosis_obat" id="jml_dosis_obat" type="text" style="width: 50px; text-align: center" value="1" onchange="countJmlObat()"/>
                            </span>
                        
                        </div>
                    </div>  
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Satuan</label>

                        <div class="col-md-4">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'Tab' , 'satuan_obat', 'satuan_obat', 'form-control', '', '');?>
                        </div>
                        <label class="control-label col-sm-2">Waktu</label>
                        <div class="col-md-4">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'aturan_pakai', 'aturan_pakai', 'form-control', '', '');?>
                        </div>
                    </div>  
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Jml Hari</label>
                        <div class="col-md-2">
                            <input class="form-control" name="jml_hari" id="jml_hari" type="text" style="text-align:center;" value="<?php echo ($value->kode_perusahaan == 120) ? 30 : "" ?>" onchange="countJmlObat()"/>
                        </div>
                        <label class="control-label col-sm-2">Jml Obat</label>
                        <div class="col-md-2">
                            <input class="form-control" name="jml_pesan" id="jml_pesan" type="text" style="text-align:center;" placeholder="(Auto)"/>
                        </div>
                        <!-- <div class="col-md-4" style="margin-top: 4px; margin-left: -20px">
                            <label>
                                <input name="is_racikan" type="checkbox" value="1" class="ace">
                                <span class="lbl"> Racikan </span>
                            </label>
                        </div> -->
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Keterangan</label>
                        <div class="col-md-10">
                            <input class="form-control" name="keterangan_resep" id="keterangan_resep" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 no-padding">
                            <div class="pull-left">
                                <a href="#" class="btn btn-xs btn-primary" id="btn_add_resep_obat" onclick="add_resep_obat()">Tambahkan Obat</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="resep_racikan_tab" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <p style="font-weight: bold">Obat Racikan</p>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-sm btn-danger" onclick="reset_form_racikan()"><i class="fa fa-refresh"></i> Reset Form Racikan</a>
                            </div>
                        </div>
                    </div>


                    <!-- form racikan header -->
                    <div id="data_racikan_div">

                        <div class="form-group">
                            <label class="control-label col-sm-2">Nama Racikan</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="nama_racikan" id="nama_racikan" value="">  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Jumlah Obat</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="jml_racikan" id="jml_racikan" style="text-align: center;">  
                            </div>
                            <label class="control-label col-sm-2">Satuan</label>
                            <div class="col-md-4">
                                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'Bks' , 'satuan_racikan', 'satuan_racikan', 'form-control', '', '');?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Dosis</label>
                            <div class="col-md-4">
                            <span class="inline">
                                <input name="dosis_start_r" id="dosis_start_r" type="text" style="width: 50px; text-align: center"/>
                            </span>
                            <span class="inline" style="padding-left: 4px;">
                                <i class="fa fa-times bigger-150"></i>
                            </span>
                            <span class="inline">
                                <input name="dosis_end_r" id="dosis_end_r" type="text" style="width: 50px; text-align: center"/>
                            </span>
                            
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Penggunaan</label>
                            <div class="col-md-4">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'anjuran_pakai_r', 'anjuran_pakai_r', 'form-control', '', '');?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-2">Catatan</label>
                            <div class="col-md-1">
                                <input class="form-control" name="catatan_r" id="catatan_r" type="text" style="width: 400px" value=""/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 no-padding">
                            <button type="submit" id="btn_submit_racikan" name="submit" value="header" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                Simpan Racikan
                            </button>
                            <button type="submit" id="btn_update_header_racikan" style="display:none" name="submit" value="header" class="btn btn-xs btn-success">
                                <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                                Update Resep Racikan
                            </button>
                            </div>
                        </div> 
                        
                        <hr>

                    </div>

                    <!-- form obat -->
                    <div id="data_obat_div" style="display: none">
                        <!-- Data Obat -->
                        <p><b>Komposisi Obat Racikan </b></p>

                        <!-- cari obat -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Cari Obat</label>  
                            <div class="col-md-8">   
                            <input type="text" name="obat" id="inputKeyObatRacikan" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                            <input type="hidden" name="obat" id="inputKeyObatRacikanHidden" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                            </div>
                        </div>

                        <!-- jumlah -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Jumlah Obat</label>
                            <div class="col-md-2">
                            <input type="text" class="form-control" name="jml_komposisi_obat" id="jml_komposisi_obat" style="text-align: center;">  
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-10 no-padding">
                                <button type="submit" id="add_komposisi_obat"  value="" name="submit" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                                Tambahkan Obat
                            </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="template" class="tab-pane fade">
                    <p>
                        --
                    </p>
                </div>

            </div>
        </div>

        <hr>
        <span style="font-weight: bold; font-style: italic">RESEP DOKTER</span>
        <table class="table" id="dt_add_resep_obat">
            <thead>
            <tr>
                <th>Item Obat</th>
                <th style="width: 50px !important"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="center">
            <a href="#" class="btn btn-xs btn-primary" onclick="save_template()"><i class="fa fa-save"></i> Simpan Sebagai Template Resep</a>
        </div>
        <br>
        <!-- <textarea name="pl_resep_farmasi" id="pl_resep_farmasi" class="form-control" style="height: 100px !important" placeholder="Keterangan lainnya"><?php echo isset($riwayat->resep_farmasi)?$this->master->br2nl($riwayat->resep_farmasi):''?></textarea> -->

    </div>
</div>
<hr>

<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i> PENUNJANG MEDIS </b></p>

<div style="margin-top: 6px">
    <label for="form-field-8">Penunjang Medis <span style="color:red">*</span></label>
    <div class="checkbox">

        <?php
            $arr_pm = array('050101','050201','050301');
            foreach ($arr_pm as $v_rw) :
                $checked = ($this->Pl_pelayanan->check_rujukan_pm($v_rw, $value->kode_bagian, $value->no_registrasi) == true ) ? 'checked' : ''; 
                switch ($v_rw) {
                    case '050101':
                        $nm_pm = 'Laboratorium';
                        break;

                    case '050201':
                        $nm_pm = 'Radiologi';
                        break;
                    
                    default:
                        $nm_pm = 'Fisioterapi';
                        break;
                }
        ?>
        <label>
            <input name="check_pm[]" type="checkbox" value="<?php echo $v_rw; ?>" class="ace" <?php echo $checked; ?> >
            <span class="lbl"> <?php echo $nm_pm ; ?> </span>
        </label>

        <?php endforeach; ?>

        <label>
            <input name="check_pm[]" type="checkbox" value="0" class="ace" <?php echo ($checked == '')?'checked': ''?> >
            <span class="lbl"> Tidak Ada Penunjang </span>
        </label>

    </div>
</div>

<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i> STATUS KUNJUNGAN PASIEN </b></p>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Cara Keluar Pasien</label>
    <div class="col-sm-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'cara_keluar')), 'Atas Persetujuan Dokter' , 'cara_keluar', 'cara_keluar', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3" for="">Pasca Pulang</label>
    <div class="col-sm-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'label', 'name' => 'label', 'where' => array('flag' => 'pasca_pulang')), 'Dalam Masa Pengobatan' , 'pasca_pulang', 'pasca_pulang', 'form-control', '', '') ?>
    </div>
</div>

<div class="form-group" style="padding-top: 10px">
    <div class="col-sm-12 no-padding">
       <button type="submit" name="submit" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> <?php echo ($this->session->userdata('flag_form_pelayanan')) ?  ($this->session->userdata('flag_form_pelayanan') == 'perawat') ? 'Simpan Data' : 'Simpan Data dan Lanjutkan ke Pasien Berikutnya' : 'Simpan Data'?> </button>
    </div>
</div>

<script src="<?php echo base_url()?>assets/js/custom/counter_poli.js"></script>