
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>SHS 4.0 - Antrian farmasiklinik</title>

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <!-- css date-time -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <!-- end css date-time -->
    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
    <link rel="shortcut icon" href="<?php echo base_url().'assets/insani/favicon_rssm.png'; ?>">

  </head>
  <style>
    @font-face { 
      font-family: 'MyriadPro'; 
      src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf'); 
    } 

    body{
      font-family: 'MyriadPro' !important;
      background: url('<?php echo base_url()?>assets/images/unit-pendaftaran.jpg') fixed !important;
      background-color: #E6E7E8;
    }

    .page-content {
        background-color: white;
        position: relative;
        margin: 0;
        padding: 0px 20px 24px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100% !important;
        min-height: 670px;
    }

    .page-header {
      padding-bottom: 9px;
      margin: 0px 0 0px !important;
      border-bottom: 1px solid #eee;
      background-color: #E6E7E8;
    }

    .footer{
      padding: 16px !important;
    }

    .table tr {
      font-size: 2.2em;
    }

    .table {
      /* border-collapse: collapse; */
      width: 100%;
    }

    .table td, .table th {
      border: 0px solid black !important;
      padding: 8px;
      color: white
    }

    .table th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      color: white !important;
    }

    .widget-main{
      padding: 0px !important
    }

    .widget-header{
      background: #00669f;
      color: white;
      font-weight: bold;
      text-align: center;
      border-top-left-radius: 10px 
    }

    .widget-body{
      background: #00669f2b;
      color: black;
    }
    

  </style>

  <body class="no-skin">
  
    <div class="main-container ace-save-state" id="main-container" style="min-height: 100vh; display: flex; flex-direction: column;">
      <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
      </script>

      <div class="main-content" style="flex: 1 1 auto; overflow-y: auto;">

        <div class="col-md-12" style="padding: 10px">
            <div style="float: left; margin-left: 20px; margin-top: 10px">
              <img alt="" src="<?php echo base_url().COMP_ICON_INSANI?>" width="300px">
            </div>
            <div style="float: right; margin-top: 10px; margin-right: 10px">
              <span class="title-text"><img alt="" src="<?php echo base_url().COMP_ICON_BY_INSANI?>" width="150"></span>
            </div>
        </div>

        

        <div class="col-md-12 header-fixed" style="background: #00669F; color: white; padding: 5px; border-top-left-radius: 15px; border-top-right-radius: 15px; position: sticky; top: 0; z-index: 1000;">
          <div style="font-size: 25px; font-weight: bold; float: left; padding-left: 20px">Antrian Resep Obat</div>
          <div style="text-align: right; font-size: 20px; margin-top: 3px; float: right; margin-right: 20px" >
            <i class="fa fa-calendar"></i> <?php date_default_timezone_set("Asia/Jakarta"); echo date('l, d F Y') ?> &nbsp; <i class="fa fa-clock-o"></i>  
            <span id="refresh">&nbsp;
                <span id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></span> WIB
          </div>
        </div>

        <div class="main-content-inner">
          <div class="page-content">
            <div id="page-area-content" >
              <!-- section antrian farmasi -->
              <div id="section_antrian_farmasi" class="row" style="margin-top: 10px">
                
                <!-- CONTENT HERE -->
                <div class="col-sm-2">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                      <h4 class="widget-title center" style="text-align: center !important">RESEP DITERIMA</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 16px; border-bottom: 1px solid black;">
                              <th width="30px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center">Waktu</th>
                          </tr>
                          </thead>
                          <?php 
                            $no=0; 
                            $arr_resep_diterima = [];
                              foreach($resep_diterima as $row) :
                                if($row->kode_trans_far == null) :
                                  $no++;
                                  $arr_resep_diterima[] = $row;
                          ?>
                          <tr style="font-size: 14px; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top"><?php echo strtoupper($row->nama_pasien)?></td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endforeach;?>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                      <h4 class="widget-title center" style="text-align: center !important">PENYEDIAAN OBAT</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 16px; border-bottom: 1px solid black;">
                              <th width="30px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center">Waktu</th>
                          </tr>
                          </thead>
                          <?php 
                            $no=0; 
                            $arr_penyediaan_obat = [];
                            foreach($resep as $row) : 
                              if($row->jenis_resep == 'racikan'){
                                $logtime = $row->log_time_3;
                              }else{
                                $logtime = $row->log_time_4;
                              }
                              if($row->log_time_2 != null && $logtime == null) : 
                                $no++;
                                $arr_penyediaan_obat[] = $row;
                          ?>
                          <tr style="font-size: 14px; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top"><?php echo strtoupper($row->nama_pasien)?></td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; 
                        endforeach;?>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                      <h4 class="widget-title center" style="text-align: center !important">PROSES RACIKAN</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 16px; border-bottom: 1px solid black;">
                              <th width="30px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center">Waktu</th>
                          </tr>
                          </thead>
                          <?php 
                            $no=0; 
                            $arr_racikan = [];
                            foreach($resep as $row) : 
                              if($row->log_time_3 != null && $row->log_time_4 == null) : 
                                $no++;
                                $arr_racikan[] = $row;
                          ?>
                          <tr style="font-size: 14px; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top"><?php echo strtoupper($row->nama_pasien)?></td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endforeach;?>
                        </table>
                        
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                      <h4 class="widget-title center" style="text-align: center !important">PROSES ETIKET</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 16px; border-bottom: 1px solid black;">
                              <th width="30px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center">Waktu</th>
                          </tr>
                          </thead>
                          <?php 
                            $no=0; 
                            $arr_etiket = [];
                            foreach($resep as $row) : 
                              if($row->log_time_4 != null && $row->log_time_5 == null) : 
                                $no++;
                                $arr_etiket[] = $row;
                          ?>
                          <tr style="font-size: 14px; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top"><?php echo strtoupper($row->nama_pasien)?></td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endforeach;?>
                        </table>
                        
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-2">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                      <h4 class="widget-title center" style="text-align: center !important">SIAP DIAMBIL</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 16px; border-bottom: 1px solid black;">
                              <th width="30px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center">Waktu</th>
                          </tr>
                          </thead>
                          <?php 
                            $no=0; 
                            $arr_siap_diambil = [];
                            foreach($resep as $row) : 
                              if($row->log_time_5 != null && $row->log_time_6 == null) : 
                                $no++;
                                $arr_siap_diambil[] = $row;
                          ?>
                          <tr style="font-size: 14px; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top"><?php echo strtoupper($row->nama_pasien)?></td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endforeach;?>
                        </table>
                        
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-sm-2">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                      <h4 class="widget-title center" style="text-align: center !important">REKAP ANTRIAN OBAT</h4>
                    </div>
                    <div class="widget-body">
                      <div class="widget-main">
                        <?php
                          // Rekap antrian farmasi: total resep selesai dan rata-rata waktu tunggu
                          $total_selesai = 0;
                          $total_detik = 0;
                          if (isset($resep) && is_array($resep)) {
                            foreach($resep as $row) {
                              if($row->log_time_6 != null && $row->log_time_1 != null) {
                                $total_selesai++;
                                $start = strtotime($row->log_time_1);
                                $end = strtotime($row->log_time_6);
                                $total_detik += ($end - $start);
                              }
                            }
                          }
                          $rata2 = '-';
                          if($total_selesai > 0 && $total_detik > 0) {
                            $avg = $total_detik / $total_selesai;
                            $jam = floor($avg / 3600);
                            $menit = floor(($avg % 3600) / 60);
                            $detik = $avg % 60;
                            $rata2 = sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
                          }
                        ?>
                        
                        <table style="width:100%; font-size:15px;">
                          <tr>
                            <td>Resep Masuk/Diterima</td>
                          </tr>
                          <tr>
                            <td style="text-align:left; font-size: 24px"><b><?php echo count($arr_resep_diterima); ?></b></td>
                          </tr>

                          <tr>
                            <td>Proses Penyediaan Obat</td>
                          </tr>

                          <tr>
                            <td style="text-align:left; font-size: 24px"><b><?php echo count($arr_penyediaan_obat); ?></b></td>
                          </tr>

                          <tr>
                            <td>Proses Racikan</td>
                          </tr>

                          <tr>
                            <td style="text-align:left; font-size: 24px"><b><?php echo count($arr_racikan); ?></b></td>
                          </tr>

                          <tr>
                            <td>Proses Etiket</td>
                          </tr>

                          <tr>
                            <td style="text-align:left; font-size: 24px"><b><?php echo count($arr_etiket); ?></b></td>
                          </tr>

                          <tr>
                            <td>Siap Diambil</td>
                          </tr>

                          <tr>
                            <td style="text-align:left; font-size: 24px"><b><?php echo count($arr_siap_diambil); ?></b></td>
                          </tr>

                          <tr>
                            <td>Rata-rata waktu tunggu obat</td>
                          </tr>

                          <tr>
                            <td style="text-align:left; font-size: 24px"><b><?php echo $rata2; ?></b></td>
                          </tr>

                        </table>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              
            </div>

          </div><!-- /.page-content -->
        </div>

      </div><!-- /.main-content -->
      
      <div class="footer footer-fixed">
        <div class="footer-inner" style="background: #0765a1; color: white; width: 100vw;">
          <div class="footer-content" style="background: #0765a1; color: white; display: flex; justify-content: space-between; align-items: center; width: 100vw; padding: 0 2vw; min-height: 60px;">
            <!-- <div class="center">
              <span style="font-size: 1.5em; font-weight: bold; padding: 20px; font-style: italic;">Partners and Integrated System :</span><br>
              <?php for($i=1; $i<13; $i++) : ?>
              <img style="padding: 10px" height="80px" src="<?php echo base_url().'assets/insani/partner/'.$i.'.png'?>">
              <?php endfor;?>
            </div> -->
            <span class="bigger-120" style="font-size: 1.2vw; font-weight: bold;">
              <span class="white bolder">RS Setia Mitra</span>
              | <i>Smart Hospital System 4.0 </i> &copy; 2018-<?php echo date('Y')?>
            </span>
        </div>
      </div>
      <style>
        .header-fixed {
          position: sticky;
          top: 0;
          left: 0;
          width: 100vw;
          z-index: 1000;
          box-shadow: 0 2px 12px rgba(13,82,128,0.12);
        }
        .footer-fixed {
          position: fixed;
          left: 0;
          bottom: 0;
          width: 100vw;
          z-index: 999;
          box-shadow: 0 -2px 12px rgba(13,82,128,0.12);
        }
        .main-content {
          margin-bottom: 70px; /* space for footer */
        }
        @media (max-width: 900px) {
          .footer-content span {
            font-size: 2vw !important;
          }
        }
        @media (max-width: 600px) {
          .footer-content span {
            font-size: 3vw !important;
          }
        }
      </style>
      </div>

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div><!-- /.main-container -->

    <!-- basic scripts -->

    
    <!--[if !IE]> -->
    <script type="text/javascript">
      window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
    </script>

    <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery-ui.min.js"></script>

    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <!-- ace scripts -->
    <script src="<?php echo base_url()?>assets/js/ace/ace.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.ajax-content.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.touch-drag.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.sidebar.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.submenu-hover.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.widget-box.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings-rtl.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings-skin.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.widget-on-reload.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.searchbox-autocomplete.js"></script>

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    
    <!-- farmnasi -->
    <script>
      $(document).ready( function(){

        // setInterval( function () {
          

          // antrian farmasi
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_farmasi') ?>", '', function (data) {   
            
            // console.log(data.result);
            $.each(data.result, function (key, val) { 
              // console.log(val);
              $.each(val, function (keys, vals) {  
                console.log(key);
                $('#table_'+key+'_'+keys+' tbody').remove();
                var length = vals.length;
                $.each(vals, function (k, v) {  
                  // console.log(k);
                  // console.log(v);
                  if(k < 2){
                    var prefix = (v.kode_perusahaan == 120)?'B':'A';
                    var lgth_no_antrian = v.no_antrian.toString();
                    // console.log(lgth_no_antrian);
                    var no_antrian = (lgth_no_antrian.length == 1) ? '0'+v.no_antrian : v.no_antrian;
                    var icon = (k == 0) ? '<span style="float: right !important"><i class="fa fa-circle green"></i></span>' : '' ;
                    $('<tr style="background: #00669F"><td align="center"><span style="border-right: 1px solid white !important;">'+prefix+' '+no_antrian+' &nbsp;&nbsp;</span></td><td><span>'+v.nama_pasien.substr(0,15)+'</span>'+icon+'</td></tr>').appendTo($('#table_'+v.kode_farmasi_bpjs+'_'+v.kode_dokter+''));
                  }

                  if(length == 1){
                    $('<tr style="background: #00669F"><td align="center"><span style="border-right: 1px solid white !important;">X 00 &nbsp;&nbsp;</span></td><td>-</td></tr>').appendTo($('#table_'+v.kode_farmasi_bpjs+'_'+v.kode_dokter+''));
                  }
                  
                })

                

              })
              
            })

            
          });

        // }, 2000 );
      
        setInterval("reload_page();",3000);

      });
      

      function reload_page(){

        // $('#refresh').load(location.href + ' #time');
        location.reload(location.href);

      }

    </script>

    
</body>
</html>
