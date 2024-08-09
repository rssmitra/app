<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
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

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      $.ajax({
      url: 'pelayanan/Pl_rekap_kunjungan/find_data',
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        find_data_reload(data,'pelayanan/Pl_rekap_kunjungan');
      }
    });
  });

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      $('#form_search')[0].reset();
      reset_data();
  });

});

$( ".form-control" )  
  .keypress(function(event) {  
    var keycode =(event.keyCode?event.keyCode:event.which);  
    if(keycode ==13){    
      event.preventDefault();     
      if($(this).valid()){  
        $('#btn_search_data').click();  
      }    
      return false;   
    }  
}); 

function find_data_reload(result){

  getMenu('pelayanan/Pl_rekap_kunjungan/index?'+result.data);

}

function reset_data(){

  oTable.ajax.url('pelayanan/Pl_rekap_kunjungan/get_data').load();

}

function saveRekap(kode_dr, kode_bag){
  preventDefault();
  var post_data = {
    "kode_bagian" : kode_bag,
    "kode_dokter" : kode_dr,
    "checklist" : $('#checklist_'+kode_dr+'_'+kode_bag+'').val(),
    "status_poli" : $('#status_session_poli_'+kode_dr+'_'+kode_bag+'').val(),
    "ttl_df" : $('#ttl_df_'+kode_dr+'_'+kode_bag+'').val(),
    "ttl_bd" : $('#ttl_bd_'+kode_dr+'_'+kode_bag+'').val(),
    "ttl_sd" : $('#ttl_sd_'+kode_dr+'_'+kode_bag+'').val(),
    "ttl_btl" : $('#ttl_btl_'+kode_dr+'_'+kode_bag+'').val(),
    "keterangan" : $('#keterangan_'+kode_dr+'_'+kode_bag+'').val(),
    "tgl_kunjungan" : $('#tgl_kunjungan').val(),
  };

  $.ajax({
    url: 'pelayanan/Pl_rekap_kunjungan/process',
    type: "post",
    data: post_data,
    dataType: "json",
    beforeSend: function() {
    },
    success: function(data) {
      // sukses respon
      $('#td_'+kode_dr+'_'+kode_bag+'').html(data.timestamp);
      $('#td_keterangan_'+kode_dr+'_'+kode_bag+'').html(data.keterangan);
    }
  });

}

</script>
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="#">

    <div class="col-md-12 no-padding">

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Kunjungan</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['from_tgl'])?$_GET['from_tgl']:date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <!-- <label class="control-label col-md-1">s/d</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['from_tgl'])?$_GET['from_tgl']:date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div> -->
          <div class="col-md-3 no-padding">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
          </div>

      </div>

    </div>
    <br>
    <hr>
    <div class="tabbable" style="margin-top: 20px !important">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#datatable_tab">
            Data Table
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#rekap_data_tab">
            Rekap Data
          </a>
        </li>

      </ul>

      <div class="tab-content">
        <div id="datatable_tab" class="tab-pane fade in active">
          <p><span style="font-weight: bold; font-size: 12px">Rekap kunjungan harian<br>Tanggal, <?php echo isset($_GET['from_tgl']) ? $this->tanggal->formatDateDmy($_GET['from_tgl']) : date('d/m/Y')?></span></p>
          <input type="hidden" id="tgl_kunjungan" value="<?php echo isset($_GET['from_tgl']) ? $_GET['from_tgl'] : date('Y-m-d')?>">

          <table id="dynamic-tablexx" base-url="pelayanan/Pl_rekap_kunjungan" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="30px" class="center">No</th>
                <th>Poliklinik/Dokter</th>
                <th class="center" width="70px">Pasien<br>Terdaftar</th>
                <th class="center" width="70px">Belum<br>Dilayani</th>
                <th class="center" width="70px">Sudah<br>Dilayani</th>
                <th class="center" width="70px">Pasien<br>Batal</th>
                <th class="center" width="80px">Kesesuaian<br>Jumlah Pasien</th>
                <th class="center" width="120px">Keterangan<br>Lainnya</th>
                <th class="center" width="120px">Status Poli</th>
                <th class="center" width="120px">Petugas<br>Penanggung Jawab</th>
                <th class="center" width="80px">#</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $arr_batal = [];
              $batal = [];
              $arr_belum_dilayani = [];
              $belum_dilayani = [];
              $arr_sudah_dilayani = [];
              $sudah_dilayani = [];

                foreach($getData as $key=>$row){
                  foreach ($row as $k => $v) {
                    foreach ($v as $ky => $vl) {
                      // pasien batal
                      if($vl->status_batal == 1){
                        $batal[$key][$k][] = 1;
                      }else{
                        if($vl->tgl_keluar_poli == null){
                          $belum_dilayani[$key][$k][] = 1;
                        }else{
                          $sudah_dilayani[$key][$k][] = 1;
                        }
                      }
                    }
                  }
                  // $arr_batal[$key][$k] = $batal;
                  // $arr_belum_dilayani[$key][$k] = $belum_dilayani;
                  // $arr_sudah_dilayani[$key][$k] = $sudah_dilayani;
                }
                // echo "<pre>";print_r($batal);die;
              ?>
              <?php $no=0; foreach($getData as $key=>$row) : $no++; ?>
                <tr>
                  <td align="center"><?php echo $no?></td>
                  <td colspan="9"><b><?php echo strtoupper($key)?></b></td>
                </tr>
                <?php 
                  foreach($row as $k=>$v) :
                    // belum dilayani
                    $ttl_belum_dilayani = isset($belum_dilayani[$key][$k]) ? count($belum_dilayani[$key][$k]) : '';
                    $ttl_sudah_dilayani = isset($sudah_dilayani[$key][$k]) ? count($sudah_dilayani[$key][$k]) : '';
                    $ttl_batal = isset($batal[$key][$k]) ? count($batal[$key][$k]) : '';
                    $rekap_ex = isset($rekapData[$v[0]->kode_bagian][$v[0]->kode_dokter]) ? $rekapData[$v[0]->kode_bagian][$v[0]->kode_dokter] : [] ;
                    $checklist = isset($rekap_ex->checklist) ? $rekap_ex->checklist : '' ;
                    $status_poli = isset($rekap_ex->status_poli) ? $rekap_ex->status_poli : 'open' ;
                    $created_by = isset($rekap_ex->created_by) ? $rekap_ex->created_by : '' ;
                    $keterangan = isset($rekap_ex->keterangan) ? $rekap_ex->keterangan : '' ;
                    $created_date = isset($rekap_ex->created_date) ? $this->tanggal->formatDateTime($rekap_ex->created_date) : '' ;
                    // echo "<pre>";print_r($v);die;
                ?>
                  <tr>
                  <td>&nbsp;</td>
                  <td valign="middle"><?php echo $k?></td>
                  <td align="center"><a href="#" onclick="show_modal('Templates/References/view_pasien_terdaftar_current?kode_dokter=<?php echo $v[0]->kode_dokter; ?>&kode_spesialis=<?php echo $v[0]->kode_bagian; ?>&tgl_registrasi=<?php echo $this->tanggal->formatDateTimeToSqlDate($v[0]->tgl_jam_poli); ?>', 'DATA KUNJUNGAN PASIEN')"><span style="font-weight: bold; color: black"><?php echo count($v)?><input type="hidden" value="<?php echo count($v)?>" id="ttl_df_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>"></span></a></td>
                  <td align="center"><a href="#" onclick="show_modal('Templates/References/view_pasien_terdaftar_current?kode_dokter=<?php echo $v[0]->kode_dokter; ?>&kode_spesialis=<?php echo $v[0]->kode_bagian; ?>&tgl_registrasi=<?php echo $this->tanggal->formatDateTimeToSqlDate($v[0]->tgl_jam_poli); ?>', 'DATA KUNJUNGAN PASIEN')"><span style="font-weight: bold; color: green"><?php echo $ttl_belum_dilayani?><input type="hidden" value="<?php echo $ttl_belum_dilayani?>" id="ttl_bd_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>"></span></a></td>
                  <td align="center"><a href="#" onclick="show_modal('Templates/References/view_pasien_terdaftar_current?kode_dokter=<?php echo $v[0]->kode_dokter; ?>&kode_spesialis=<?php echo $v[0]->kode_bagian; ?>&tgl_registrasi=<?php echo $this->tanggal->formatDateTimeToSqlDate($v[0]->tgl_jam_poli); ?>', 'DATA KUNJUNGAN PASIEN')"><span style="font-weight: bold; color: blue"><?php echo $ttl_sudah_dilayani?><input type="hidden" value="<?php echo $ttl_sudah_dilayani?>" id="ttl_sd_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>"></span></a></td>
                  <td align="center"><a href="#" onclick="show_modal('Templates/References/view_pasien_terdaftar_current?kode_dokter=<?php echo $v[0]->kode_dokter; ?>&kode_spesialis=<?php echo $v[0]->kode_bagian; ?>&tgl_registrasi=<?php echo $this->tanggal->formatDateTimeToSqlDate($v[0]->tgl_jam_poli); ?>', 'DATA KUNJUNGAN PASIEN')"><span style="font-weight: bold; color: red"><?php echo $ttl_batal?><input type="hidden" value="<?php echo $ttl_batal?>" id="ttl_btl_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>"></span></a></td>
                  <td align="center">
                    <div class="checkbox">
                      <label>
                        <input name="checklist" value="1" id="checklist_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>" type="checkbox" class="ace" <?php echo ($checklist == 1)?'checked':''?> >
                        <span class="lbl">&nbsp;</span>
                      </label>
                    </div>
                  </td>
                  <td id="td_keterangan_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>">
                    <?php if($keterangan == '') :?>
                    <input type="text" id="keterangan_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>" class="form-control" value="<?php echo $keterangan?>"></td>
                      <?php else: echo $keterangan; endif; ?>
                  <td>
                    <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'status_session_poli')), $status_poli , 'status_session_poli_'.$v[0]->kode_dokter.'_'.$v[0]->kode_bagian.'', 'status_session_poli_'.$v[0]->kode_dokter.'_'.$v[0]->kode_bagian.'', 'form-control', '', '') ?>
                  </td>
                  <td align="center"><?php echo ($created_by != '') ? $created_by : $this->session->userdata('user')->fullname?></td>
                  <td align="center"  id="td_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>">
                    <?php if(count($rekap_ex) == 0) : ?>
                    <a href="#" id="btn_save_<?php echo $v[0]->kode_dokter?>_<?php echo $v[0]->kode_bagian?>" class="btn btn-xs btn-primary" onclick="saveRekap(<?php echo $v[0]->kode_dokter?>, '<?php echo $v[0]->kode_bagian?>')">save</a>
                    <?php else : echo $created_date; endif; ?>
                  </td>
                </tr>
                <?php 
                  $arr_terdaftar[] = count($v);
                  $arr_bd[] = $ttl_belum_dilayani;
                  $arr_sd[] = $ttl_sudah_dilayani;
                  $arr_batal[] = $ttl_batal;

                  endforeach; ?>
              <?php endforeach; ?>
              <tr>
                <td colspan="2">TOTAL PASIEN</td>
                <td align="center"><span style="font-weight: bold; color: black"><?php echo array_sum($arr_terdaftar)?></td>
                <td align="center"><span style="font-weight: bold; color: green"><?php echo array_sum($arr_bd)?></td>
                <td align="center"><span style="font-weight: bold; color: blue"><?php echo array_sum($arr_sd)?></td>
                <td align="center"><span style="font-weight: bold; color: red"><?php echo array_sum($arr_batal)?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div id="rekap_data_tab" class="tab-pane fade">
          <div class="row">
            <div class="col-md-4">
              <p style="font-size: 14px; font-weight: bold;">REKAP DATA BERDASARKAN POLI</p>
              <table class="table" id="rekap_data">
                <thead>
                  <tr>
                    <th class="center" width="30px">No</th>
                    <th>Tujuan Kunjungan</th>
                    <th class="center" width="100px">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $no=0;
                    foreach($resume as $rw){
                      $no++;
                      echo "<tr>";
                      echo "<td align='center'>".$no."</td>";
                      echo "<td>".$rw['unit']."</td>";
                      echo "<td align='center'>".$rw['total']."</td>";
                      echo "</tr>";
                      $arr_resume[] = $rw['total'];
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr><td colspan="2" align="right"><b>TOTAL PASIEN</b></td><td align="center"><span class="total_rekap"><?php echo array_sum($arr_resume)?></span></td></tr>
                </tfoot>
              </table>
            </div>

            <div class="col-md-4">
              <p style="font-size: 14px; font-weight: bold;">REKAP DATA BERDASARKAN DOKTER</p>
              <table class="table" id="resume_rekap_data_dr">
                <thead>
                  <tr>
                    <th class="center" width="30px">No</th>
                    <th>Nama Dokter</th>
                    <th class="center" width="100px">Total</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                    $no=0;
                    foreach($rekap_dr as $rw){
                      $no++;
                      echo "<tr>";
                      echo "<td align='center'>".$no."</td>";
                      echo "<td>".$rw['nama_dr']."</td>";
                      echo "<td align='center'>".$rw['total']."</td>";
                      echo "</tr>";
                      $arr_rekap_dr[] = $rw['total'];
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr><td colspan="2" align="right"><b>TOTAL PASIEN</b></td><td align="center"><span class="total_rekap"><?php echo array_sum($arr_rekap_dr)?></span></td></tr>
                </tfoot>
              </table>
            </div>

            <div class="col-md-4">
              <p style="font-size: 14px; font-weight: bold;">REKAP KUJUNGAN PASIEN</p>
              <table class="table" id="resume_rekap_batal">
                  <tr>
                    <th class="center" width="30px">No</th>
                    <th>Deskripsi</th>
                    <th class="center" width="100px">Total</th>
                  </tr>
                  <tr>
                    <td class="center" width="30px">1</td>
                    <td>PASIEN TERDAFTAR</td>
                    <td class="center" width="100px"><span class="total_rekap"><?php echo array_sum($arr_terdaftar)?></span></td>
                  </tr>
                  <tr>
                    <td class="center" width="30px">2</td>
                    <td>PASIEN BELUM DILYANI</td>
                    <td class="center" width="100px"><span class="total_rekap"><?php echo array_sum($arr_bd)?></span></td>
                  </tr>
                  <tr>
                    <td class="center" width="30px">3</td>
                    <td>PASIEN SUDAH DILAYANI</td>
                    <td class="center" width="100px"><span class="total_rekap"><?php echo array_sum($arr_sd)?></span></td>
                  </tr>
                  <tr>
                    <td class="center" width="30px">4</td>
                    <td>PASIEN BATAL</td>
                    <td class="center" width="100px"><span id="rekap_batal"><?php echo array_sum($arr_batal)?></span></td>
                  </tr>
                  <!-- <tr>
                    <td class="center" width="30px">&nbsp;</td>
                    <td align="right"><b>TOTAL PASIEN DATANG</b></td>
                    <td class="center" width="100px"><span id="total_berkunjung"></span></td>
                  </tr> -->
              </table>

            </div>

          </div>
        </div>

      </div>
    </div>
    

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->

<!-- <script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script> -->



