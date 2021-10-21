<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Billing</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  
  <style>
  
    .body_print{
      /* margin: 0px 0px 0px 10px; */
      padding: 0;
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

    .top-row-bottom-line, th {
      border-bottom : 1px solid black;
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

<?php 
  if( count( $kunjungan ) > 0 ) : 
    foreach( $kunjungan as $key=>$row_dt_kunj ) :
      foreach( $row_dt_kunj as $key_s=>$row_s ) : 
        $sum_array_default[$key_s] = [];
         
        if( isset( $_GET['kode_tc_trans_kasir'] ) ) :   
          foreach( $row_s as $value_data ) : 
              if( $value_data->kode_tc_trans_kasir == $_GET['kode_tc_trans_kasir'] ) : 
                if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
                  if($value_data->kode_tc_trans_kasir != NULL) : 
                    $subtotal = $this->Billing->get_total_tagihan($value_data);
                    $sum_array_default[$key_s][] = $subtotal;
                  endif;
                else:
                  $subtotal = $this->Billing->get_total_tagihan($value_data);
                  $sum_array_default[$key_s][] = $subtotal;
                endif; 
              endif;
          endforeach; 
        else:
          foreach( $row_s as $value_data ) : 
            if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
              if($value_data->kode_tc_trans_kasir != NULL) : 
                $subtotal = $this->Billing->get_total_tagihan($value_data);
                $sum_array_default[$key_s][] = $subtotal;
              endif;
            else:
              $subtotal = $this->Billing->get_total_tagihan($value_data);
              $sum_array_default[$key_s][] = $subtotal;
            endif; 
          endforeach; 
        endif;
      endforeach;
    endforeach;
  endif;

  // echo '<pre>';print_r($sum_array_default);die;        
?>

<div class="body_print">
  
  <table width="500px" border="0">
    <!-- Nama RS dan Alamat -->
    <tr>
      <!-- <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td> -->
      <td valign="bottom"><b><span style="font-size: 15px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
      <td width="180px" valign="bottom" style="text-align: right;"><b>Trx. No. <?php echo $data->reg_data->kode_trans_far;?></b></td>
      <td width="40px"></td>
    </tr>
    <tr>
      <td align="center" colspan="2" style="font-size:16px;border-bottom: 2px solid black; "><center><b>Rincian Biaya Pasien</b></center></td>
    </tr>
  </table>
  <div class="row">
  <div class="col-xs-12">
    <!-- Detail Pasien -->
    <table width="460px" style="font-size:13px; border-bottom: 2px solid black;">
      
      <tr>
        <td width="25%">No MR</td>
        <td>: <?php echo $data->reg_data->no_mr?></td>
      </tr>
      <tr>
        <td>Nama Pasien</td>
        <td>: <?php echo $data->reg_data->nama_pasien?></td>
      </tr>
      <tr>
        <td>Tanggal</td>
        <td>: <?php echo $this->tanggal->formatDate($data->reg_data->tgl_trans).' - '.$this->tanggal->formatDateTimeToTime($data->reg_data->tgl_trans).' WIB'?></td>
      </tr>
    </table>
    
      
      <!-- PAGE CONTENT BEGINS -->
      <?php if( count( $kunjungan ) > 0 ) :  ?>
      <?php $no_key = 1; foreach( $kunjungan as $key=>$row_dt_kunj ) : $no_key++; ?>
          
        <?php 
            
            foreach( $row_dt_kunj as $key_s=>$row_s ) : 
              $sum_array[$no_key][$key_s] = array();
              if(array_sum($sum_array_default[$key_s]) > 0) :
        ?>
      
      <table class="" width="460px" style="margin-top: 3px;">
              <tr>
                <th style="text-align: center; font-size: 13px;" colspan="2">
                  <?php 
                  if( $key_s === 'Gawat Darurat' ){
                    echo 'INSTALASI '.strtoupper( $key_s );
                  }else{
                    echo strtoupper( $key_s );
                  }
                  ?> 
                </th>
              </tr>
              <tr>
                <th style="text-align: left;"> Uraian </th>
                <th style="text-align: right;" width="100px">Jumlah ( Rp )</th>
              </tr>
            
            <?php 
              
              if( isset( $_GET['kode_tc_trans_kasir'] ) AND $_GET['kode_tc_trans_kasir'] != NULL ) : 
                
            ?>
              <!-- jika ada kode tc trans kasir maka billing difilter berdasarkan kode tc trans kasir // jenis_tindakan != 9, tindakan yang bukan BPAKO -->
            <?php 
              // $arr_sum_total = [];
              foreach( $row_s as $value_data ) : 
                  if( $value_data->kode_tc_trans_kasir == $_GET['kode_tc_trans_kasir'] /*&& $value_data->jenis_tindakan != 9*/ ) : 
                    
                    $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#d3d3d321':'#d3d3d321';
                    $checkbox = ($value_data->kode_tc_trans_kasir==NULL)?'<input type="checkbox" name="selected_bill[]" value="'.$value_data->kode_trans_pelayanan.'" checked>':'';
                    $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="font-size: 12px;width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>'; 

                    if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
                      if($value_data->kode_tc_trans_kasir != NULL) : 
                        
                        if(isset($_GET['status_nk'])) :
                          if($_GET['status_nk'] == $value_data->status_nk) :
                          $subtotal = $this->Billing->get_total_tagihan($value_data);
                          $sum_array[$no_key][$key_s][] = $subtotal;
                          // $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]);
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
                            // $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]);
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
                      endif;
                    else: 
                ?>
                  <?php 
                    $subtotal = $this->Billing->get_total_tagihan($value_data);
                    $sum_array[$no_key][$key_s][] = $subtotal;
                    // $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]);
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
                  endif;
                endforeach; 
                $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]); 
              // endif;
            ?>
            <!-- selesai -- jika ada kode tc trans kasir maka billing difilter berdasarkan kode tc trans kasir -->

            <!-- jika tidak ada kode tc trans kasir maka billing ditampilkan semua -->
              <?php 
                
              else :
                // echo 'dsini';
              // $sum_array[$no_key][$key_s] = array();
              // $arr_sum_total = [];
              foreach( $row_s as $value_data ) : 
                
                    $sign_pay = ($value_data->kode_tc_trans_kasir==NULL)?'#d3d3d321':'#d3d3d321';
                    $checkbox = ($value_data->kode_tc_trans_kasir==NULL)?'<input type="checkbox" name="selected_bill[]" value="'.$value_data->kode_trans_pelayanan.'" checked>':'';
                    $penjamin = $this->master->custom_selection($params = array('table' => 'mt_perusahaan', 'id' => 'kode_perusahaan', 'name' => 'nama_perusahaan', 'where' => array() ), $value_data->kode_perusahaan , 'penjamin[]', 'penjamin_val_'.$value_data->kode_trans_pelayanan.'', '', '', ' style="font-size: 12px;width: 150px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 100% border-bottom: 1px #ccc solid; margin: 0px 1px !important; display: none"').'<span id="penjamin_txt_'.$value_data->kode_trans_pelayanan.'">'.$value_data->nama_perusahaan.'</span>'; 

                    if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
                      if($value_data->kode_tc_trans_kasir != NULL) : 
                        $subtotal = $this->Billing->get_total_tagihan($value_data);
                        $sum_array[$no_key][$key_s][] = $subtotal;
                        
                        // echo $subtotal;
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
                      // echo $subtotal;
                      // $arr_sum_total[] = $subtotal;
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
                
            ?>

            <?php 
              endif;
              
            ?>
            <!-- selesai --- jika tidak ada kode tc trans kasir maka billing ditampilkan semua -->


            <tr style="font-weight: bold">
                <td style="text-align: right; padding-right: 10px; font-size: 12px"><i>Subtotal &nbsp;</i></td>
                <td style="text-align: right; border-top: 1px solid black; font-size: 12px;"><?php echo number_format(array_sum($sum_array[$no_key][$key_s]))?>,-</td>
            </tr>
            
          </table>

        <?php    
            $getDataBill[] = array_sum($sum_array[$no_key][$key_s]);
          endif;
          $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]);
          
        endforeach;
        
        ?>

<?php 
          endforeach;
          // echo '<pre>';print_r($getDataBill);die;
          
      ?>
      
      <table width="460px" style="border-top: 1px solid black; margin-top: 15px !important;"  border="0">
        <?php
          $total_all_bill = isset($getDataBill) ? array_sum($getDataBill)  : 0; 
        ?>
        <tr>
          <td style="text-align: right; font-size: 13px;"><b>Total : </b></td>
          <td style="font-family:Verdana, Geneva, Tahoma, sans-serif; text-align: right; font-size: 13px; width: 100px">
            <i><b>
              <?php 
                echo number_format($total_all_bill); 
              ?>,-
            </b></i>
          </td>
        </tr>
        <?php 
        // Yang Harus Dibayar Pasien
        $total_bayar_pasien = ( $total_all_bill - $kasir_data[0]->potongan );
        
        if ( $kasir_data[0]->potongan > 0 ){
          echo  '<tr>
                  <td style="text-align: right">Potongan Karyawan :</td>    
                  <td style="text-align: right; width: 100px; border-bottom: solid 1px black;">-('.number_format( $kasir_data[0]->potongan ).'),-</d>
                </tr>';
          echo '<tr>
                  <td style="text-align: right; font-size: 13px;"><b>Total Bayar : </b></td>
                  <td style="font-family:Verdana, Geneva, Tahoma, sans-serif; text-align: right; font-size: 13px; width: 100px"><i><b>
                    '.number_format($total_bayar_pasien).',-</b></i>
                  </td>
                </tr>';
        }else {
          // echo '<tr>
          //         <td style="text-align: right; font-size: 13px;"><b>Total : </b></td>
          //         <td style="font-family:Verdana, Geneva, Tahoma, sans-serif; text-align: right; font-size: 13px; width: 100px"><i><b>
          //           '.number_format($total_bayar_pasien).',-</b></i>
          //         </td>
          //       </tr>';
                
        }
        ?>
        
      </table>
      <!-- footer -->
      <div width="98%" style="padding-left: 0%;">
        <table width="460px" style="border-bottom: 1px solid black;">
          <tr>
            <td valign="top" width="65px">Terbilang :&nbsp;</td>
            <td style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"><b><i><?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total_bayar_pasien))?> Rupiah</i></b></td>
            <td width="50px"></td>
          </tr>
        </table>
        <table width="460px" border="0">
        <?php
          if ( $kasir_data[0]->nk_karyawan > 0 ){
            echo  '<tr>
                    <td style="text-align: left" colspan="3"><i>Keterangan : <b>'.$kasir_data[0]->keterangan.'</b></i></td>    
                  </tr>';
            echo  '<tr>
                    <td style="text-align: right; width: 100px;">Bon Karyawan : </td>    
                    <td style="text-align: right; width: 1px; white-space: nowrap;">&nbsp;'.number_format( $kasir_data[0]->nk_karyawan ).',-</d>
                    <td></td>
                  </tr>';
          }
          if ( ($kasir_data[0]->cash > 0 || $kasir_data[0]->debet > 0 || $kasir_data[0]->kredit > 0) && $kasir_data[0]->discount > 0  ){
            echo  '<tr>
                    <td style="text-align: right">Total dibayar : </td>    
                    <td style="text-align: right;">&nbsp;'.number_format( ($kasir_data[0]->cash + $kasir_data[0]->debet + $kasir_data[0]->kredit) ).',-</d>
                  </tr>';
          }
          ?>
        </table>
        <table width="460px">
          <tr>
            <td style="text-align: right">
              Jakarta, <?php echo date('d/m/Y')?>
              <br><br><br><br><br>
              <?php if( $flag_bill == 'temporary' ) : ?>
              <div class="col-xs-4">
              </div>
              <?php endif;?>
              ( <?php echo $this->session->userdata('user')->fullname?> )
              <br>
              <center><p style="font-size: 11px;">Terima Kasih atas kepercayaan anda kepada <?php echo COMP_LONG; ?>, semoga lekas sembuh.</p></center>
            </td>
          </tr>
        </table>
      </div>
      <?php else: echo '<center><h3>Tidak ada data billing ditemukan!</h3></center>'; endif;?>
    </div><!-- /.col -->
    <div id="options">
      <button
        id="printpagebutton"
        style="
          font-family: arial;
          background: blue;
          color: white;
          cursor: pointer;
          padding: 20px;
          position:absolute;
          right: 10px;
          cursor: pointer;
        "
        onclick="printpage();"

        >
        PRINT OUT
      </button>
    </div>
  </div><!-- /.row -->
</div>
<script>
  function printpage(){
    //Get the print button and put it into a variable
    var printButton = document.getElementById("printpagebutton");
    //Set the print button visibility to 'hidden' 
    printButton.style.visibility = 'hidden';
    //Print the page content
    window.print()
    printButton.style.visibility = 'visible';
  }
</script>



  
</body>
</html>