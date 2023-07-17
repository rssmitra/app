<script>
  function cetak_hasil_expertise() {
    window.open('Templates/Export_data/export?type=pdf&flag=RAD&noreg='+$('#no_registrasi_pm').val()+'&pm='+$('#kode_penunjang_pm').val()+'&kode_pm='+$('#kode_bag_tujuan_pm').val()+'&no_kunjungan='+$('#no_kunjungan_pm').val()+'', '_blank');
  }
</script>

<!-- hidden form -->
<input type="hidden" name="kode_penunjang_pm" id="kode_penunjang_pm" value="<?php echo isset($kunjungan->kode_penunjang)?$kunjungan->kode_penunjang:0?>">
<input type="hidden" name="no_kunjungan_pm" id="no_kunjungan_pm" value="<?php echo isset($kunjungan->no_kunjungan)?$kunjungan->no_kunjungan:0?>">
<input type="hidden" name="no_registrasi_pm" id="no_registrasi_pm" value="<?php echo isset($kunjungan->no_registrasi)?$kunjungan->no_registrasi:0?>">
<input type="hidden" name="kode_bag_tujuan_pm" id="kode_bag_tujuan_pm" value="<?php echo isset($kunjungan->kode_bagian_tujuan)?$kunjungan->kode_bagian_tujuan:''?>">

<div class="pull-right" style="padding-bottom: 3px">
  <div class="form-group">
    <div class="col-sm-12 no-padding">
      <button type="button" class="btn btn-xs btn-inverse" id="btn_print_hasil" onclick="cetak_hasil_expertise()"> <i class="fa fa-print"></i> Cetak Hasil Pemeriksaan </button>
      <button type="submit" class="btn btn-xs btn-primary" id="btn_save"> <i class="fa fa-save"></i> Simpan Hasil Pemeriksaan</button>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">

      <?php
        $no = 0;
        foreach ($pemeriksaan as $k => $v) :
          $no++;
      ?>
        <!-- hidden form -->
        <input type="hidden" name="kode_mt_hasilpm[<?php echo $v->kode_trans_pelayanan; ?>]" value="<?php echo $v->kode_mt_hasilpm; ?>">
        <input type="hidden" name="kode_trans_pelayanan[]" value="<?php echo $v->kode_trans_pelayanan; ?>">
        <div class="panel-body">
          <span>Nama Pemeriksaan <?php echo $no; ?>:</span><br>
          <span style="font-weight: bold"><?php echo strtoupper($v->nama_tindakan)?></span>
          <!-- <div class="wysiwyg-editor" id="editor_konten_<?php echo $no?>" style="height: 300px !important">
            <?php echo isset($v->hasil)?$v->hasil:''?>
          </div> -->
          <textarea  id="konten<?php echo $no?>" name="hasil_pemeriksaan[<?php echo $v->kode_trans_pelayanan; ?>]" class="form-control" style="height: 200px !important"><?php echo isset($v->hasil)?$this->master->br2nl($v->hasil):''?></textarea>

          <span style="font-weight: bold">Kesan</span>
          <!-- <div class="wysiwyg-editor" id="editor_konten_<?php echo $no?>" style="height: 300px !important">
            <?php echo isset($v->hasil)?$v->hasil:''?>
          </div> -->
          <textarea  id="kesan<?php echo $no?>" name="kesan_pemeriksaan[<?php echo $v->kode_trans_pelayanan; ?>]" class="form-control" style="height: 100px !important"><?php echo isset($v->kesan)?$this->master->br2nl($v->kesan):''?></textarea>
          <br>
          <hr>

        </div>
      <?php endforeach; ?>

  </div>

  <div class="col-sm-12" style="margin-top: 10px"> 
      <span style="font-weight: bold">Catatan Pemeriksaan : </span><br>
      <textarea name="catatan_hasil" id="catatan_hasil" class="form-control" cols="50" style="height:100px !important;"><?php echo isset($pemeriksaan[0]->catatan_hasil)?$pemeriksaan[0]->catatan_hasil:'';?></textarea>
  </div>


</div>


<script src="<?php echo base_url()?>/assets/js/jquery-ui.custom.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url()?>/assets/js/markdown/markdown.js"></script>
<script src="<?php echo base_url()?>/assets/js/markdown/bootstrap-markdown.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.hotkeys.js"></script>
<script src="<?php echo base_url()?>/assets/js/bootstrap-wysiwyg.js"></script>
<script src="<?php echo base_url()?>/assets/js/bootbox.js"></script>

<script type="text/javascript">
    jQuery(function($) {
      $('#editor_konten_1, #editor_konten_2, #editor_konten_3, #editor_konten_4').ace_wysiwyg({
        toolbar:
        [
          {
            name:'font',
            title:'Custom tooltip',
            values:['Some Font!','Arial','Verdana','Comic Sans MS','Custom Font!']
          },
          null,
          {
            name:'fontSize',
            title:'Custom tooltip',
            values:{1 : 'Size#1 Text' , 2 : 'Size#1 Text' , 3 : 'Size#3 Text' , 4 : 'Size#4 Text' , 5 : 'Size#5 Text'} 
          },
          null,
          {name:'bold', title:'Custom tooltip'},
          {name:'italic', title:'Custom tooltip'},
          {name:'strikethrough', title:'Custom tooltip'},
          {name:'underline', title:'Custom tooltip'},
          null,
          'insertunorderedlist',
          'insertorderedlist',
          'outdent',
          'indent',
          null,
          {name:'justifyleft'},
          {name:'justifycenter'},
          {name:'justifyright'},
          {name:'justifyfull'},
          null,
          {
            name:'createLink',
            placeholder:'Custom PlaceHolder Text',
            button_class:'btn-purple',
            button_text:'Custom TEXT'
          },
          {name:'unlink'},
          null,
          {name:'undo'},
          {name:'redo'},
          null,
          'viewSource'
        ],
        //speech_button:false,//hide speech button on chrome
        
        'wysiwyg': {
          hotKeys : {} //disable hotkeys
        }
        
      }).prev().addClass('wysiwyg-style2');

      $('#form_pelayanan').on('reset', function() {
        $('#editor_konten_1').empty();
        $('#editor_konten_2').empty();
        $('#editor_konten_3').empty();
        $('#editor_konten_4').empty();
        $('#editor_konten_5').empty();
      });

    });

</script>