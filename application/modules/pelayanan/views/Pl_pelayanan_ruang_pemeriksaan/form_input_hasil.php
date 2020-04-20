<script>
  function cetak_hasil_expertise(kode_expertise, title) {
    
  }
</script>

<style>
    .wysiwyg-editor{
        max-height: 700px !important;
    }
</style>

<!-- hidden form -->
<input type="hidden" name="kode_expertise" id="kode_expertise" value="<?php echo isset($riwayat->kode_expertise)?$riwayat->kode_expertise:0?>">
<input type="hidden" name="jenis_expertise" id="jenis_expertise" value="<?php echo $jenis_expertise?>">
<input type="hidden" name="kode_bag_expertise" id="kode_bag_expertise" value="<?php echo $kode_bag_expertise?>">

<div class="pull-left">
  <p style="font-size: 15px">INPUT HASIL PEMERIKSAAN<br>USG Abdomen Full</p>
</div>

<div class="pull-right" style="padding-bottom: 3px">
  <div class="form-group">
    <div class="col-sm-12 no-padding">
      <button type="button" class="btn btn-xs btn-inverse" id="btn_print_hasil" onclick="cetak_hasil_expertise('<?php echo isset($riwayat->kode_expertise)?$riwayat->kode_expertise:0?>','<?php echo $jenis_expertise?>')"> <i class="fa fa-print"></i> Cetak Hasil Pemeriksaan </button>
      <button type="button" class="btn btn-xs btn-primary" id="btn_delete_hasil"> <i class="fa fa-save"></i> Simpan Hasil Pemeriksaan</button>
    </div>
  </div>
</div>

<div class="form-group">
  <div class="col-md-12 no-padding">
    <div class="wysiwyg-editor" id="editor_konten" style="height: 700px !important">
      <?php echo isset($riwayat->hasil_expertise)?$riwayat->hasil_expertise:''?>
    </div>
    <textarea spellcheck="false" id="konten" name="konten" style="display:none"></textarea>
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
      $('.wysiwyg-editor').ace_wysiwyg({
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
        $('#editor_konten').empty();
      });

    });

</script>