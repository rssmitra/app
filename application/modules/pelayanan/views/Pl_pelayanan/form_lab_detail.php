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

$(document).ready(function(){

  // default tab by penjamin
  // if($('#kode_perusahaan_hidden').val() == 120){
  //   $('.tab-pane a[href="#tab_lab_bpjs"]').tab('show');
  //   $('.tab-pane a[href="#tab_lab_umum"]').tab('hide');
  //   }else{
  //   $('.tab-pane a[href="#tab_lab_bpjs"]').tab('hide');
  //   $('.tab-pane a[href="#tab_lab_umum"]').tab('show');
  // }

  // if( $('#id_pm_tc_penunjang').val() > 0){
  //   $('#btn_proses_order_lab').text('Ubah Permintaan Laboratorium').removeClass('btn btn-xs btn-primary').addClass('btn btn-xs btn-success');
  // }else{
  //   $('#btn_proses_order_lab').text('Simpan Permintaan Laboratorium').removeClass('btn btn-xs btn-success').addClass('btn btn-xs btn-primary');
  // }

  $('#input_keyword').keyup(function(){
      searchedText = $('#input_keyword').val();
      $('span.tbl_nama_pemeriksaan').each(function() {
        string = $(this).text();
        
        if( searchedText != ''){
          var res = string.replaceAll(searchedText, "<b style='color: red'>"+searchedText+"</b>");
          $(this).html(res);
        }else{
          var res = string.replaceAll(searchedText, searchedText);
          $(this).html(res);
        }
      });
  });

  $('#btn_proses_order_lab').click(function (e) {   
    e.preventDefault();

    /*process add tindakan*/
    $.ajax({
        url: "pelayanan/Pl_pelayanan_pm/process_order_lab",
        data: $('#form_pelayanan').serialize(),            
        dataType: "json",
        type: "POST",
        beforeSend: function() {
          achtungShowLoader();   
        },
        success: function (response) {
          /*reset table*/
          if(response.status==200) {
              $.achtung({message: response.message, timeout:5});  

              // getMenuTabs('pelayanan/Pl_pelayanan/form_lab/'+$('#id_pl_tc_poli').val()+'/'+$('#no_kunjungan').val()+'?kode_bag='+$('#kode_bagian_pm').val()+'', 'section_data_order');

              getMenuTabs('registration/Reg_pm/rujuk_pm/'+$('#no_registrasi').val()+'/'+$('#kode_bagian_asal').val()+'/'+$('#kode_klas').val()+'/'+$('#tipe_pelayanan').val()+'', 'tabs_form_pelayanan');

          }else{
            $.achtung({message: response.message, timeout:5, className:'achtungFail'});  
          }
          achtungHideLoader();   
          
        }
    });

  });

});


</script>

<div id="section_order_lab">
  <p style="text-align: center; font-size: 18px; line-height: 20px "><b> FORM PERMINTAAN<br>PEMERIKSAAN LABORATORIUM<br><span style="font-size: 12px !important; font-weight: normal !important">Tanggal <?php echo date('D, d/m/Y')?></span></b></p>
  <hr>
  <?php if(!isset($show)) : ?>
  <input type="hidden" class="form-control" id="id_pm_tc_penunjang" name="id_pm_tc_penunjang" value="<?php echo isset($id_pm_tc_penunjang)?$id_pm_tc_penunjang:''?>">
  <input type="hidden" class="form-control" id="kode_bagian_pm" name="kode_bagian_pm" value="<?php echo isset($sess_kode_bag)?$sess_kode_bag:''?>">
  <input type="hidden" class="form-control" id="kode_bagian_asal" name="kode_bagian_asal" value="<?php echo isset($kode_bagian_asal)?$kode_bagian_asal:''?>">
  <input type="hidden" class="form-control" id="kode_klas" name="kode_klas" value="<?php echo isset($kode_klas)?$kode_klas:''?>">
  <input type="hidden" class="form-control" id="tipe_pelayanan" name="tipe_pelayanan" value="<?php echo isset($tipe_pelayanan)?$tipe_pelayanan:''?>">
  
  <div class="form-group">
    
    <div class="col-sm-6 pull-right" style="text-align: right">
      <a href="#" class="btn btn-xs btn-primary" id="btn_proses_order_lab"> <i class="fa fa-save"></i> Simpan dan Proses Permintaan Penunjang </a>
    </div>
  </div>
  

  <br>
  <div>
    <label for="form-field-8"><b>Cari nama pemeriksaan :</b> </label>
    <input type="text" class="form-control" id="input_keyword" placeholder="Masukan keyword" onkeyup="changeSubStringColor()">
  </div>
  <br>
  <?php endif; ?>

  <div id="section_pemeriksaan_lab">
    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#tab_lab_bpjs">
            BPJS Kesehatan
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#tab_lab_umum">
            Umum & Asuransi
          </a>
        </li>
      </ul>

      <div class="tab-content">
        <div id="tab_lab_bpjs" class="tab-pane fade in active">

          <!-- HEMATOLOGI -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;">
              <td colspan="3" align="center"> HEMATOLOGI </td>
              <td colspan="1" align="center"> FESES </td>
            </tr>
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['HEMATOLOGI'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      if($key_h < 7) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['HEMATOLOGI'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      if($key_h < 15 && $key_h >= 7) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['HEMATOLOGI'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      if($key_h < 25 && $key_h >= 15) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['FESES'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- URIN dan FAAL HATI -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;"><td colspan="2" align="center"> <span>&nbsp;URIN</span> </td><td colspan="2" align="center"> <span>&nbsp;KIMIA DARAH</span> </td></tr>
            <tr><td colspan="2" align="center">&nbsp;</td><td colspan="2" align="center"><b>FAAL HATI</b></td></tr>
            
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['URIN'] as $key_urin=>$row_urin): 
                      $split = explode("|", $row_urin);
                      if($key_urin < 2) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['URIN'] as $key_urin=>$row_urin): 
                      $split = explode("|", $row_urin);
                      if($key_urin < 4 && $key_urin >= 2) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['FAAL_HATI'] as $key_fh=>$row_fh): 
                      $split = explode("|", $row_fh);
                      if($key_fh < 5) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['FAAL_HATI'] as $key_fh=>$row_fh): 
                      $split = explode("|", $row_fh);
                      if($key_fh >= 5 && $key_fh < 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
            </tr>

          </table>

          <!-- DIABETES GINJAL LEMAK ELEKTROLIT -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;">
              <td colspan="4" align="center"> <span>&nbsp;KIMIA DARAH</span> </td>
            </tr>
            <tr>
              <td align="center"><b>DIABETES</b></td>
              <td align="center"><b>LEMAK</b></td>
              <td align="center"><b>GINJAL</b></td>
              <td align="center"><b>ELEKTROLIT</b></td>
            </tr>
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['DIABETES'] as $key_diabet=>$row_diabet): 
                      $split = explode("|", $row_diabet);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php  endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['LEMAK'] as $key_lemak=>$row_lemak): 
                      $split = explode("|", $row_lemak);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>

              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['GINJAL'] as $key_ginjal=>$row_ginjal): 
                      $split = explode("|", $row_ginjal);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['ELEKTROLIT'] as $key_elektr=>$row_elektr): 
                      $split = explode("|", $row_elektr);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- HORMON, HEPATITIS, SEROLOGI DAN IMUNOLOGI PENANDA TUMOR -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;">
              <td colspan="4" align="center"> <span>&nbsp;HORMON/JANTUNG/PROSTAT/SEROLOGI & IMUNOLOGI</span> </td>
            </tr>
            <tr>
              <td align="center"><b>HORMON</b></td>
              <td align="center"><b>JANTUNG</b></td>
              <td align="center" colspan="2"><b>SEROLOGI & IMUNOLOGI</b></td>
            </tr>
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">-</td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['JANTUNG'] as $key_jantung=>$row_jantung): 
                      $split = explode("|", $row_jantung);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php  endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['SEROLOGI_&_IMUNOLOGI'] as $key_si=>$row_si): 
                      $split = explode("|", $row_si);
                      if($key_si < 5) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['SEROLOGI_&_IMUNOLOGI'] as $key_si=>$row_si): 
                      $split = explode("|", $row_si);
                      if($key_si >= 5 && $key_si < 20) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- PENANDA HEPATITIS, TUMOR, PENYAKIT INFEKSI/VIROLOGI -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;">
              <td colspan="4" align="center"> <span>&nbsp;PENANDA HEPATITIS, TUMOR, PENYAKIT INFEKSI/VIROLOGI, AIDS, DRUG ABUSED</span> </td>
            </tr>
            <tr>
              <td align="center"><b>PENANDA HEPATITIS</b></td>
              <td align="center"><b>PENANDA TUMOR</b></td>
              <td align="center"><b>AIDS</b></td>
              <td align="center" colspan="2"><b>DRUG ABUSED</b></td>
            </tr>
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['PENANDA_HEPATITIS'] as $key_ph=>$row_ph): 
                      $split = explode("|", $row_ph);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php  endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['AIDS'] as $key_aids=>$row_aids): 
                      $split = explode("|", $row_aids);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- PARASITOLOGI, BAKTERIOLOGI, CAIRAN TUBUH, MONITOR OBAT -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;">
              <td align="center"> PARASITOLOGI </td>
              <td align="center"> BAKTERIOLOGI </td>
              <td align="center"> CAIRAN TUBUH </td>
              <td align="center"> MONITOR OBAT </td>
            </tr>
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['PARASITOLOGI'] as $key_parasit=>$row_parasit): 
                      $split = explode("|", $row_parasit);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['BAKTERIOLOGI'] as $key_bakteri=>$row_bakteri): 
                      $split = explode("|", $row_bakteri);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- PEMERIKSAAN LAINNYA -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: #e98102e6; font-weight: bold;">
              <td align="center"> TEST KEHAMILAN </td>
              <td align="center"> PATOLOGI ANATOMI </td>
              <td align="center"> PEMERIKSAAN LAINNYA </td>
            </tr>
            <!-- BPJS -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['TEST_KEHAMILAN'] as $key_tkh=>$row_tkh): 
                      $split = explode("|", $row_tkh);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['BPJS']['PATOLOGI_ANATOMI'] as $key_pa=>$row_pa): 
                      $split = explode("|", $row_pa);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>
          
        </div>

        <div id="tab_lab_umum" class="tab-pane fade">
          <!-- HEMATOLOGI -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;">
              <td colspan="3" align="center"> HEMATOLOGI </td>
              <td colspan="1" align="center"> FESES </td>
            </tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['HEMATOLOGI'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      if($key_h < 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['HEMATOLOGI'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      if($key_h < 21 && $key_h >= 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['HEMATOLOGI'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      if($key_h < 40 && $key_h >= 21) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <!-- FESES -->
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['FESES'] as $key_h=>$row_h): 
                      $split = explode("|", $row_h);
                      
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- URIN dan FAAL HATI -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;"><td colspan="2" align="center"> <span>&nbsp;URIN</span> </td><td colspan="2" align="center"> <span>&nbsp;KIMIA DARAH</span> </td></tr>
            <tr><td colspan="2" align="center">&nbsp;</td><td colspan="2" align="center"><b>FAAL HATI</b></td></tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['URIN'] as $key_urin=>$row_urin): 
                      $split = explode("|", $row_urin);
                      if($key_urin < 9) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                <?php 
                    foreach($pemeriksaan['UMUM']['URIN'] as $key_urin=>$row_urin): 
                      $split = explode("|", $row_urin);
                      if($key_urin < 20 && $key_urin >=9) :
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                  <tr><td colspan="2" style="font-size: 10px !important"><b>EKSKRESI 24 JAM URIN</b></td></tr>
                  <?php 
                    foreach($pemeriksaan['UMUM']['EKSKRESI_24_JAM_(URINE_24_JAM)'] as $key_urin=>$row_urin): 
                      $split = explode("|", $row_urin);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['FAAL_HATI'] as $key_fh=>$row_fh): 
                      $split = explode("|", $row_fh);
                      if($key_fh < 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['FAAL_HATI'] as $key_fh=>$row_fh): 
                      $split = explode("|", $row_fh);
                      if($key_fh < 30 && $key_fh >= 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- DIABETES GINJAL LEMAK ELEKTROLIT -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;">
              <td colspan="4" align="center"> <span>&nbsp;KIMIA DARAH</span> </td>
            </tr>
            <tr>
              <td align="center"><b>DIABETES</b></td>
              <td align="center"><b>LEMAK</b></td>
              <td align="center"><b>GINJAL</b></td>
              <td align="center"><b>ELEKTROLIT</b></td>
            </tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['DIABETES'] as $key_diabet=>$row_diabet): 
                      $split = explode("|", $row_diabet);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php  endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['LEMAK'] as $key_lemak=>$row_lemak): 
                      $split = explode("|", $row_lemak);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>

              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['GINJAL'] as $key_ginjal=>$row_ginjal): 
                      $split = explode("|", $row_ginjal);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['ELEKTROLIT'] as $key_elektr=>$row_elektr): 
                      $split = explode("|", $row_elektr);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- HORMON, HEPATITIS, SEROLOGI DAN IMUNOLOGI PENANDA TUMOR -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;">
              <td colspan="4" align="center"> <span>&nbsp;HORMON/JANTUNG/PROSTAT/SEROLOGI & IMUNOLOGI</span> </td>
            </tr>
            <tr>
              <td align="center"><b>HORMON</b></td>
              <td align="center"><b>JANTUNG</b></td>
              <td align="center" colspan="2"><b>SEROLOGI & IMUNOLOGI</b></td>
            </tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['HORMON'] as $key_hormon=>$row_hormon): 
                      $split = explode("|", $row_hormon);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php  endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['JANTUNG'] as $key_jantung=>$row_jantung): 
                      $split = explode("|", $row_jantung);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <tr><td colspan="2" align="center" style="font-size: 10px !important"><b>PROSTAT</b></td></tr>
                  <?php 
                    foreach($pemeriksaan['UMUM']['PROSTAT'] as $key_prostat=>$row_prostat): 
                      $split = explode("|", $row_prostat);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>

                </table>
              </td>

              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['SEROLOGI_&_IMUNOLOGI'] as $key_si=>$row_si): 
                      $split = explode("|", $row_si);
                      if($key_si < 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['SEROLOGI_&_IMUNOLOGI'] as $key_si=>$row_si): 
                      $split = explode("|", $row_si);
                      if($key_si >= 10 && $key_si < 20) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              
            </tr>
          </table>

          <!-- PENANDA HEPATITIS, TUMOR, PENYAKIT INFEKSI/VIROLOGI -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;">
              <td colspan="4" align="center"> <span>&nbsp;PENANDA HEPATITIS, TUMOR, PENYAKIT INFEKSI/VIROLOGI, AIDS, DRUG ABUSED</span> </td>
            </tr>
            <tr>
              <td align="center"><b>PENANDA HEPATITIS</b></td>
              <td align="center"><b>PENANDA TUMOR</b></td>
              <td align="center"><b>AIDS</b></td>
              <td align="center" colspan="2"><b>DRUG ABUSED</b></td>
            </tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['PENANDA_HEPATITIS'] as $key_ph=>$row_ph): 
                      $split = explode("|", $row_ph);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php  endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['PENANDA_TUMOR'] as $key_pt=>$row_pt): 
                      $split = explode("|", $row_pt);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>

                </table>
              </td>

              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['AIDS'] as $key_aids=>$row_aids): 
                      $split = explode("|", $row_aids);
                      if($key_aids < 10) :
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endif; endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['DRUG_ABUSED'] as $key_drug=>$row_drug): 
                      $split = explode("|", $row_drug);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              
            </tr>
          </table>

          <!-- PARASITOLOGI, BAKTERIOLOGI, CAIRAN TUBUH, MONITOR OBAT -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;">
              <td align="center"> PARASITOLOGI </td>
              <td align="center"> BAKTERIOLOGI </td>
              <td align="center"> CAIRAN TUBUH </td>
              <td align="center"> MONITOR OBAT </td>
            </tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['PARASITOLOGI'] as $key_parasit=>$row_parasit): 
                      $split = explode("|", $row_parasit);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['BAKTERIOLOGI'] as $key_bakteri=>$row_bakteri): 
                      $split = explode("|", $row_bakteri);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['CAIRAN_TUBUH'] as $key_ct=>$row_ct): 
                      $split = explode("|", $row_ct);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['MONITOR_OBAT'] as $key_mo=>$row_mo): 
                      $split = explode("|", $row_mo);
                      
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

          <!-- PEMERIKSAAN LAINNYA -->
          <table style="font-size: 10px; width: 100%">
            <tr style="color: white; background: darkgreen; font-weight: bold;">
              <td align="center"> TEST KEHAMILAN </td>
              <td align="center"> PATOLOGI ANATOMI </td>
              <td align="center"> PEMERIKSAAN LAINNYA </td>
            </tr>
            <!-- UMUM -->
            <tr>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['TEST_KEHAMILAN'] as $key_tkh=>$row_tkh): 
                      $split = explode("|", $row_tkh);
                  ?>
                  <tr>
                    <td style="vertical-align: top;">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['PATOLOGI_ANATOMI'] as $key_pa=>$row_pa): 
                      $split = explode("|", $row_pa);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
              <td style="vertical-align: top; width: 25%">
                <table>
                  <?php 
                    foreach($pemeriksaan['UMUM']['LAINNYA'] as $key_dll=>$row_dll): 
                      $split = explode("|", $row_dll);
                  ?>
                  <tr>
                    <td style="vertical-align: top">
                        <label style="padding : 1px;">
                          <input name="selected_pemeriksaan[<?php echo $split[0]?>]" type="checkbox" value="<?php echo $split[1]?>" class="ace" <?php echo isset($last_order[$split[0]]) ? 'checked' : '' ?> >
                          <span class="lbl"></span>
                        </label>
                    </td>
                    <td style="font-size: 10px">
                      <span class="tbl_nama_pemeriksaan"><?php echo $split[1]?></span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </table>
              </td>
            </tr>
          </table>

        </div>
      </div>
    </div>

  </div>

</div>





