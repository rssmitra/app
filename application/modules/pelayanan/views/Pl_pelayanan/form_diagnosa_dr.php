<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
    
    jQuery(function($) {  

        $('.date-picker').datepicker({    

        autoclose: true,    

        todayHighlight: true    

        })  

        //show datepicker when clicking on the icon

        .next().on(ace.click_event, function(){    

        $(this).prev().focus();    

        });  

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

    $('#pl_diagnosa_sekunder').typeahead({
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
        $('#pl_diagnosa_sekunder').val('');
        $('<span class="multi-typeahead" id="txt_icd_'+val_item.trim().replace('.', '_')+'"><a href="#" onclick="remove_icd('+"'"+val_item.trim().replace('.', '_')+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt');
        }

    });

    $( "#pl_diagnosa_sekunder" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            var val_item = 1 + Math.floor(Math.random() * 100);
            console.log(val_item);
            var item = $('#pl_diagnosa_sekunder').val();
            $('<span class="multi-typeahead" id="txt_icd_'+val_item+'"><a href="#" onclick="remove_icd('+"'"+val_item+"'"+')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span><span class="text_icd_10"> '+item+' </span> </span>').appendTo('#pl_diagnosa_sekunder_hidden_txt'); 
          }          
          return $('#pl_diagnosa_sekunder').val('');                 
        }    
    });

    function remove_icd(icd){
        preventDefault();
        $('#txt_icd_'+icd+'').html('');
        $('#txt_icd_'+icd+'').hide();
    }

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

    $('#callPatientPoli').click(function (e) {  
      e.preventDefault();
      // setTimeout(playAudioDing(1, 2), 5000);
      var params = {
        no_kunjungan : $('#no_kunjungan').val(),
        dokter : $('#kode_dokter_poli').val(),
        poli : $('#kode_bagian_val').val(),
      };
      $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan/callPatient') ?>", params , function (response) { 
           // no action
      })
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
                    // hide row first
                    $('#row_'+jsonResponse.newId+'').remove();
                    // add to tabel
                    $('<tr id="row_'+jsonResponse.newId+'"><td align="center">'+jsonResponse.newId+'<input type="hidden" name="idobatdetail[]" value="'+jsonResponse.newId+'"></td><td>'+formData.kode_brg+' : '+formData.nama_brg+'</td><td>'+formData.jml_dosis+' x '+formData.jml_dosis_obat+' '+formData.satuan_obat+' '+formData.aturan_pakai+'</td><td>'+formData.keterangan+'</td><td align="center"><a href="#" class="btn btn-xs btn-warning" onclick="clickedit('+jsonResponse.newId+')"><i class="fa fa-pencil"></i></a> <a href="#" class="btn btn-xs btn-danger" onclick="deleterow('+jsonResponse.newId+')"><i class="fa fa-trash"></i></a></td></tr>').appendTo($('#dt_add_resep_obat')); 
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

    function reset_form_resep(){
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

    function clickedit(id){
        preventDefault();
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

    function deleterow(id){
        preventDefault();
        $('#row_'+id+'').remove();
        $.ajax({
            url: "pelayanan/Pl_pelayanan/deleterowresep",
            data: { ID : id},            
            dataType: "json",
            type: "POST",
            success: function (response) {
                return false;
            }
        });
    }

    function countJmlObat(){
        var jml_hari = parseInt($('#jml_hari').val());
        var jml_obat = parseInt($('#jml_dosis_obat').val());
        // jml obat
        var ttl_pesan = jml_hari * jml_obat;
        $('#jml_pesan').val(ttl_pesan);
    }

    $('#pl_procedure').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefProcedure",
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
            var label_item=item.split('-')[1];
            var val_item=item.split('-')[0];
            console.log(val_item);
            $('#pl_procedure').val(label_item);
            $('#pl_procedure_hidden').val(val_item);
          }
      });

</script>
<script src="<?php echo base_url()?>assets/tts/script.js"></script>
<!-- hidden form -->

<audio id="container" autoplay=""></audio>

<span>Waktu Pelayanan</span><br>
<div class="pull-left" style="font-size: 20px; font-weight: bold"> 
    <span id="minutes">00</span> : <span id="second">00</span> : <span id="centiSecond">00</span>
</div>
<div class="pull-right">
    <button type="button" class="btn btn-xs btn-inverse" id="startCount" onclick="startStopWatch()">Start <i class="fa fa-play"></i></button>
    <button type="button" class="btn btn-xs btn-inverse" id="pauseCount" onclick="pauseStopWatch()">Stop <i class="fa fa-pause"></i></button>
    <button type="button" class="btn btn-xs btn-success" onclick="speak()" id="callPatientPoli">Call <i class="fa fa-bullhorn bigger-120"></i></button>
</div>
<br>

<div class="hr dotted"></div>

<div class="widget-box transparent ui-sortable-handle collapsed" id="widget-box-12" style="display: block">
    <div class="widget-header">
        <span style="font-style: italic; font-size: 14px" class="widget-title lighter">Pemanggilan Pasien</h4>
        <div class="widget-toolbar no-border">
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>

    <div class="widget-body" style="display: none;">
        <form style="padding: 10px">
            <label>Text to speech</label>
            <input type="text" class="txt" style="width: 100%" value="<?php echo $txt_call_patient?>">
            <div class="col-md-6 no-padding">
                <label for="rate">Rate</label><input type="range" min="0.5" max="2" value="1" step="0.1" id="rate">
                <div class="rate-value">1</div>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-6">
                <label for="pitch">Pitch</label><input type="range" min="0" max="2" value="1" step="0.1" id="pitch">
                <div class="pitch-value">1</div>
                <div class="clearfix"></div>
            </div>
            <label>Language</label><br>
            <select id="tts_language" style="width: 100%"></select>
        </form>
    </div>
</div>
<div class="col-md-4 no-padding" style="margin-top: 14px">
    <span style="font-weight: bold"><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:''?></span> <br>
    <span>Tanggal periksa. <?php echo isset($value->tgl_keluar_poli)?$this->tanggal->formatDateTimeFormDmy($value->tgl_keluar_poli) : $this->tanggal->formatDateTimeFormDmy($value->tgl_jam_poli)?></span> <br>
</div>
<div class="col-md-8">
    <p style="text-align: right; margin-top: -10px"><b><span style="font-size: 36px;font-family: 'Glyphicons Halflings';">S O A P</span> <br>(<i>Subjective, Objective, Assesment, Planning</i>) </b></p>
</div>

<span style="font-weight: bold; font-style: italic; color: blue">(Subjective)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"> <b>Anamnesa / Keluhan Pasien</b> <span style="color:red">* </span> <br><span style="font-size: 11px; font-style: italic">(Masukan anamnesa minimal 8 karakter)</span> </label>
    <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important" id="pl_anamnesa"><?php echo isset($riwayat->anamnesa)?$this->master->br2nl($riwayat->anamnesa):''?></textarea>
    <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>
<br>

<span style="font-weight: bold; font-style: italic; color: blue">(Objective)</span>

<div style="margin-top: 6px">
    <label for="form-field-8"> <i><b>Vital Sign</b></i><br><span style="font-size: 11px; font-style: italic">(Masukan tanda-tanda vital)</span></label>
    <table class="table">
        <tr style="font-size: 11px; background: beige;">
            <th>Tinggi Badan (Cm)</th>
            <th>Berat Badan (Kg)</th>
            <th>Tekanan Darah (mmHg)</th>
            <th>Nadi (bpm)</th>
            <th>Suhu Tubuh (C&deg;)</th>
        </tr>
        <tbody>
        <tr style="background: aliceblue;">
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_tb" value="<?php echo isset($riwayat->tinggi_badan)?$riwayat->tinggi_badan:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_bb" value="<?php echo isset($riwayat->berat_badan)?$riwayat->berat_badan:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_td" value="<?php echo isset($riwayat->tekanan_darah)?$riwayat->tekanan_darah:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_nadi" value="<?php echo isset($riwayat->nadi)?$riwayat->nadi:''?>">
            </td>
            <td>
                <input type="text" style="text-align: center" class="form-control" name="pl_dr_suhu" value="<?php echo isset($riwayat->suhu)?$riwayat->suhu:''?>">
            </td>
        </tr>
        </tbody>
    </table>

    <label for="form-field-8"> <b>Pemeriksaan Fisik</b><br><span style="font-size: 11px; font-style: italic">(Mohon dijelaskan kondisi fisik pasien)</span></label>
    <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$this->master->br2nl($riwayat->pemeriksaan):''?></textarea>
    <input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>"><br>
    
</div>

<span style="font-weight: bold; font-style: italic; color: blue">(Assesment)</span>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diagnosa Primer(ICD10)</b> <span style="color:red">* </span><br><i style="font-size: 11px">(Wajib mengisi menggunakan ICD10)</i></label>
    <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8"><b>Diagnosa Sekunder</b> <br><i style="font-size: 11px">(Klik <b>"enter"</b> untuk menambahkan Diagnosa Sekunder dan dapat diisi lebih dari satu )</i></label>
    <input type="text" class="form-control" name="pl_diagnosa_sekunder" id="pl_diagnosa_sekunder" placeholder="Masukan keyword ICD 10" value="">
    <div id="pl_diagnosa_sekunder_hidden_txt" style="padding: 2px; line-height: 23px; border: 1px solid #d5d5d5; min-height: 25px; margin-top: 2px">
        <?php
            $arr_text = isset($riwayat->diagnosa_sekunder) ? explode('|',$riwayat->diagnosa_sekunder) : [];
            // echo "<pre>";print_r($arr_text);
            $no_ds = 1;
            foreach ($arr_text as $k => $v) {
                $len = strlen(trim($v));
                // echo $len;
                if($len > 0){
                    $no_ds++;
                    $split = explode(':',$v);
                    if(count($split) > 1){
                        echo '<span class="multi-typeahead" id="txt_icd_'.trim(str_replace('.','_',$split[0])).'"><a href="#" onclick="remove_icd('."'".trim(str_replace('.','_',$split[0]))."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }else{
                        echo '<span class="multi-typeahead" id="txt_icd_'.$no_ds.'"><a href="#" onclick="remove_icd('."'".$no_ds."'".')" style="padding: 3px;text-align: center"><i class="fa fa-times black"></i> </a><span style="display: none">|</span> <span class="text_icd_10"> '.$v.' </span> </span>';
                    }
                }
                
            }
        ?>
    </div>
    <input type="hidden" class="form-control" name="konten_diagnosa_sekunder" id="konten_diagnosa_sekunder" value="<?php echo isset($riwayat->diagnosa_sekunder)?$riwayat->diagnosa_sekunder:''?>">
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Prosedur/ Tindakan(ICD9)</b> <span style="color:red">* </span><br><i style="font-size: 11px">(Wajib mengisi menggunakan ICD9)</i></label>
    <input type="text" class="form-control" name="pl_procedure" id="pl_procedure" placeholder="Masukan keyword ICD 9" value="<?php echo isset($riwayat->text_icd9)?$riwayat->text_icd9:' Other consultation'?>">
    <input type="hidden" class="form-control" name="pl_procedure_hidden" id="pl_procedure_hidden" value="<?php echo isset($riwayat->kode_icd9)?$riwayat->kode_icd9:'89.08'?>">
</div>

<br>
<span style="font-weight: bold; font-style: italic; color: blue">(Planning)</span>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Rencana Asuhan / Anjuran Dokter</b><br><i style="font-size: 11px">(Mohon dijelaskan Rencana Asuhan Pasien dan Tindak Lanjutnya)</i></label>
    <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$this->master->br2nl($riwayat->pengobatan):''?></textarea>
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Tanggal Kontrol Kembali</b><br><i style="font-size: 11px">(Secara default untuk pasien BPJS kontrol kembali setelah 31 hari)</i></label><br>
    <input type="text" class="date-picker" data-date-format="yyyy-mm-dd" name="pl_tgl_kontrol_kembali" id="pl_tgl_kontrol_kembali" class="form-control" style="width: 100% !important" placeholder="ex: <?php echo date('Y-m-d')?>" value="<?php $next_date = date('Y-m-d', strtotime("+31 days")); echo isset($riwayat->tgl_kontrol_kembali)?$riwayat->tgl_kontrol_kembali:$next_date?>">
</div>
<div style="margin-top: 6px">
    <label for="form-field-8"><b>Catatan Kontrol</b></label>
    <textarea name="pl_catatan_kontrol" id="pl_catatan_kontrol" class="form-control" style="height: 70px !important" placeholder="ex. Mohon membawa hasil LAB saat kontrol kembali"><?php echo isset($riwayat->catatan_kontrol_kembali)?$this->master->br2nl($riwayat->catatan_kontrol_kembali):''?></textarea>
</div>
<br>


<p><b><i class="fa fa-stethoscope bigger-120"></i> INFORMASI PASIEN PULANG </b></p>
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
        <?php if(isset($_GET['form']) && $_GET['form'] == 'billing_entry') : ?>
            <div class="alert alert-danger"><strong>Peringatan!</strong><br>Session anda bukan sebagai dokter, anda tidak dapat mengubah SOAP</div>
       <?php else:?>
       <button type="submit" name="submit" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>" class="btn btn-xs btn-primary" id="btn_save_data"> <i class="fa fa-save"></i> <?php echo ($this->session->userdata('flag_form_pelayanan')) ?  ($this->session->userdata('flag_form_pelayanan') == 'perawat') ? 'Simpan Data' : 'Simpan Data' : 'Simpan Data'?> </button>
       <?php endif;?>
    </div>
</div>

<script src="<?php echo base_url()?>assets/js/custom/counter_poli.js"></script>