<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  
  <style>
  
    .body_print{
      padding: 10px;
    }
  
    .body_print, table, p{
    /* font-family: calibri; */
    font-size: 12px;
    background-color: white;
    }
    .table-utama{
    border: 1px solid black;
    border-collapse: collapse;
    }
    th, td {
    padding: 0px;
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
</head>
<body>

<div class="body_print">
  
  <table width="500px" border="0">
    <tr>
      <!-- <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td> -->
      <td valign="bottom"><b><span style="font-size: 14px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td width="160px"></td>
    </tr>
    <tr>
      <td align="center" colspan="2" style="font-size:18px"><center><b>Rincian Biaya Pasien</b></center></td>
    </tr>
  </table>
  <hr>      
  <div class="row">
  <div class="col-xs-12">
    <table width="500px" style="font-size:14px">
      
      <tr>
        <td width="25%">No MR</td>
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
      <!-- <tr>
        <td>Penjamin</td>
        <td>: <?php echo isset($data->reg_data->nama_perusahaan)?$data->reg_data->nama_perusahaan:'Umum'?> <?php echo ($data->reg_data->kode_perusahaan==120) ? '( '.$data->reg_data->no_sep.' )' :'';?></td>
      </tr> -->
    </table>
    
      <hr>
      <!-- PAGE CONTENT BEGINS -->
      <?php if( count($kunjungan) > 0 ) : ?>
      <?php $no_key=1; foreach($kunjungan as $key=>$row_dt_kunj) : $no_key++; ?>
          
        <span style="font-size: 14px; color: black">Tanggal, <?php echo ucwords($key)?><br></span>

        <?php 
            foreach($row_dt_kunj as $key_s=>$row_s) : 
        ?>
      
          <?php echo '<span style="font-size: 15px; font-weight: bold">'.ucwords($key_s).'</span>';?> ( <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_masuk)?> s/d <?php echo $this->tanggal->formatDateTimeToTime($row_s[0]->tgl_keluar)?> )
          
          <table class="" width="485px" style="color: black" border="0">
              <tr style="background-color: lightgrey;">
                  <th> Uraian </th>
                  <th style="text-align:right" width="100px">Subtotal (Rp.)</th>
              </tr>

            <?php 
              $sum_array[$no_key][$key_s] = array();
              foreach( $row_s as $value_data ) : 

                $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#d3d3d321':'#d3d3d321';
                $checkbox = ($value_data->kode_tc_trans_kasir==NULL)?'<input type="checkbox" name="selected_bill[]" value="'.$value_data->kode_trans_pelayanan.'" checked>':'';
                $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="font-size: 14px;width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>'; 

                if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
                  if($value_data->kode_tc_trans_kasir != NULL) : 
                    $subtotal = $this->Billing->get_total_tagihan($value_data);
                    $sum_array[$no_key][$key_s][] = $subtotal;
            ?>
              
              <tr id="tr_<?php echo $value_data->kode_trans_pelayanan?>" style="background-color:<?php echo $sign_pay?>">
                  <td>
                      <?php echo $value_data->nama_tindakan;?>
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
                      <?php echo $value_data->nama_tindakan;?>
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

            <tr style="font-weight: bold; font-size: 14px">
                <td align="right">Subtotal</td>
                <td style="text-align: right"><?php echo number_format(array_sum($sum_array[$no_key][$key_s]))?>,-</td>
            </tr>
            
          </table>

        <?php    
          endforeach;
        ?>

      <?php endforeach?>
      <hr>
      <table width="485px" border="0">
          <tr>
            <td style="text-align: right"><b>Total</b></td>
            <td style="text-align: right; width: 100px"><?php echo number_format(array_sum($arr_sum_total))?>,-</td>
          </tr>
          <tr>
            <td style="text-align: right"><b>Bayar</b></td>
            <td style="text-align: right; width: 100px"><?php echo isset($kasir_data[0]->cash)?number_format($kasir_data[0]->cash):0?>,-</td>
          </tr>
          <tr>
            <td style="text-align: right"><b>Kembali</b></td>
            <td style="text-align: right; width: 100px"><?php echo isset($kasir_data[0]->change)?number_format($kasir_data[0]->change):0?>,-</td>
          </tr>
      </table>
      <br>
      <!-- footer -->
      <div width="98%" style="padding-left: 0%; padding-right: 1%">
        <span style="font-size: 14px">Total biaya keseluruhan : <b>Rp. <?php echo number_format(array_sum($arr_sum_total))?></b></span>
        <br>
        Terbilang : <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_sum_total)))?>"</i></b>
        <br>
        <table border="0" width="485px">
          <tr>
            <td style="text-align: right">
              Jakarta, <?php echo date('d/m/Y')?>
              <br><br><br>
              <?php if( $flag_bill == 'temporary' ) : ?>
              <div class="col-xs-4">
              <span style="margin-left:-80%; margin-top: -15%; font-size: 24px" class="stamp center">BILLING<br>SEMENTARA</span>
              </div>
              <?php endif;?>
              ( <?php echo $this->session->userdata('user')->fullname?> )
              <br>
              <center><p style="font-size: 11px">Terima Kasih atas kepercayaan anda kepada <?php echo COMP_LONG; ?>, semoga lekas sembuh.</p></center>
            </td>
          </tr>
        </table>
      </div>
      <?php else: echo '<center><h3>Tidak ada data billing ditemukan!</h3></center>'; endif;?>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div>



  
</body>
</html>