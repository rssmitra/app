<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Out Billing Pembelian Apotik</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <style>
  
    .body_print{
      padding: 10px 0 0 15px;
    }
  
    .body_print, table, p{
    /* font-family: calibri; */
    font-size: 14px;
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
  </style>
</head>
<body>
  <div class="body_print">
    <!-- Nama RS dan Alamat -->
    <table width="500px" border="0">
      <tr>
        <td valign="bottom"><b><span style="font-size: 15px"><?php echo COMP_LONG; ?></span></b><br><span style="font-size: 12px;"><?php echo COMP_ADDRESS; ?></span></td>
        <td width="180px" valign="bottom" style="text-align: right;"><b>No. Trx. <?php echo $data->reg_data->flag_trans.'/'.$data->kode_trans_far ?></b></td>
        <td width="36px"></td>
      </tr>
      <tr>
        <td align="center" colspan="2" style="font-size:16px;border-bottom: 2px solid black; padding-bottom : 2px;"><center><b>Rincian Biaya Pasien</b></center></td>
      </tr>
    </table>
    <!-- data pasien -->
    <div class="row">
      <div class="col-xs-12">
        <table width="460px" style=" border-bottom: 2px solid black;">
          <tr>
            <td width="30%">No MR</td>
            <td>: <?php echo ($data->reg_data->no_mr != 0) ? $data->reg_data->no_mr : 'Pembelian Resep / Apotik'?></td>
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
            <td>: <?php echo $this->tanggal->formatDate($data->reg_data->tgl_trans).' - '.$this->tanggal->formatDateTimeToTime($data->reg_data->tgl_trans).' WIB'?></td>
          </tr>
          <tr>
            <td style="padding-bottom: 3px;"></td>
          </tr>
        </table>
        <!-- PAGE CONTENT BEGINS, Detail Transaksi -->
        <?php if( count($kunjungan) > 0 ) : 
          $no_key=1;
          foreach($kunjungan as $key=>$row_dt_kunj) : $no_key++;
            foreach($row_dt_kunj as $key_s=>$row_s) : 
        ?>
        
        <table class="" width="460px" style="color: black; margin-top: 3px;">
          <tr>
            <th colspan="2" style=" text-align: center;font-weight: bold;"><?php echo strtoupper($key_s)?></th>
          </tr>  
          <tr style="background-color: lightgrey;">
            <th> Uraian </th>
            <th style="text-align:right" width="100px">Jumlah ( Rp )</th>
          </tr>
  
            <?php 
              $sum_array[$no_key][$key_s] = array();
              foreach( $row_s as $value_data ) : 

                $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#d3d3d321':'#d3d3d321';
                $checkbox = ($value_data->kode_tc_trans_kasir==NULL)?'<input type="checkbox" name="selected_bill[]" value="'.$value_data->kode_trans_pelayanan.'" checked>':'';
                $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="font-size: 12px; width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>'; 

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
  
          <tr style="font-style: italic; ">
            <td style="text-align: right; font-weight: bold; font-family: Segoe UI;">Subtotal</td>
            <td style="text-align: right; border-top: 1px solid black; "><?php echo number_format(array_sum($sum_array[$no_key][$key_s]))?>,-</td>
          </tr>
          <?php    
              endforeach;
          endforeach;
          if( $kasir_data[0]->nk_karyawan !=0 || $kasir_data[0]->potongan !=0  ){
            echo  '<tr style="font-style:italic;  line-height: 0.75;">';
            echo  '  <td style="text-align: right; font-family: Segoe UI;"><b>Potongan</b></td>';
            echo  '  <td style="text-align: right; width: 100px">'. number_format( $kasir_data[0]->potongan ) .',-</td>';
            echo  '</tr>';
          }
          ?>
        </table>
  
        
        <table width="460px" border="0" style="line-height: 1.15; margin-top: 2px;">
          <?php 
          ?>
          <tr style="font-style:italic; font-family: Segoe UI; ">
            <td style="text-align: right; padding-top: 3px;"><b>Total Bayar</b></td>
            <td style="text-align: right; font-weight: bold;width: 100px; border-top: 1px solid black; padding-top: 3px;">
              <?php 
                $total_val = array_sum($arr_sum_total) - $kasir_data[0]->potongan;
                echo number_format( $total_val );
              ?>,-
            </td>
          </tr>
          <?php 
            if( $kasir_data[0]->nk_karyawan !=0 ){
              echo  '<tr style="font-style:italic; ">';
              echo  '  <td style="text-align: right; font-family: Segoe UI;"><b>Nota Kredit</b></td>';
              echo  '  <td style="text-align: right; width: 100px">'. number_format( $kasir_data[0]->nk_karyawan ) .',-</td>';
              echo  '</tr>';
            }
            if( $kasir_data[0]->debet != 0 ){
              echo  '<tr style="font-style:italic; ">';
              echo  '  <td style="text-align: right; font-family: Segoe UI;"><b>Kartu Debit</b></td>';
              echo  '  <td style="text-align: right; width: 100px">'. number_format( $kasir_data[0]->debet ) .',-</td>';
              echo  '</tr>';
            }
            if( $kasir_data[0]->kredit != 0 ){
              echo  '<tr style="font-style:italic; ">';
              echo  '  <td style="text-align: right; font-family: Segoe UI;"><b>Kartu Kredit</b></td>';
              echo  '  <td style="text-align: right; width: 100px">'. number_format( $kasir_data[0]->kredit ) .',-</td>';
              echo  '</tr>';
            }
            if( $kasir_data[0]->cash !=0 ){
              echo  '<tr style="font-style:italic; ">';
              echo  '  <td style="text-align: right; font-family: Segoe UI;"><b>Bayar Cash</b></td>';
              echo  '  <td style="text-align: right; width: 100px">'. number_format( $kasir_data[0]->cash ) .',-</td>';
              echo  '</tr>';

              echo  '<tr style="font-style:italic; ">';
              echo  '  <td style="text-align: right; font-family: Segoe UI;"><b>Uang Kembali</b></td>';
              echo  '  <td style="text-align: right; width: 100px"><b>'. number_format( $kasir_data[0]->change ) .',-</b></td>';
              echo  '</tr>';
            }
          ?>
        </table>
        <br>
        <!-- footer -->
        <div width="460px" style="padding-left: 0%; padding-right: 1%">
          <!-- <span style="font-size: 12px">Total biaya keseluruhan : <b>Rp. <?php //echo number_format(array_sum($arr_sum_total))?></b></span>
          <br> -->
          Terbilang : <b><i>"<span style=" font-family: Segoe UI"><?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang( $total_val ))?>"</span></i></b>
          <br><br>
          <table border="0" width="460px">
            <tr>
              <td style="text-align: right">
                Jakarta, <?php echo date('d/m/Y')?>
                <br><br><br><br><br>
                ( <?php echo $this->session->userdata('user')->fullname?> )
              </td>
            </tr>
            <tr>
              <td>
                <center><p style="font-size: 11px">Terima Kasih atas kepercayaan anda kepada <?php echo COMP_LONG; ?>, semoga lekas sembuh.</p></center>
              </td>
            </tr>
          </table>
        </div>
        <?php else: echo '<center><h3>Tidak ada data billing ditemukan!</h3></center>'; endif;?>
      </div><!-- /.col -->
    </div><!-- /.row .end data Pasien -->
  </div><!-- .end body_print -->
</body>
</html>




