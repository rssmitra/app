<h3 class="center">LAPORAN HARIAN KASIR (LHK)</h3>
<!-- stamp -->

<?php if($is_published == true) :?>
<span style="margin-left:12%;position:absolute;transform: rotate(0deg) !important; margin-top: -5%" class="stamp is-approved">Published</span>
<?php endif; ?>

<div class="row">
  
  <div class="col-sm-8">
    <button class="btn btn-success btn-xs">
      <i class="ace-icon fa fa-file-excel-o bigger-160"></i>
      Export Excel
    </button><br>
    <span style="font-size: 15px;">Rekapitulasi Pendapatan Kasir Berdasarkan Pembayaran <b><?php echo ($method=='bill')?'Billing':ucfirst($method)?></b> </span><br>
    Tanggal <?php echo $this->tanggal->formatDate($date)?>
    <table class="table" style="width: 100%;" border="0">
      <thead>
        <tr >
          <th width="50px" class="center">No</th>
          <th>Deskripsi</th>
          <?php 
            if(count($column) > 0 ){
              foreach($column as $key_col=>$col){
                echo '<th class="center">'.ucwords(strtolower($key_col)).'</th>';
              }
            }else{
              echo '<th class="center">Nama Petugas</th>';
            }
          ?>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        $no = 0; 
        foreach($rowColumn as $key_row=>$row) : $no++; 
          $colspan = count($column) + 1;
          echo '<tr>';
          echo '<td align="center" >'.$no.'</td>';
          echo '<td align="left" colspan="'.$colspan.'"><b>'.strtoupper($key_row).'</b></td>';
          echo '<tr>';
          foreach ($row as $sub_key => $sub_row) {
            echo '<tr>';
            echo '<td align="center">&nbsp;</td>';
            echo '<td align="left">&nbsp;&nbsp;&nbsp;- '.$sub_key.'</td>';
            foreach($column as $key_col=>$col){
              // search data
              $tunai = $this->master->sumArrayByColumn($rowData['data'], array( array('petugas' => $key_col), array('jenis_tindakan' => $sub_key), array('nama_bagian' => $key_row) ), 'bill_rs' );
              $arr_tunai[$key_col][] = $tunai;
              echo '<td align="right">'.number_format($tunai).'</td>';
              $arr_total[$sub_key][] = $tunai;
            }
            echo '<td align="right">'.number_format(array_sum($arr_total[$sub_key])).'</td>';
            echo '<tr>';
          }
        endforeach;
      ?>
      <tr style="font-weight: bold">
        <td colspan="<?php echo (count($column) > 0) ? 2 : 3?>" align="left">Sub Total</td>
        <?php 
            foreach($column as $key_col=>$col){
              $arr_total[] = array_sum($arr_tunai[$key_col]);
              echo '<td align="right">'.number_format(array_sum($arr_tunai[$key_col])).'</td>';
            }
            echo '<td align="right">'.number_format(array_sum($arr_total)).'</td>';
        ?>
      </tr>
      <tr style="font-weight: bold">
        <td colspan="2" align="left">Total Pemasukan Kasir</td>
        <td colspan="<?php echo (count($column) > 0) ? count($column) + 1 : 0?>" align="right" style="font-size: 16px"><?php echo isset($arr_total) ? number_format(array_sum($arr_total)) : 0;?></td>
      </tr>
      </tbody>
    </table>
  </div>

  <div class="col-sm-4">
    <button class="btn btn-success btn-xs">
      <i class="ace-icon fa fa-file-excel-o bigger-160"></i>
      Export Excel
    </button><br>
    <span style="font-size: 15px;">Resume Mapping Pendapatan</span><br>Tanggal <?php echo $this->tanggal->formatDate($date)?>
    <table class="table" style="width: 100%">
      <tr>
        <thead>
        <th class="center">No</th>
        <th>Deskripsi</th>
        <th style="text-align: right">Total</th>
        </thead>
      </tr>
      <tbody>
      <?php
        $no_urut_bill = 0; 
        if( count($resume_billing) > 0 ) {
          foreach($resume_billing as $row_resume_bill) { $no_urut_bill++;
            $arr_sub_total_bill[] = $row_resume_bill['subtotal'];
            echo '<tr>';
            echo '<td align="center">'.$no_urut_bill.'</td>';
            echo '<td align="left">'.$row_resume_bill['title'].'</td>';
            echo '<td align="right">'.number_format($row_resume_bill['subtotal']).'</td>';
            echo '</tr>';
          }
        }else{
          echo '<tr>';
          echo '<td colspan="3">-Tidak ada data ditemukan-</td>';
          echo '</tr>';
        }
        
      ?>
      <tr style="font-weight: bold">
        <td align="center"></td>
        <td align="center">TOTAL</td>
        <td align="right"><?php echo isset($arr_sub_total_bill) ? number_format(array_sum($arr_sub_total_bill)) : 0; ?></td>
      </tr>
      </tbody>
    </table>
    <?php
        foreach($resume as $row_resume) {
          $total_resume_bill = $row_resume[$method];
          $arr_resume_bill[] = $total_resume_bill;
        }
    ?>
    <hr>
    <div class="pull-right">
        <span style="font-size: 14px; text-align: right !important">Total <?php echo ($method=='bill')?'Billing':ucfirst($method)?></span><br>
        <span style="font-size: 20px"><b><?php echo isset($arr_resume_bill) ? number_format(array_sum($arr_resume_bill)) : 0; ?></b></span>
    </div>
    <br>
    Total Pendapatan setelah dikurangi potongan/diskon
    
  </div>

  <hr>
  <div class="col-sm-12">
    <button class="btn btn-success btn-xs">
      <i class="ace-icon fa fa-file-excel-o bigger-160"></i>
      Export Excel
    </button><br>
    <span style="font-size: 15px;">Resume Pendapatan Berdasarkan Petugas</span><br>Tanggal <?php echo $this->tanggal->formatDate($date)?>
    <table class="table" style="width: 100%">
      <tr>
        <thead>
        <th class="center">No</th>
        <th>Nama Petugas</th>
        <th style="text-align: right">Tunai</th>
        <th style="text-align: right">Debet</th>
        <th style="text-align: right">Kredit</th>
        <th style="text-align: right">NK Perusahaan</th>
        <th style="text-align: right">NK Karyawan</th>
        <th style="text-align: right">Potongan</th>
        <th style="text-align: right">Total</th>
        </thead>
      </tr>
      <tbody>
      <?php
        $no_urut = 0; 
        foreach($resume as $row_resume) { 
          $no_urut++;
          $nama_petugas = ($row_resume['fullname'])?$row_resume['fullname']:$row_resume['nama_pegawai'].'<span style="color: red"> (av) </spanb>';
          echo '<tr>';
          echo '<td align="center">'.$no_urut.'</td>';
          echo '<td>'.strtoupper($nama_petugas).'</td>';
          echo '<td align="right">'.number_format($row_resume['tunai']).'</td>';
          $arr_tunai[] = $row_resume['tunai'];
          echo '<td align="right">'.number_format($row_resume['debet']).'</td>';
          $arr_debet[] = $row_resume['debet'];
          echo '<td align="right">'.number_format($row_resume['kredit']).'</td>';
          $arr_kredit[] = $row_resume['kredit'];
          echo '<td align="right">'.number_format($row_resume['piutang']).'</td>';
          $arr_piutang[] = $row_resume['piutang'];
          echo '<td align="right">'.number_format($row_resume['nk_karyawan']).'</td>';
          $arr_nk_karyawan[] = $row_resume['nk_karyawan'];
          echo '<td align="right">'.number_format($row_resume['potongan']).'</td>';
          $arr_potongan[] = $row_resume['potongan'];
          $total = $row_resume['bill'] + $row_resume['potongan'];
          echo '<td align="right">'.number_format($total).'</td>';
          $arr_bill[] = $total;
          echo '</tr>';
        }
      ?>
      <tr style="font-weight: bold">
        <td align="center"></td>
        <td align="center">TOTAL</td>
        <td align="right"><?php echo isset($arr_tunai) ? number_format(array_sum($arr_tunai)) : 0; ?></td>
        <td align="right"><?php echo isset($arr_debet) ? number_format(array_sum($arr_debet)) : 0; ?></td>
        <td align="right"><?php echo isset($arr_kredit) ? number_format(array_sum($arr_kredit)) : 0; ?></td>
        <td align="right"><?php echo isset($arr_piutang) ? number_format(array_sum($arr_piutang)) : 0; ?></td>
        <td align="right"><?php echo isset($arr_nk_karyawan) ? number_format(array_sum($arr_nk_karyawan)) : 0; ?></td>
        <td align="right"><?php echo isset($arr_potongan) ? number_format(array_sum($arr_potongan)) : 0; ?></td>
        <td align="right"><?php echo isset($arr_bill) ? number_format(array_sum($arr_bill)) : 0; ?></td>
      </tr>
      </tbody>
    </table>
  </div>

  <hr>
  <div class="col-sm-12">
  <hr class="separator">
  <a href="#" onclick="getMenuTabs('adm_pasien/loket_kasir/Adm_resume_lhk/get_data_by_pasien?method=<?php echo $method; ?>&from_tgl=<?php echo $date?>', 'load_data_pasien')">Tampilkan Data Pasien</a>
  <div id="load_data_pasien"></div>
  </div>
</div>