<style>
  .monotype_style{
    font-family : Monotype Corsiva, Times, Serif !important;
    font-size: 14px; 
    padding-right: 10px
  }
  .wysiwyg-editor{
    max-height: 500px !important;
    height: 500px !important;
  }
</style>
<div class="row">

  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_copy_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/E_resep_rj/process_copy_resep">      
      
      <!-- hidden form -->
      <input type="hidden" name="kode_pesan_resep" id="kode_pesan_resep" value="<?php echo $kode_pesan_resep?>">
      <div class="row">
        <div class="col-md-12">

          <form class="inline" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      

              <div style="float: left">
                <p><b>COPY RESEP FARMASI</b></p>
              </div>
              <div style="float: right">
                <button type="submit" class="btn btn-success btn-xs">
                    <span class="ace-icon fa fa-print dark icon-on-right bigger-110"></span>
                    Print Copy Resep
                </button>
              </div>
              
              <!-- default form -->
              <div class="row">
                
                <div class="col-sm-12 no-padding">

                  <div class="form-group">
                    <div class="col-md-12">
                      <div class="wysiwyg-editor" id="editor" style="padding-left: 5px">
                        <?php 
                          foreach($eresep as $row){
                            if($row->parent == '0'){
                              if($row->tipe_obat == 'non_racikan'){
                                $config = array(
                                  'dd' => $row->jml_dosis,
                                  'qty' => $row->jml_dosis_obat,
                                  'unit' => $row->satuan_obat,
                                  'use' => $row->aturan_pakai,
                                );
                                $format_signa = $this->master->formatSigna($config);
                                echo '<span class="monotype_style">R/</span><br>';
                                echo '<div style="padding-left: 15px">';
                                echo $row->nama_brg.' &nbsp;&nbsp; No. '.$this->master->formatRomawi((int)$row->jml_pesan).'<br>';
                                echo '<i>'.$format_signa.'</i>';
                                // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row->dosis_per_hari.' x '.$row->dosis_obat.'&nbsp; '.$row->satuan_obat.'  ('.$row->anjuran_pakai.')<br>';
                                echo ' ____________ det / nedet<br><br>';
                                echo '</div>';
                              }else{
                                $config = array(
                                  'nama_obat' => $row->nama_brg,
                                  'dd' => $row->jml_dosis,
                                  'qty' => $row->jml_dosis_obat,
                                  'unit' => $row->satuan_obat,
                                  'use' => $row->aturan_pakai,
                                  'jumlah' => $row->jml_pesan,
                                );
                                $unit_code = $this->master->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($row->satuan_obat)) );
                                // komposisi obat racikan
                                $format_signa_racikan = '<span class="monotype_style">R/</span><br>';
                                $format_signa_racikan .= '<div style="padding-left: 15px">';
                                $format_signa_racikan .= $this->master->get_child_racikan_eresep($kode_pesan_resep, $row->kode_brg);
                                $format_signa_racikan .= '<i>m.f '.$unit_code.' dtd no. '.$this->master->formatRomawi((int)$row->jml_pesan).' da in '.$unit_code.'</i> <br>';
                                $format_signa_racikan .= ''.$this->master->formatSignaFull($config);
                                $format_signa_racikan .= '</div>';
                                echo '<div class="left">'.$format_signa_racikan.'<br>Ket : <br>'.$row->keterangan.'</div>';


                              }
                            }
                          }
                        ?>
                      </div>
                      <textarea spellcheck="false" id="content" name="content" style="display:none"></textarea>
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
                    PopupCenter('farmasi/E_resep_rj/preview_copy_resep/'+jsonResponse.kode_pesan_resep+'','',900,600);
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





