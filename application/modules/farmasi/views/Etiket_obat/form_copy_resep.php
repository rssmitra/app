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
      <left><span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
      No. <?php echo isset($value->kode_trans_far)?$value->kode_trans_far:''; ?> - <?php echo isset($value->no_resep)?$value->no_resep:''; ?>
      </span></left>

      <div class="row">
        <div class="col-md-4">
          <table>
            <tr style="">
              <td width="100px">No. SEP</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->no_sep)?$value->no_sep:'' ?></td>
            </tr>
            <tr style="">
              <td width="100px">No. MR</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->no_mr)?$value->no_mr:''?></td>
            </tr>
            <tr style="">
              <td width="100px">Nama Pasien</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->nama_pasien)?$value->nama_pasien:''?></td>
            </tr>
          </table>
        </div>

        <div class="col-md-4">
          <table>
          
            <tr style="">
              <td width="100px">Tanggal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($value->tgl_trans) ?></td>
            </tr>
            <tr style="">
              <td width="100px">Dokter</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo isset($value->dokter_pengirim)?$value->dokter_pengirim:''?></td>
            </tr>
            <tr style="">
              <td width="100px">Poli Asal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $detail_obat[0]['nama_bagian']?></td>
            </tr>
          </table>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-12">

          <form class="inline" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      

              <div style="float: left">
                <p><b>COPY RESEP FARMASI</b></p>
              </div>
              <div style="float: right">

                <button type="button" onclick="getMenu('farmasi/Process_entry_resep/preview_entry/<?php echo isset($value->kode_trans_far)?$value->kode_trans_far:''; ?>?flag=<?php echo $flag; ?>');" class="btn btn-xs btn-default" title="Kembali ke Resep Rawat Jalan">
                    <i class="fa fa-arrow-left dark"></i> Kembali sebelumnya
                </button>

                <button type="submit" class="btn btn-success btn-xs">
                    <span class="ace-icon fa fa-print dark icon-on-right bigger-110"></span>
                    Print Copy Resep
                </button>

                <button type="button" onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo isset($value->kode_trans_far)?$value->kode_trans_far:''; ?>')" class="btn btn-xs btn-warning" title="create_copy_resep">
                    <i class="fa fa-print dark"></i> Nota Farmasi
                </button>
                <button type="button" onclick="getMenu('farmasi/Etiket_obat/form/<?php echo isset($value->kode_trans_far)?$value->kode_trans_far:''; ?>?flag=<?php echo $flag; ?>')" class="btn btn-xs btn-primary" title="etiket">
                  <i class="fa fa-ticket dark"></i> Etiket Obat
                </button>
              </div>
              
              <!-- default form -->
              <div class="row">
                
                <div class="col-sm-12 no-padding">

                  <div class="form-group">
                    <div class="col-md-8">
                      <span>Silahkan tulis resep dibawah ini :</span>
                      <div class="wysiwyg-editor" id="editor" style="padding-left: 5px">
                        <?php 
                          foreach($detail_obat as $row){

                            if($row['flag_resep'] == 'biasa'){
                              $config = array(
                                'dd' => $row['dosis_per_hari'],
                                'qty' => $row['dosis_obat'],
                                'unit' => $row['satuan_obat'],
                                'use' => $row['anjuran_pakai'],
                              );
                              $jumlah_tebus = $row['jumlah_tebus'] + $row['jumlah_obat_23'];
                              $format_signa = $this->master->formatSigna($config);
                              echo '<span class="monotype_style">R/</span><br>';
                              echo '<div style="padding-left: 15px">';
                              echo $row['nama_brg'].' &nbsp;&nbsp; No. '.$this->master->formatRomawi((int)$jumlah_tebus).'<br>';
                              echo '<i>'.$format_signa.'</i>';
                              // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['dosis_per_hari'].' x '.$row['dosis_obat'].'&nbsp; '.$row['satuan_obat'].'  ('.$row['anjuran_pakai'].')<br>';
                              echo ' ____________ det / nedet<br><br>';
                              echo '</div>';
                            }else{
                              echo '<span class="monotype_style">R/</span><br>';
                              echo '<div style="padding-left: 15px">';
                              echo '<table>';
                              $first_dt = $row['racikan'][0];
                              foreach ($row['racikan'][0] as $key => $value) {

                                echo '<tr>';  
                                echo '<td width="70%">'.$value->nama_brg.'</td>';  
                                echo '<td width="30%" style="padding-left: 10px">'.$value->jumlah.' '.strtolower($value->satuan).'</td>';  
                                echo '</tr>';  
                              }
                              echo '</table>';
                              $unit_code = $this->master->get_string_data('reff_id', 'global_parameter', array('flag' => 'satuan_obat', 'value' => ucfirst($first_dt[0]->satuan_racikan)) );
                              
                              echo '<i>m.f '.$unit_code.' dtd no. '.$this->master->formatRomawi((int)$row['jumlah_tebus']).' da in '.$unit_code.'</i> <br>';

                              $config_racikan = array(
                                'dd' => $first_dt[0]->dosis_per_hari,
                                'qty' => $first_dt[0]->dosis_obat,
                                'unit' => $first_dt[0]->satuan_racikan,
                                'use' => $first_dt[0]->anjuran_pakai,
                              );
                              // format signa racikan

                              $format_signa_racikan = $this->master->formatSigna($config_racikan);
                              // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['dosis_per_hari'].' x '.$row['dosis_obat'].'&nbsp; '.$row['satuan_obat'].'  ('.$row['anjuran_pakai'].')<br>';
                              echo '<i>'.$format_signa_racikan.'</i>';
                              echo ' ____________ det / nedet<br><br>';
                              echo '</div>';
                            }
                            
                          }
                        ?>
                      </div>
                      <textarea spellcheck="false" id="content" name="content" style="display:none"></textarea>
                    </div>
                    <div class="col-md-4">
                      <address style="padding-top: 20px">
                        Format Signa: <br>

                        {signa}.{aturan pakai} {satuan} {jumlah obat dalam romawi} {waktu pemakaian}<br><br>
                        ex. <i> <b> S.sdd TAB I p.c</b> </i><br>
                        1 x sehari 1 tablet Sesudah Makan
                        <br><br>
                        <b>S</b> = Initial Signa <br>
                        <b>sdd</b> = 1 x sehari <br>
                        <b>TAB</b> = Tab <br>
                        <b>I</b> = Jumlah obat 1 <br>
                        <b>p.c</b> = Sesudah Makan <br>
                        <br>
                        ex. <i> <b> S.bdd CAP II a.c</b> </i><br>
                        2 x sehari 2 Kapsul Sebelum Makan
                        <br><br>
                        ex. <i> <b> S.tdd tube I d.c</b> </i><br>
                        3 x sehari 1 tube Bersamaan Makan

                      </address>
                    </div>
                  </div>

                  <div class="form-group" style="margin-top: 18px">
                    <div class="col-md-10">
                      

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





