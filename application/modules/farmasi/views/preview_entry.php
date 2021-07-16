<div class="row">

  <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div> 

  <div class="col-xs-12">

    <?php if(count($resep) > 0) : ?>

      <div class="col-xs-<?php echo (count($resep_kronis) > 0) ? 6 : 12 ?>">
          <center>
            <span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
            No. <?php echo $kode_trans_far; ?> - <?php echo strtoupper($resep[0]['no_resep'])?>
            </span>
            <div class="pull-right">
              <button onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $kode_trans_far; ?>')" class="btn btn-xs btn-warning" title="Nota Farmasi">
                  <i class="fa fa-print dark"></i> Nota Farmasi
              </button>
            </div>
          </center>

          <table>
            <tr>
              <td width="100px">Tanggal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($resep[0]['tgl_trans']) ?></td>
            </tr>
            <tr>
              <td width="100px">Nama Pasien</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['nama_pasien'])?></td>
            </tr>
            <tr>
              <td width="100px">No. MR</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $no_mr?></td>
            </tr>
            <tr>
              <td width="100px">Dokter</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['dokter_pengirim'])?></td>
            </tr>
            <tr>
              <td width="100px">Unit/Bagian</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep[0]['nama_bagian'])?></td>
            </tr>
            
          </table>

          <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
            <thead>
                <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
                  <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
                  <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Ditangguhkan</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
                  <?php if (count($resep_kronis) == 0) : ?>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Harga Satuan</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jasa R</td>
                  <?php endif; ?>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
                </tr>
            </thead>
                <?php 
                  $no=0; 
                  $arr_total = [];
                  foreach($resep as $key_dt=>$row_dt) : 
                    if( $row_dt['jumlah_tebus'] > 0 ) :
                    $no++; 
                    $subtotal = ($row_dt['flag_resep'] == 'racikan') ? $row_dt['jasa_r'] : ($row_dt['harga_jual'] * $row_dt['jumlah_tebus']) + $row_dt['jasa_r']; 
                    $arr_total[] = $subtotal;
                    $desc = ($row_dt['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dt['nama_brg'];
                    $satuan = ($row_dt['satuan_kecil'] != null) ? $row_dt['satuan_kecil'] : $row_dt['satuan_brg'];
                    $penangguhan_resep = ($row_dt['resep_ditangguhkan'] == 1) ? 'Ya' : '-';
                    $color_penangguhan_resep = ($row_dt['resep_ditangguhkan'] == 1) ? 'red' : 'blue';
                    $racikan = isset($row_dt['racikan'][0])?$row_dt['racikan'][0]:[];
                ?>

                  <tr>
                    <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                    <td style="border-collapse: collapse"><?php echo $desc?></td>
                    <td style="text-align:center; border-collapse: collapse; color: <?php echo $color_penangguhan_resep; ?>; font-weight: bold"><?php echo ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->jml_content : $row_dt['jumlah_tebus'];?></td>
                    <td style="text-align:center; border-collapse: collapse;"><?php echo $penangguhan_resep;?></td>
                    <td style="text-align: center; border-collapse: collapse"><?php echo ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->satuan_racikan : $satuan;?></td>
                    <?php if (count($resep_kronis) == 0) : ?>
                    <td style="text-align:right; border-collapse: collapse"><?php echo ($row_dt['flag_resep'] == 'racikan') ? 0 : number_format($row_dt['harga_jual']);?></td>
                    <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dt['jasa_r'])?></td>
                    <?php endif;?>
                    <td style="text-align:right; border-collapse: collapse"><?php echo number_format($subtotal)?></td>
                  </tr>
                  <?php 
                    $arr_total = [];
                    if($row_dt['flag_resep'] == 'racikan') :
                      foreach ($row_dt['racikan'][0] as $key => $value) {
                        $arr_total[] = ($value->harga_jual * $value->jumlah);
                        $subtotal_racikan = ($value->harga_jual * $value->jumlah);
                        $penangguhan_resep = ($value->resep_ditangguhkan == 1) ? 'Ya' : '-';
                        echo '<tr>
                              <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                              <td style="border-collapse: collapse"> - '.$value->nama_brg.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$value->jumlah.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$penangguhan_resep.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$value->satuan.'</td>';
                        if(count($resep_kronis) == 0 ) :
                        echo '
                              <td style="text-align: right; border-collapse: collapse">'.number_format($value->harga_jual).'</td>
                              <td style="text-align: right; border-collapse: collapse">0</td>';
                        endif;
                        echo '<td style="text-align: right; border-collapse: collapse">'.number_format($subtotal_racikan).'</td>
                            </tr>';
                      }
                    endif; 
                  ?>

                  <?php endif; endforeach;?>

                  <tr>
                    <td colspan="<?php echo (count($resep_kronis) > 0) ? 5 : 7 ?>" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
                    <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_total))?></td>
                  </tr>
                  <tr>
                    <td colspan="<?php echo (count($resep_kronis) > 0) ? 6 : 8 ?>" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
                    <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_total)))?> Rupiah"</i></b>
                    </td>
                  </tr>

          </table>

          Catatan : Obat yang sudah dibeli tidak bisa dikembalikan
          <table style="width: 100% !important; text-align: center">
            <tr>
              <td style="text-align: left; width: 30%">&nbsp;</td>
              <td style="text-align: center; width: 40%">&nbsp;</td>
              <td style="text-align: center; width: 30%">
                <span style="font-size: 14px"><b>Petugas</b></span><br>
                <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
                <br>

              </td>
            </tr>
            
          </table>
      </div>

    <?php 
      else:
        echo '<div class="alert alert-danger"><strong>Peringatan!</strong> Tidak ada data ditampilkan. Silahkan lakukan <a href="#" onclick="update_data('.$kode_trans_far.', '."'".strtolower($flag)."'".')">Entry Resep.</div>';
      endif; 
    ?>

    <?php if(count($resep_kronis) > 0) :?>

      <div class="col-xs-6">
          <center>
            <span style="font-size: 12px;"><strong><u>TRANSAKSI FARMASI</u></strong><br>
              No. RSK-<?php echo $resep_kronis[0]['kode_trans_far']?> - <?php echo strtoupper($resep_kronis[0]['no_resep'])?>
            </span>
            <div class="pull-right">
              <button onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $kode_trans_far; ?>?tipe=resep_kronis')" class="btn btn-xs btn-warning" title="Nota Farmasi">
                  <i class="fa fa-print dark"></i> Nota Farmasi
              </button>
            </div>
          </center>

          <table>
            <tr>
              <td width="100px">Tanggal</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $this->tanggal->formatDateTime($resep_kronis[0]['tgl_trans']) ?></td>
            </tr>
            <tr>
              <td width="100px">Nama Pasien</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep_kronis[0]['nama_pasien'])?></td>
            </tr>
            <tr>
              <td width="100px">No. MR</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep_kronis[0]['no_mr']?></td>
            </tr>
            <tr>
              <td width="100px">Dokter</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep_kronis[0]['dokter_pengirim'])?></td>
            </tr>
            <tr>
              <td width="100px">Unit/Bagian</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo ucwords($resep_kronis[0]['nama_bagian'])?></td>
            </tr>
            <!-- <tr>
              <td width="100px">Penjamin</td>
              <td style="background-color: #FFF;color: #0a0a0a;border: 1px solid #FFF; border-collapse: collapse"> : <?php echo $resep_kronis[0]['nama_perusahaan']?></td>
            </tr> -->
          </table>

          <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
            <thead>
                <tr style="background-color: #e4e7e8;color: #0a0a0a;border-bottom: 1px solid black; border-collapse: collapse">
                  <td style="text-align:center; width: 30px; border-bottom: 1px solid black; border-collapse: collapse">No</td>
                  <td style="border-bottom: 1px solid black; border-collapse: collapse">Nama Obat</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Jumlah Tebus</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Ditangguhkan</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Satuan</td>
                  <td style="text-align:center; width: 100px; border-bottom: 1px solid black; border-collapse: collapse">Subtotal</td>
                </tr>
            </thead>
                <?php 
                  $no=0; 
                  foreach($resep_kronis as $key_dtk=>$row_dtkr) : $no++;
                    if( $row_dtkr['jumlah_obat_23'] > 0 ) : 
                    $subtotalkr = ($row_dtkr['flag_resep'] == 'racikan') ? $row_dtkr['jasa_r'] : ($row_dtkr['harga_jual'] * $row_dtkr['jumlah_obat_23']) + $row_dtkr['jasa_r']; 
                    $arr_totalkr[] = $subtotalkr;
                    $desc = ($row_dtkr['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dtkr['nama_brg'];
                    $satuan = ($row_dtkr['satuan_kecil'] != null) ? $row_dtkr['satuan_kecil'] : $row_dtkr['satuan_brg'];
                    $penangguhan_kronis = ($row_dtkr['prb_ditangguhkan'] == 1) ? 'Ya' : '-';
                    $color_penangguhan_kronis = ($row_dtkr['prb_ditangguhkan'] == 1) ? 'red' : 'blue';
                ?>

                  <tr>
                    <td style="text-align:center; border-collapse: collapse"><?php echo $no?>.</td>
                    <td style="border-collapse: collapse"><?php echo $desc?></td>
                    <td style="text-align:center; border-collapse: collapse; color: <?php echo $color_penangguhan_kronis?>; font-weight: bold"><?php echo ($row_dtkr['flag_resep'] == 'racikan') ? '' : $row_dtkr['jumlah_obat_23'];?></td>
                    <td style="text-align:center; border-collapse: collapse;"><?php echo $penangguhan_kronis;?></td>
                    <td style="text-align: center; border-collapse: collapse"><?php echo $satuan?></td>
                    <!-- <td style="text-align:right; border-collapse: collapse"><?php echo ($row_dtkr['flag_resep'] == 'racikan') ? 0 : number_format($row_dtkr['harga_jual']);?></td>
                    <td style="text-align:right; border-collapse: collapse"><?php echo number_format($row_dtkr['jasa_r'])?></td> -->
                    <td style="text-align:right; border-collapse: collapse"><?php echo number_format($subtotalkr)?></td>
                  </tr>
                  <?php 
                    if($row_dtkr['flag_resep'] == 'racikan') :
                      foreach ($row_dtkr['racikan'][0] as $key => $valuekr) {
                        $arr_totalkr[] = ($valuekr->harga_jual * $valuekr->jumlah);
                        $subtotal_racikankr = ($valuekr->harga_jual * $valuekr->jumlah);
                        echo '<tr>
                              <td style="text-align:center; border-collapse: collapse">&nbsp;</td>
                              <td style="border-collapse: collapse"> - '.$valuekr->nama_brg.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$valuekr->jumlah.'</td>
                              <td style="text-align: center; border-collapse: collapse">'.$valuekr->satuan.'</td>
                              <td style="text-align: right; border-collapse: collapse">'.number_format($subtotal_racikankr).'</td>
                            </tr>';
                      }
                    endif; 
                  ?>

                  <?php endif; endforeach;?>

                  <tr>
                    <td colspan="5" style="text-align:right; padding-right: 20px; border-top: 1px solid black; border-collapse: collapse">Total </td>
                    <td style="text-align:right; border-top: 1px solid black; border-collapse: collapse"><?php echo number_format(array_sum($arr_totalkr))?></td>
                  </tr>
                  <tr>
                    <td colspan="6" style="text-align:left; border-top: 1px solid black; border-collapse: collapse">
                    <b><i>"<?php $terbilangkr = new Kuitansi(); echo ucwords($terbilangkr->terbilang(array_sum($arr_totalkr)))?> Rupiah"</i></b>
                    </td>
                  </tr>

          </table>

          Catatan : Obat yang sudah dibeli tidak bisa dikembalikan
          <table style="width: 100% !important; text-align: center">
            <tr>
              <td style="text-align: left; width: 30%">&nbsp;</td>
              <td style="text-align: center; width: 40%">&nbsp;</td>
              <td style="text-align: center; width: 30%">
                <span style="font-size: 14px"><b>Petugas</b></span><br>
                <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname)?$decode->fullname:$this->session->userdata('user')->fullname;?>
                <br>

              </td>
            </tr>
          </table>
          <span style="margin-left:45%;position:absolute;transform: rotate(0deg) !important; margin-top: -31%" class="stamp is-approved">Resep Kronis</span>
      </div>

    <?php endif; ?>

    
    <!-- button action -->
    <div class="col-xs-12" >
        <div class="col-xs-12 center" style="margin-top: 10px">
        <!-- input hidden -->
        <input type="hidden" name="no_resep" id="no_resep" value="<?php echo isset( $resep[0]['kode_pesan_resep'])? $resep[0]['kode_pesan_resep']:0?>">
        <input type="hidden" name="no_mr" id="no_mr" value="<?php echo $no_mr?>">

        <button onclick="getMenu('farmasi/Retur_obat');" class="btn btn-xs btn-purple" title="Lihat Riwayat Resep">
            <i class="fa fa-history dark"></i> Kembali ke Riwayat Resep
        </button>
        
        <button onclick="getMenu('farmasi/Etiket_obat/form_copy_resep/<?php echo $kode_trans_far; ?>?flag=<?php echo $flag; ?>')" class="btn btn-xs btn-success" title="Copy Resep">
            <i class="fa fa-copy dark"></i> Cetak Copy Resep
        </button>
        <button onclick="getMenu('farmasi/Etiket_obat/form/<?php echo $kode_trans_far; ?>?flag=<?php echo $flag; ?>')" class="btn btn-xs btn-primary" title="etiket">
          <i class="fa fa-ticket dark"></i> Cetak Etiket Obat
        </button>
        <?php if($status_lunas == 0) : ?>
        <button onclick="rollback_by_kode_trans_far(<?php echo $kode_trans_far; ?>, '<?php echo strtolower($flag); ?>')" class="btn btn-xs btn-danger" title="rollback">
          <i class="fa fa-undo dark"></i> Rollback Resep
        </button>
        <?php endif; ?>
        <button onclick="print_tracer(<?php echo $kode_trans_far; ?>)" class="btn btn-xs btn-default" title="etiket">
          <i class="fa fa-print dark"></i> Kirim Tracer ke Gudang
        </button>
      </div>
    </div>
    <hr>
  </div>
  
  <?php if($no_mr != 0) :?>
  <div class="col-xs-12" style="margin-top: 20px">
      <!-- history resep pasien -->
      <p class="center">
        <span style="font-size: 14px; font-weight: bold">RIWAYAT PESAN RESEP FARMASI </span> <br>Data yang ditampikan dibawah ini adalah data pemesanan resep 3 bulan terakhir</p>
      <table id="riwayat_pesan_resep_pasien" base-url="farmasi/Retur_obat/get_data?flag=All&no_mr=<?php echo $no_mr; ?>" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th class="center">No</th>
            <th>Kode</th>
            <th>No Resep</th>
            <th>Tgl Pesan</th>
            <th>No Mr</th>
            <th>Nama Pasien</th>
            <th>Nama Dokter</th>
            <th>Pelayanan</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
  </div>
  <?php endif; ?>

</div>

<script type="text/javascript">
  
  $(document).ready(function(){

    table = $('#riwayat_pesan_resep_pasien').DataTable( {
        "processing": true, 
        "serverSide": true,
        "bInfo": false,
        "bPaginate": false,
        "searching": false,
        "bSort": false,
        "ajax": {
            "url": $('#riwayat_pesan_resep_pasien').attr('base-url'),
            "type": "POST"
        }
    }); 

    $('#riwayat_pesan_resep_pasien tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            //achtungShowLoader();
            $(this).removeClass('selected');
            //achtungHideLoader();
        }
        else {
            //achtungShowLoader();
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            //achtungHideLoader();
        }
    } );

  })

  function rollback_by_kode_trans_far(id, flag){
    preventDefault();
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'farmasi/process_entry_resep/rollback_by_kode_trans_far',
          type: "post",
          data: { ID : id },
          dataType: "json",
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
              if(flag == 'rj' || flag == 'ri'){
                $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form/'+$('#no_resep').val()+'?mr='+$('#no_mr').val()+'&tipe_layanan='+flag+'&rollback=true');
              }

              if(flag == 'rl' || flag == 'pb' || flag == 'rk'){
                $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form_create?jenis_resep='+flag+'&rollback=true&kode_trans_far='+id+'&mr='+$('#no_mr').val()+'&rollback=true');
              }
              

            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }

        });

    }else{
      return false;
    }
    
  }

  function update_data(kode_trans_far, jenis_resep){
  
    preventDefault();
    $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form_create?jenis_resep='+jenis_resep+'');

  }

  function print_tracer(kode_trans_far){
    preventDefault();
    PopupCenter('farmasi/Process_entry_resep/print_tracer_gudang_view/'+kode_trans_far+'', 'TRACER GUDANG FARMASI', 500, 600);
  }
</script>
