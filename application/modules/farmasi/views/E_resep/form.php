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
            "url": "farmasi/E_resep/get_cart_resep_data/"+$('#kode_pesan_resep_e').val()+"",
            "type": "POST"
        }
    });

    oTable2 = $('#dt_template_resep').DataTable({ 
            
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "ordering": false,
            "searching": false,
            "bPaginate": false,
            "bInfo": false,
            "pageLength": 25,
            "ajax": {
                "url": "farmasi/E_resep/get_template_resep/"+$('#kode_dokter_poli').val()+"",
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false, //set not orderable
                },
                {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
                { "visible": false, "targets": [ 1 ] },
            ],
    });

    $('#dt_template_resep tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable2.row( tr );
            var data = oTable2.row( $(this).parents('tr') ).data();
            var ID = data[ 1 ];
                      

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
                $.getJSON("farmasi/E_resep/getDetail/" + ID, '', function (data) {
                      response_data = data;
                      // Open this row
                      row.child( format_html( response_data ) ).show();
                      tr.addClass('shown');
                });                              
            }
    } );

    function format_html ( data ) {
        return data.html;
    }

    $('#btn_submit_racikan, #btn_update_header_racikan').click(function (e) {  
      e.preventDefault();
      var formData = {
            submit : $(this).val(),
            id_template : $('#id_template').val(),
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            kode_pesan_resep : $('#kode_pesan_resep_e').val(),
            no_registrasi : $('#no_registrasi').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_brg : $('#kode_brg_racik').val(),
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
            tipe_racik : $('input[name="tipe_racik"]:checked').val(),
            parent : '0',
        };
        $.ajax({
            url: "farmasi/E_resep/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  

                    oTable.ajax.url("farmasi/E_resep/get_cart_resep_data/"+$('#kode_pesan_resep_e').val()+"").load();

                    $('#btn_submit_racikan').hide();
                    $('#btn_update_header_racikan').hide();
                    $('#data_obat_div').show();
                    $('#add_komposisi_obat').show('');
                    $('#add_komposisi_obat').val(jsonResponse.parent);
                    $('#this_template').html('');

                    // reset form
                    reset_form_resep();
                    $('#txt_nama_racikan').text('[ '+$('#nama_racikan').val()+' ]');
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
            submit : $(this).val(),
            id_template : $('#id_template').val(),
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            no_registrasi : $('#no_registrasi').val(),
            kode_pesan_resep : $('#kode_pesan_resep_e').val(),
            no_kunjungan : $('#no_kunjungan').val(),
            kode_brg : $('#inputKeyObatRacikanHidden').val(),
            nama_brg : $('#inputKeyObatRacikan').val(),
            jml_pesan : $('#jml_komposisi_obat').val(),
            satuan_obat : $('#satuan_racik').val(),
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
            url: "farmasi/E_resep/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  

                    oTable.ajax.url("farmasi/E_resep/get_cart_resep_data/"+$('#kode_pesan_resep_e').val()+"").load();

                    $('#btn_submit_racikan').hide();
                    $('#data_obat_div').show();
                    $('#this_template').html('');
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
            submit : $('#btn_add_resep_obat').attr('value'),
            id_template : $('#id_template').val(),
            id_pesan_resep_detail : $('#id_pesan_resep_detail').val(),
            kode_pesan_resep : $('#kode_pesan_resep_e').val(),
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
            url: "farmasi/E_resep/add_resep_obat",
            data: formData,            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;  
                var jsonResponse = JSON.parse(data);  
                if(jsonResponse.status === 200){  
                    if(jsonResponse.type == 'fr_tc_pesan_resep_detail'){
                        oTable.ajax.url("farmasi/E_resep/get_cart_resep_data/"+$('#kode_pesan_resep_e').val()+"").load();
                    }

                    if(jsonResponse.type == 'fr_tc_template_resep_detail'){
                        activeTab('template_tab');
                        $('#id_template').val("");
                        oTable2.ajax.url("farmasi/E_resep/get_template_resep/"+$('#kode_dokter_poli').val()+"").load();
                    }

                    $('#inputKeyObat').focus();
                    $('#this_template').html('');
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
            $('#add_komposisi_obat').focus();            
          }          
          return false;                 
        }    
    });

    $( "#nama_racikan" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_racikan').focus();            
          }          
          return false;                 
        }    
    });

    $( "#jml_racikan" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#satuan_racikan').focus();            
          }          
          return false;                 
        }    
    });

    $( "#satuan_racikan" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#dosis_start_r').focus();            
          }          
          return false;                 
        }    
    });

    $( "#dosis_start_r" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#dosis_end_r').focus();            
          }          
          return false;                 
        }    
    });

    $( "#dosis_end_r" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#anjuran_pakai_r').focus();            
          }          
          return false;                 
        }    
    });
    $( "#anjuran_pakai_r" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#catatan_r').focus();            
          }          
          return false;                 
        }    
    });
    $( "#catatan_r" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#btn_submit_racikan').click();            
          }          
          return false;                 
        }    
    });

    $( "#inputKeyObatRacikan" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#jml_komposisi_obat').focus();            
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
            $('#satuan_racik').focus();            
          }          
          return false;                 
        }    
    });

    $( "#satuan_racik" )    
      .keypress(function(event) {        
        var keycode =(event.keyCode?event.keyCode:event.which);         
        if(keycode ==13){          
          event.preventDefault();         
          if($(this).valid()){            
            $('#keterangan_bahan_racik').focus();            
          }          
          return false;                 
        }    
    });

    $( "#keterangan_bahan_racik" )    
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
        $('#id_template').val("");
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
        $('#id_template').val("");
        $('#inputKeyObatRacikan').val("");
        $('#inputKeyObatRacikanHidden').val("");
        $('#jml_komposisi_obat').val("");
        $('#satuan_racik').val("");
    }

    function reset_form_racikan(){
        preventDefault();
        $('#kode_brg_racik').val("");
        $('#nama_racikan').val("");
        $('#nama_racikan').focus();
        $('#jml_racikan').val("");
        $('#satuan_racikan').val("");
        $('#dosis_start_r').val("");
        $('#dosis_end_r').val("");
        $('#anjuran_pakai_r').val("");
        $('#catatan_r').val("");
        $('#id_pesan_resep_detail').val("");
        $('#id_template').val("");
        $('#inputKeyObatRacikan').val("");
        $('#inputKeyObatRacikanHidden').val("");

        $('#btn_submit_racikan').show();
        $('#btn_update_header_racikan').hide();
        // $('#data_obat_div').hide();
        $('#add_komposisi_obat').val("");

    }

    function clickedit(id){
        preventDefault();
        activeTab('resep_non_racikan_tab');
        // reset racikan
        reset_form_racikan();
        reset_form_komposisi();
        $.getJSON("<?php echo site_url('farmasi/E_resep/getrowresep') ?>", {ID: id} , function (response) {      
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

    function clickedititemtemplate(id){
        preventDefault();
        activeTab('resep_non_racikan_tab');
        $('#div_show_resep').hide();
        $('#this_template').html('<p><span style="color: red">[Edit Template Resep]</span></p>');
        $.getJSON("<?php echo site_url('farmasi/E_resep/getrowitemtemplate') ?>", {ID: id} , function (response) {      
            console.log(response);
            $('#id_template').val(response.id_template);
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
        activeTab('resep_racikan_tab');
        // reset non racikan
        reset_form_resep();
        $.getJSON("<?php echo site_url('farmasi/E_resep/getrowresep') ?>", {ID: id} , function (response) {      
            console.log(response);
            $('#id_template').val(response.id_template);
            $('#id_pesan_resep_detail').val(response.id);
            $('#kode_brg_obat').val(response.kode_brg);
            $('#kode_brg_racik').val(response.kode_brg);
            $('#nama_racikan').val(response.nama_brg);
            $('#jml_racikan').val(response.jml_pesan);
            $('#dosis_start_r').val(response.jml_dosis);
            $('#dosis_end_r').val(response.jml_dosis_obat);
            $('#satuan_racikan').val(response.satuan_obat);
            $('#anjuran_pakai_r').val(response.aturan_pakai);
            $('#catatan_r').val(response.keterangan);
            $('#txt_nama_racikan').text('[ '+response.nama_brg+' ]');
            // show button upadte
            $('#btn_submit_racikan').hide();
            $('#btn_update_header_racikan').show();
            $('#add_komposisi_obat').val(response.kode_brg);
            $('#add_komposisi_obat').hide();
        })
        
    }

    function clickeditracikanitemtemplate(id){
        preventDefault();
        activeTab('resep_racikan_tab');
        $('#div_show_resep').hide();
        $('#this_template').html('<p><span style="color: red">[Edit Template Resep]</span></p>');
        $.getJSON("<?php echo site_url('farmasi/E_resep/getrowitemtemplate') ?>", {ID: id} , function (response) {      
            console.log(response);
            $('#id_template').val(response.id_template);
            $('#id_pesan_resep_detail').val(response.id);
            $('#kode_brg_racik').val(response.kode_brg);
            $('#nama_racikan').val(response.nama_brg);
            $('#jml_racikan').val(response.jml_pesan);
            $('#dosis_start_r').val(response.jml_dosis);
            $('#dosis_end_r').val(response.jml_dosis_obat);
            $('#satuan_racikan').val(response.satuan_obat);
            $('#anjuran_pakai_r').val(response.aturan_pakai);
            $('#catatan_r').val(response.keterangan);
            // show button upadte
            $('#btn_submit_racikan').hide();
            $('#btn_update_header_racikan').show();
        })
    }

    function deleterow(id){
        preventDefault();
        if(confirm('Are you sure?')){
            $.ajax({
                url: "farmasi/E_resep/deleterowresep",
                data: { ID : id},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    oTable.ajax.url("farmasi/E_resep/get_cart_resep_data/"+$('#kode_pesan_resep_e').val()+"").load();
                    reset_form_racikan();
                    reset_form_resep();
                    reset_form_komposisi();
                }
            });
        }else{
            return false;
        }
        
    }

    function deleterowtemplate(id){
        preventDefault();
        if(confirm('Are you sure?')){
            $.ajax({
                url: "farmasi/E_resep/deleterowtemplate",
                data: { ID : id},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    oTable2.ajax.url("farmasi/E_resep/get_template_resep/"+$('#kode_dokter_poli').val()+"").load();
                }
            });
        }else{
            return false;
        }
        
    }

    function deleterowitemtemplate(id){
        preventDefault();
        if(confirm('Are you sure?')){
            $.ajax({
                url: "farmasi/E_resep/deleterowitemtemplate",
                data: { ID : id},            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    $('#detail_'+id+'').hide();
                    $('#row_racikan_template_'+id+'').hide();

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
        // var ttl_pesan = jml_hari * (jml_dosis * jml_obat);
        var ttl_pesan = '';
        $('#jml_pesan').val(ttl_pesan);
    }

    function activeTab(tab){

        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        
        if(tab == 'template_tab'){
            $('#div_show_resep').hide();
        }else{
            $('#div_show_resep').show();
        }
    }

    function save_template(){
        show_modal_small('farmasi/E_resep/form_template_resep/'+$('#kode_dokter_poli').val()+'/'+$('#kode_pesan_resep_e').val()+'', 'SIMPAN DATA OBAT SEBAGAI TEMPLATE RESEP');
    }

    function click_edit_template(id){
        preventDefault();
        show_modal_small('farmasi/E_resep/form_template_resep/'+$('#kode_dokter_poli').val()+'/'+$('#kode_pesan_resep_e').val()+'?ID='+id+'', 'SIMPAN DATA OBAT SEBAGAI TEMPLATE RESEP');
    }

    function click_resepkan_template(id){
        preventDefault();
        if(confirm('Apakah anda yakin akan meresepkan format ini?')){
            $.ajax({
                url: "farmasi/E_resep/proses_resepkan_template",
                data: { ID : id, no_kunjungan : $('#no_kunjungan').val(), no_registrasi : $('#no_registrasi').val(), no_mr : $('#no_mr_resep').val(), kode_pesan_resep : $('#kode_pesan_resep_e').val() },            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    // load eresep
                    getMenuTabs('farmasi/E_resep/form/'+$('#no_registrasi').val()+'/'+$('#kode_pesan_resep_e').val()+'?no_mr='+$('#no_mr_resep').val()+
                    '&no_kunjungan='+$('#no_kunjungan').val(), 'form_pesan_resep')
                }
            });
        }else{
            return false;
        }
        
    }

    function proses_resep(id){
        preventDefault();
        if(confirm('Apakah anda yakin akan memproses resep ini?')){
            $.ajax({
                url: "farmasi/E_resep/proses_resep",
                data: $('#form_pelayanan').serialize(),            
                dataType: "json",
                type: "POST",
                success: function (response) {
                    back_to_previous();
                    // $('#kode_pesan_resep').val(response.kode_pesan_resep);
                    // $('#form_input_resep').html('<div class="alert alert-block alert-success"><strong><i class="ace-icon fa fa-check"></i> Sukses</strong><br> e Resep anda berhasil diproses dengan <b>kode '+response.kode_pesan_resep+'</b>.</div>');
                }
            });
        }else{
            return false;
        }
        
    }

</script>
<style>
    .input-icon > input {
        padding-left: 0px !important;
        padding-right: 0px !important;
        text-align: center !important;
    }
</style>
<!-- hidden -->
 <input type="hidden" name="kode_dokter_poli" id="kode_dokter_poli" value="<?php echo isset($value->kode_dokter)?$value->kode_dokter:''?>">
<div class="row" id="form_input_resep">
    <div class="col-md-6" style="line-height: 18px">
        Kode Resep :<br>
        <?php if(isset($pesan_resep->kode_pesan_resep)) : ?>
        <span style="font-weight: bold; font-size: 20px"><?php echo isset($pesan_resep->kode_pesan_resep)?$pesan_resep->kode_pesan_resep:0?></span> <br><i>(<?php echo isset($pesan_resep->tgl_pesan) ? $this->tanggal->formatDateTime($pesan_resep->tgl_pesan) : '-'?>)</i>
        <?php else: echo '<span style="color: red; font-weight: bold">[Belum ada pesan resep]</span>'; endif; ?>
    </div>
    <div class="col-md-6" style="text-align: right">
        <a href="#" class="btn btn-xs btn-primary" onclick="save_template()"><i class="fa fa-save"></i> Simpan Sebagai Template Resep</a>
        <a href="#" class="btn btn-xs btn-success" onclick="proses_resep()"><i class="fa fa-save"></i> Resep Selesai</a>
    </div>

    <div class="col-md-12" style="margin-top: 6px">
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#resep_non_racikan_tab" onclick="activeTab('resep_non_racikan_tab')">
                        <i class="green ace-icon fa fa-home bigger-120"></i>
                        Non Racikan
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#resep_racikan_tab" onclick="activeTab('resep_racikan_tab')">
                    <i class="green ace-icon fa fa-flask bigger-120"></i>
                    Racikan
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#template_tab" onclick="activeTab('template_tab')">
                    <i class="green ace-icon fa fa-list bigger-120"></i>
                    Template Resep
                    </a>
                </li>
            </ul>
            <br>
            <input type="hidden" name="id_template" id="id_template" class="form-control">
            <input type="hidden" name="no_mr_resep" id="no_mr_resep" value="<?php echo $no_mr; ?>">
            <input type="hidden" name="kode_pesan_resep_e" id="kode_pesan_resep_e" value="<?php echo isset($pesan_resep->kode_pesan_resep) ? $pesan_resep->kode_pesan_resep : 0?>">

            <div id="this_template"></div>

            <div class="tab-content">                
                <input type="hidden" name="id_pesan_resep_detail" id="id_pesan_resep_detail" class="form-control">

                <div id="resep_non_racikan_tab" class="tab-pane fade in active">
                    <!-- hidden -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <p style="font-weight: bold">Obat Non Racikan</p>
                            </div>
                            <!-- <div class="pull-right">
                                <a href="#" class="btn btn-sm btn-danger" onclick="reset_form_resep()"><i class="fa fa-refresh"></i> Reset Form</a>
                            </div> -->
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-sm-2">Cari Obat</label>            
                        <div class="col-md-10">            
                            <input type="text" name="obat" id="inputKeyObat" class="form-control" placeholder="Masukan Keyword Obat" value="">
                            <input type="hidden" name="kode_brg" id="kode_brg_obat" class="form-control">
                            <span style="font-style: italic; padding-left: 5px; font-weight: bold; color: blue; font-size: 11px">Jika obat tidak ditemukan, mohon diisi nama obat dengan lengkap [free text]</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Signa</label>
                        <div class="col-md-3">
                            <span class="input-icon">
                                <input name="jml_dosis" id="jml_dosis" type="text" style="width: 30px;" value="" onchange="countJmlObat()"/>
                            </span>

                            <span class="input-icon" style="padding-left: 4px">
                                <i class="fa fa-times bigger-150"></i>
                            </span>

                            <span class="input-icon">
                            <input name="jml_dosis_obat" id="jml_dosis_obat" type="text" style="width: 50px; " value="" onchange="countJmlObat()"/>
                            </span>
                        </div>
                        <div class="col-md-3" style="margin-left: -5.6%">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), '' , 'satuan_obat', 'satuan_obat', 'form-control', '', '');?>
                        </div>
                        <div class="col-md-3" style="margin-left: -21px">
                            <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), '' , 'aturan_pakai', 'aturan_pakai', 'form-control', '', '');?>
                        </div>
                        <label class="control-label col-sm-1">Qty</label>
                        <div class="col-md-1">
                            <input class="form-control" name="jml_pesan" id="jml_pesan" type="text" style="text-align:center; width: 50px !important"/>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="control-label col-sm-2">Keterangan</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="keterangan_resep" id="keterangan_resep">
                        </div>
                        <div class="col-md-2" style="margin-left: -2%;">
                            <a href="#" class="btn btn-xs btn-primary" id="btn_add_resep_obat" onclick="add_resep_obat()" value="add_non_racikan"><i class="fa fa-plus"></i> Tambahkan Obat</a>
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
                                <a href="#" onclick="reset_form_racikan()"><i class="fa fa-plus"></i> Buat Racikan Baru</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- form racikan header -->
                    <div id="data_racikan_div">

                        <input type="hidden" class="form-control" name="kode_brg_racik" id="kode_brg_racik" value="0">  
                        <!-- <div class="form-group">
                            <label class="control-label col-sm-2">Tipe Racik</label>
                            <div class="col-md-9">
                            <div class="radio">
                                <label>
                                    <input name="tipe_racik" type="radio" class="ace" value="dtd" checked/>
                                    <span class="lbl"> DTD</span>
                                </label>

                                <label>
                                    <input name="tipe_racik" type="radio" class="ace" value="non dtd" />
                                    <span class="lbl"> Non DTD</span>
                                </label>
                            </div>
                            </div> 
                        </div> -->
                        
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nama Racikan</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="nama_racikan" id="nama_racikan" value="">  
                            </div>
                            <label class="control-label col-sm-1">Qty</label>
                            <div class="col-md-1">
                                <input type="text" class="form-control" name="jml_racikan" id="jml_racikan" style="text-align: center;">  
                            </div>
                            <div class="col-md-3" style="margin-left: -20px">
                                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'satuan_obat')), 'Bks' , 'satuan_racikan', 'satuan_racikan', 'form-control', '', '');?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Signa</label>
                            <div class="col-md-3">
                                <span class="inline">
                                    <input name="dosis_start_r" id="dosis_start_r" type="text" style="width: 30px; text-align: center"/>
                                </span>
                                <span class="inline" style="padding-left: 4px;">
                                    <i class="fa fa-times bigger-150"></i>
                                </span>
                                <span class="inline">
                                    <input name="dosis_end_r" id="dosis_end_r" type="text" style="width: 50px; text-align: center"/>
                                </span>
                            </div>
                            <div class="col-md-4" style="margin-left: -5.6%">
                                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'anjuran_pakai_obat')), 'Sesudah Makan' , 'anjuran_pakai_r', 'anjuran_pakai_r', 'form-control', '', '');?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Keterangan</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="catatan_r" id="catatan_r">
                            </div>
                            <div class="col-md-2" style="margin-left: -2%">
                                <button type="submit" id="btn_submit_racikan" name="submit" value="header" class="btn btn-xs btn-primary">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Simpan Racikan
                                </button>
                                <button type="submit" id="btn_update_header_racikan" style="display:none" name="submit" value="header" class="btn btn-xs btn-success">
                                    <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                                    Update Racikan
                                </button>
                            </div>
                        </div>

                        <!-- <div class="form-group">
                        <label class="col-sm-2">&nbsp;</label>
                            <div class="col-md-8" style="padding-left: 18px">
                            <button type="submit" id="btn_submit_racikan" name="submit" value="header" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                Simpan Racikan
                            </button>
                            <button type="submit" id="btn_update_header_racikan" style="display:none" name="submit" value="header" class="btn btn-xs btn-success">
                                <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                                Update Racikan
                            </button>
                            </div>
                        </div>  -->
                        <hr>
                    </div>

                    <!-- form obat -->
                    <div id="data_obat_div" style="display: block; background: #edf3f4;padding: 10px;">
                        <!-- Data Obat -->
                        <p><b>Bahan Obat Racikan <span id="txt_nama_racikan"></span></b></p>
                        <table width="100%">
                            <tr style="border-bottom: 1px solid grey ">
                                <th>Cari Obat :</th>
                                <th style="padding-left: 7px;" width="50px">Dosis:</th>
                                <th style="padding-left: 7px;" width="100px">Satuan :</th>
                                <th style="padding-left: 7px;">Keterangan :</th>
                                <th style="padding-left: 7px;" width="100px"></th>
                            </tr>
                            <tr style="padding-top: 10px">
                                <td style="padding-top: 5px"><input type="text" name="obat" id="inputKeyObatRacikan" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                                <input type="hidden" name="obat" id="inputKeyObatRacikanHidden" class="form-control" placeholder="Masukan Keyword Obat" value=""> </td>
                                <td style="padding-top: 5px"><input type="text" class="form-control" name="jml_komposisi_obat" id="jml_komposisi_obat" style="margin-left: 5px; text-align: center; width: 50px">  </td>
                                <td style="padding-top: 5px"><input type="text" class="form-control" name="satuan_racik" id="satuan_racik" style="margin-left: 5px; width: 100px" placeholder="ex: g/mg"> </td>
                                <td style="padding-top: 5px"><input type="text" class="form-control" name="keterangan_bahan_racik" id="keterangan_bahan_racik" style="margin-left: 5px;"> </td>
                                <td style="padding-top: 5px">&nbsp;<button style="margin-left: 5px; display: none" type="submit" id="add_komposisi_obat"  value="racikan_detail" name="submit" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                                    Tambahkan
                                </button></td>
                            </tr>
                        </table>
                        <!-- cari obat -->
                        <!-- <div class="form-group">
                            <label class="control-label col-sm-2">Cari Obat</label>  
                            <div class="col-md-4">   
                            <input type="text" name="obat" id="inputKeyObatRacikan" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                            <input type="hidden" name="obat" id="inputKeyObatRacikanHidden" class="form-control" placeholder="Masukan Keyword Obat" value=""> 
                            </div>
                            <label class="control-label col-sm-1">Qty</label>
                            <div class="col-md-1">
                            <input type="text" class="form-control" name="jml_komposisi_obat" id="jml_komposisi_obat" style="text-align: center;">  
                            </div>
                            <label class="control-label col-sm-1">Satuan</label>
                            <div class="col-md-2">
                            <input type="text" class="form-control" name="satuan_racik" id="satuan_racik" style="text-align: center;" placeholder="ex: g/mg">  
                            </div>
                            <div class="col-md-1 no-padding">
                                <button type="submit" id="add_komposisi_obat"  value="racikan_detail" name="submit" class="btn btn-xs btn-primary">
                                <i class="ace-icon fa fa-plus icon-on-right bigger-110"></i>
                                    Tambahkan
                                </button>
                            </div>
                        </div> -->

                    </div>

                </div>

                <div id="template_tab" class="tab-pane fade">
                    <p>
                        <b>Template Resep</b><br>
                        <?php echo $value->nama_pegawai?><br>
                        <?php echo $value->nama_bagian?>
                    </p>
                    <table class="table" id="dt_template_resep">
                        <thead>
                        <tr>
                            <th style="width: 30px !important"></th>
                            <th style="width: 30px !important"></th>
                            <th style="width: 150px !important">Nama Resep</th>
                            <th style="width: 250px !important">Deskripsi Resep</th>
                            <th style="width: 30px !important">Resepkan</th>
                            <th style="width: 80px !important">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div id="div_show_resep" style="border: 1px solid #cacaca; padding: 10px; margin-top: 5px">
            <span style="font-weight: bold; font-style: italic">Data e-Resep</span>
            <table class="table" id="dt_add_resep_obat">
                <thead>
                <tr>
                    <th width="30px">No</th>
                    <th>Nama Obat</th>
                    <th width="150px">Signa</th>
                    <th width="60px">Qty</th>
                    <th>Keterangan</th>
                    <th style="width: 80px !important">Aksi</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>