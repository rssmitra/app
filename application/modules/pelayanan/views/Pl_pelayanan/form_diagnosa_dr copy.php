<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">
  
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
  $("#check_resep").change(function() {
        if(this.checked) {
            $('#form_input_resep').show();
        }else{
            $('#form_input_resep').hide();
        }
    });


</script>

<script>
  $( function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#keyword_obat" )
      // don't navigate away from the field on tab when selecting an item
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });
  } );
  </script>
  
<!-- hidden form -->
<input type="hidden" name="flag_form_pelayanan" value="<?php echo ($this->session->userdata('flag_form_pelayanan')) ? $this->session->userdata('flag_form_pelayanan') : 'perawat'?>">

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
    <label for="form-field-8">Diagnosa (ICD10) <span style="color:red">* : </span></label>
    <input type="text" class="form-control" name="pl_diagnosa" id="pl_diagnosa" placeholder="Masukan keyword ICD 10" value="<?php echo isset($riwayat->diagnosa_akhir)?$riwayat->diagnosa_akhir:''?>">
    <input type="hidden" class="form-control" name="pl_diagnosa_hidden" id="pl_diagnosa_hidden" value="<?php echo isset($riwayat->kode_icd_diagnosa)?$riwayat->kode_icd_diagnosa:''?>">
</div>

<div style="margin-top: 6px">
    <label for="form-field-8">Anamnesa <span style="color:red">* : </span> </label>
    <textarea class="form-control" name="pl_anamnesa" style="height: 100px !important"><?php echo isset($riwayat->anamnesa)?$riwayat->anamnesa:''?></textarea>
    <input type="hidden" class="form-control" name="kode_riwayat" id="kode_riwayat" value="<?php echo isset($riwayat->kode_riwayat)?$riwayat->kode_riwayat:''?>">
</div>

<div class="row">
    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Pemeriksaan : </label>
        <textarea name="pl_pemeriksaan" id="pl_pemeriksaan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pemeriksaan)?$riwayat->pemeriksaan:''?></textarea>
    </div>

    <div class="col-md-6" style="margin-top: 6px">
        <label for="form-field-8">Anjuran Dokter : </label>
        <textarea name="pl_pengobatan" id="pl_pengobatan" class="form-control" style="height: 100px !important"><?php echo isset($riwayat->pengobatan)?$riwayat->pengobatan:''?></textarea>
    </div>
</div>

<br>
<p><b><i class="fa fa-file bigger-120"></i> RESEP FARMASI </b></p>
<div style="margin-top: 6px">
    <div class="checkbox" style="margin-left: -20px">
        <label>
        Resep Farmasi ?
        </label>
        <label>
            <?php 
                $checked_resep = ($this->Pl_pelayanan->check_resep_fr($value->kode_bagian, $value->no_registrasi) == true ) ? 'checked' : ''; 
            ?>
            <input name="check_resep" id="check_resep" type="checkbox" class="ace" value="1" <?php echo $checked_resep; ?>>
            <span class="lbl"> Ya</span>
        </label>
    </div>
</div>
<div class="row" id="form_input_resep" <?php echo ($checked_resep == '')?'style="display: none"':''; ?> >
    <div style="margin-top: 6px; padding: 5px !important; padding-left:12px !important" class="col-md-7 no-padding">
        <label for="form-field-8">Cari Nama Obat<span style="color:red">* : </span></label>
        <input type="text" class="form-control" name="keyword_obat" id="keyword_obat" placeholder="Masukan keyword obat" value="">
    </div>
    <div style="margin-top: 6px; padding: 5px !important" class="col-md-2 no-padding">
        <label for="form-field-8">Dosis<span style="color:red">* : </span></label>
        <input type="text" class="form-control" name="dosis" id="dosis" value="" placeholder="EX. 3 x 1">
    </div>
    <div style="margin-top: 6px; padding: 5px !important" class="col-md-2 no-padding">
        <label for="form-field-8">Jumlah<span style="color:red">* : </span></label>
        <input type="text" class="form-control" name="jumlah_obat" id="jumlah_obat" value="" placeholder="ex. 10 TAB" style="text-transform: uppercase">
    </div>
    <div style="margin-top: 35px; margin-left: -2px" class="col-md-1 no-padding">
        <a href="#" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i></a>
    </div>
</div>



<br>
<p><b><i class="fa fa-stethoscope bigger-120"></i> PENUNJANG MEDIS </b></p>

<div style="margin-top: 6px">
    <label for="form-field-8">Penunjang Medis : </label>
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

