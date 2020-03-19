<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small><i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 
        
    <form class="form-horizontal" method="post" id="form_copy_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/Etiket_obat/process_copy_resep">      
      
      <!-- hidden form -->
      <input type="hidden" name="kode_trans_far" id="kode_trans_far" value="<?php echo isset($value)?ucwords($value->kode_trans_far):''?>">
      <div class="row">

        <div class="col-sm-12">

          <div class="col-xs-6">
            <h4><?php echo isset($value)?ucwords($value->kode_trans_far):''?> - <?php echo isset($value)?ucwords($value->nama_pasien):''?> (<?php echo isset($value)?ucwords($value->no_resep):''?>) </h4>
          </div>
        </div>

      </div>

      <hr>

      <div class="row">
        <div class="col-md-12">

          <p><b>COPY RESEP FARMASI</b></p>

          <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
              <!-- default form -->
              <div class="row">
                
                <div class="col-sm-12 no-padding">

                  <div class="form-group">
                    <label class="control-label col-md-2">Tulis Resep</label>
                    <div class="col-md-10">
                      <div class="wysiwyg-editor" id="editor"><?php 
                          foreach($detail_obat as $row){
                            echo $row->nama_brg.'<br>';
                            echo '&nbsp;&nbsp;&nbsp;'.$row->dosis_obat.' x '.$row->dosis_per_hari.' (hari) &nbsp; '.$row->jumlah_obat.'&nbsp;&nbsp;'.$row->satuan_obat.'  ('.$row->anjuran_pakai.')';
                            echo '<br>';
                          }
                        ?></div>
                      <textarea spellcheck="false" id="content" name="content" style="display:none"></textarea>
                    </div>
                  </div>

                  <div class="form-group" style="margin-top: 10px">
                    <label class="col-md-2">&nbsp;</label>
                    <div class="col-md-10">
                      <button type="submit" class="btn btn-primary btn-xs">
                          <span class="ace-icon fa fa-print icon-on-right bigger-110"></span>
                          Cetak Copy Resep
                      </button>
                    </div>
                  </div>
                  
                </div>

              </div>

            </form>
            
        </div>
      </div>

    </form>


  </div>

</div><!-- /.row -->

<script src="<?php echo base_url()?>/assets/js/jquery-ui.custom.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.ui.touch-punch.js"></script>
<script src="<?php echo base_url()?>/assets/js/markdown/markdown.js"></script>
<script src="<?php echo base_url()?>/assets/js/markdown/bootstrap-markdown.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.hotkeys.js"></script>
<script src="<?php echo base_url()?>/assets/js/bootstrap-wysiwyg.js"></script>
<script src="<?php echo base_url()?>/assets/js/bootbox.js"></script>

<script type="text/javascript">
    jQuery(function($) {
      $('#editor').ace_wysiwyg({
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
          {
            name:'insertImage',
            placeholder:'Custom PlaceHolder Text',
            button_class:'btn-inverse',
            //choose_file:false,//hide choose file button
            button_text:'Set choose_file:false to hide this',
            button_insert_class:'btn-pink',
            button_insert:'Insert Image'
          },
          null,
          {
            name:'foreColor',
            title:'Custom Colors',
            values:['red','green','blue','navy','orange'],
            /**
              You change colors as well
            */
          },
          /**null,
          {
            name:'backColor'
          },*/
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

      
      
      //handle form onsubmit event to send the wysiwyg's content to server
      $('#form_copy_resep').on('submit', function(){
        
        //put the editor's html content inside the hidden input to be sent to server
        
        $('#content').val($('#editor').html());

        var formData = new FormData($('#form_copy_resep')[0]);
        /*formData.append('content', $('input[name=wysiwyg-value]' , this).val($('#editor').html()) ); */
        //pf_file_name = new Array();
        pf_file = new Array();

        var formData = new FormData($('#form_copy_resep')[0]);
        
        i=0;
          
        url = $('#form_copy_resep').attr('action');

            // ajax adding data to database
              $.ajax({
                url : url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                
                beforeSend: function() {
                  achtungShowLoader();  
                },
                uploadProgress: function(event, position, total, percentComplete) {
                },
                complete: function(xhr) {     
                  var data=xhr.responseText;
                  var jsonResponse = JSON.parse(data);

                  if(jsonResponse.status === 200){
                    $.achtung({message: jsonResponse.message, timeout:5});
                    PopupCenter('farmasi/Etiket_obat/preview_copy_resep/'+jsonResponse.kode_trans_far+'','',900,600);
                  }else{
                    $.achtung({message: jsonResponse.message, timeout:5});
                  }
                  achtungHideLoader();
                }
            });

        return false;
      });
      $('#form_copy_resep').on('reset', function() {
        $('#editor').empty();
      });
    });

    

  </script>





