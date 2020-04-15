<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<style>

  .body_print{
    padding: 10px;
  }

  .body_print, table, p{
  font-family: calibri;
  font-size: 12px;
  background-color: white;
  }
  .table-utama{
  border: 1px solid black;
  border-collapse: collapse;
  }
  th, td {
  padding: 2px;
  text-align: left;
  }
  @media print{ #barPrint{
      display:none;
    }
  }

  .stamp {
      margin-top: -96px;
      margin-left: 600px;
      position: absolute;
      display: inline-block;
      color: black;
      padding: 1px;
      padding-left: 10px;
      padding-right: 10px;
      background-color: white;
      box-shadow:inset 0px 0px 0px 0px;
      /*opacity: 0.5;*/
      -webkit-transform: rotate(25deg);
      -moz-transform: rotate(25deg);
      -ms-transform: rotate(25deg);
      -o-transform: rotate(25deg);
      transform: rotate(0deg);
      
  }
  
</style>

<div class="body_print">
  
  <table width="100%" border="0">
    <tr>
      <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
      <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td align="right"></td>
    </tr>
  </table>
  <hr>      
  <div class="row">
  <div class="col-xs-12">
    <table border="0" width="100%">
      <tr>
        <td width="50%">
          <table width="100%" style="font-size:12px">
            <tr>
              <td colspan="2"><b>DATA PASIEN</b></td>
            </tr>
            <tr>
              <td width="30%">No MR</td>
              <td>: <?php echo $data->reg_data->no_mr?></td>
            </tr>
            <tr>
              <td>Nama Pasien</td>
              <td>: <?php echo $data->reg_data->nama_pasien?></td>
            </tr>
            <!-- <tr>
              <td>TTL</td>
              <td>: <?php echo $data->reg_data->tempat_lahir?>, <?php echo $this->tanggal->formatDate($data->reg_data->tgl_lhr)?></td>
            </tr>
            <tr>
              <td>Umur Pasien</td>
              <td>: <?php echo $data->reg_data->umur?> Tahun</td>
            </tr> -->
            <tr>
              <td>Tanggal</td>
              <td>: <?php echo $this->tanggal->formatDateTime($data->reg_data->tgl_jam_masuk)?></td>
            </tr>
            <tr>
              <td>Poli/Klinik</td>
              <td>: <?php echo ucwords($data->reg_data->bagian_masuk_field)?></td>
            </tr>
            <tr>
              <td>Dokter</td>
              <td>: <?php echo $data->reg_data->nama_pegawai?></td>
            </tr>
            <tr>
              <td>Penjamin</td>
              <td>: <?php echo isset($data->reg_data->nama_perusahaan)?$data->reg_data->nama_perusahaan:'Umum'?> <?php echo ($data->reg_data->kode_perusahaan==120) ? '( '.$data->reg_data->no_sep.' )' :'';?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    
      <hr>
      <!-- PAGE CONTENT BEGINS -->
      <?php if( count($kunjungan) > 0 ) : ?>
      <?php $no_key=1; foreach($kunjungan as $key=>$row_dt_kunj) : $no_key++; ?>
                      
      <div class="timeline-container timeline-style2">
          <span class="timeline-label" style="width:110px !important">
              <b><span style="font-size: 14px; color: black"><?php echo ucwords($key)?></span></b>
          </span>

          <div class="timeline-items">
              <?php 
                  foreach($row_dt_kunj as $key_s=>$row_s) : 
              ?>
                  
              <!-- <div class="timeline-item clearfix"> -->
                  <!-- <div class="timeline-info">

                      <span class="timeline-date"> 
                          <?php echo ($row_s[0]->tgl_keluar==NULL) ? '<i class="fa fa-times-circle bigger-120 red"></i>' : '<i class="fa fa-check bigger-120 green"></i>' ;?> <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_masuk)?> s/d <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_keluar)?>
                      </span>

                      <i class="timeline-indicator btn btn-info no-hover"></i>
                  </div> -->

                  <div class="widget-box transparent">
                      <div class="widget-body">
                          <div class="widget-main no-padding">
                              <?php echo '<span style="font-size: 14px; font-weight: bold">'.ucwords($key_s).'</span>';?> ( <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_masuk)?> s/d <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_keluar)?> )
                              <table class="table-2 table-striped table-bordered" width="100%" style="color: black">
                                  <tr style="background-color: lightgrey;">
                                      <th> Deskripsi Item</th>
                                      <th style="text-align:right" width="100px">Subtotal (Rp.)</th>
                                  </tr>

                              <?php 
                                $sum_array[$no_key][$key_s] = array();
                                foreach( $row_s as $value_data ) : 

                                  $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#d3d3d321':'#d3d3d321';
                                  $checkbox = ($value_data->kode_tc_trans_kasir==NULL)?'<input type="checkbox" name="selected_bill[]" value="'.$value_data->kode_trans_pelayanan.'" checked>':'';
                                  $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>'; 

                                  if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
                                    if($value_data->kode_tc_trans_kasir != NULL) : 
                                      $subtotal = $this->Billing->get_total_tagihan($value_data);
                                      $sum_array[$no_key][$key_s][] = $subtotal;
                              ?>
                                  
                                  <tr id="tr_<?php echo $value_data->kode_trans_pelayanan?>" style="background-color:<?php echo $sign_pay?>">
                                      <td>
                                          <?php echo $value_data->kode_trans_pelayanan.' - '.$value_data->nama_tindakan;?>
                                      </td>
                                      <td style="text-align: right">
                                          <span id="subtotal_<?php echo $value_data->kode_trans_pelayanan?>"><?php echo number_format($subtotal)?>,-</span>
                                      </td>
                                  </tr>
                              <?php 
                                  
                                    endif;
                                  else: 
                              ?>
                                  <?php 
                                    $subtotal = $this->Billing->get_total_tagihan($value_data);
                                    $sum_array[$no_key][$key_s][] = $subtotal;
                                  ?>
                                  <tr id="tr_<?php echo $value_data->kode_trans_pelayanan?>" style="background-color:<?php echo $sign_pay?>">
                                      <td>
                                          <?php echo $value_data->kode_trans_pelayanan.' - '.$value_data->nama_tindakan;?>
                                      </td>
                                      <td style="text-align: right">
                                          <span id="subtotal_<?php echo $value_data->kode_trans_pelayanan?>"><?php echo number_format($subtotal)?>,-</span>
                                      </td>
                                  </tr>
                              <?php 
                                  endif; 
                                  endforeach; 

                                  $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]);
                              ?>

                              <tr style="font-weight: bold; font-size: 13px">
                                  <td align="right">Total</td>
                                  <td style="text-align: right"><?php echo number_format(array_sum($sum_array[$no_key][$key_s]))?></td>
                              </tr>
                                
                              </table>

                          </div>
                      </div>
                  </div>
              <!-- </div> -->

              <?php 
                      
              endforeach;
              ?>
              
          </div><!-- /.timeline-items -->
      </div>
      
      <?php endforeach?>
      <!-- footer -->
      <div width="98%" style="padding-left: 0%; padding-right: 1%">
        <span style="font-size: 14px">Total biaya keseluruhan : <b>Rp. <?php echo number_format(array_sum($arr_sum_total))?></b></span>
        <br>
        Terbilang : <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_sum_total)))?>"</i></b>
        <br><br><br>
        <table border="0" width="100%">
          <tr>
            <td style="text-align: right">
              Jakarta, <?php echo date('d/m/Y')?>
              <br><br><br><br><br>
              <?php if( $flag_bill == 'temporary' ) : ?>
              <div class="col-xs-4">
              <span style="margin-left:-80%; margin-top: -15%; font-size: 24px" class="stamp center">BILLING<br>SEMENTARA</span>
              </div>
              <?php endif;?>
              ( <?php echo $this->session->userdata('user')->fullname?> )
            </td>
          </tr>
        </table>
      </div>
      <?php else: echo '<center><h3>Tidak ada data billing ditemukan!</h3></center>'; endif;?>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div>


