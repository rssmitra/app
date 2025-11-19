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
        padding: 0px 10px 10px !important;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100% !important;
        /* min-height: 670px; */
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

    table tbody{
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
      background: #006a9f82;
      color: black;
    }
    

    /* Wrapper untuk scroll vertikal pada tabel antrian */
    .scroll-table-wrapper {
      height: 55vh;
      min-height: 200px;
      max-height: calc(100vh - 260px);
      overflow: hidden;
      margin-bottom: 0;
      background: transparent;
      position: relative;
    }
    .scroll-table-inner {
      height: 100%;
      max-height: 100%;
      overflow-y: auto;
      overflow-x: hidden;
      scroll-behavior: smooth;
      background: transparent;
    }
    .scroll-table-inner table {
      border-collapse: separate;
      border-spacing: 0;
    }
    .scroll-table-inner thead th {
      position: sticky;
      top: 0;
      z-index: 2;
      background: #00669F !important;
      color: #fff !important;
      box-shadow: 0 2px 2px rgba(0,0,0,0.04);
      padding: 5px
    }
    .table {
      width: 100%;
      margin-bottom: 0;
      table-layout: auto;
      background: #fff;
    }
  </style>

  <body class="no-skin">
    <!-- STAMP LABEL -->
    <!-- <div class="uji-coba-stamp">Sedang Uji Coba</div> -->
    <style>
      .uji-coba-stamp {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 20000;
        background: rgba(255, 0, 0, 0.85);
        color: #fff;
        font-size: 2.2em;
        font-weight: bold;
        padding: 12px 32px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(255,0,0,0.18);
        letter-spacing: 2px;
        transform: rotate(8deg);
        opacity: 0.92;
        pointer-events: none;
        user-select: none;
      }
      @media (max-width: 600px) {
        .uji-coba-stamp {
          font-size: 1.2em;
          padding: 6px 14px;
          top: 10px;
          right: 10px;
        }
      }
    </style>
  
    <div class="main-container ace-save-state" id="main-container" style="min-height: 100vh; display: flex; flex-direction: column; background: black">
      <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
      </script>

      <div class="main-content" style="flex: 1 1 auto; overflow-y: auto; background: black">

        <!-- <div class="col-md-12" style="padding: 10px">
            <div style="float: left; margin-left: 20px; margin-top: 10px">
              <img alt="" src="<?php echo base_url().COMP_ICON_INSANI?>" width="200px">
            </div>
            <div style="float: right; margin-top: 10px; margin-right: 10px">
              <span class="title-text"><img alt="" src="<?php echo base_url().COMP_ICON_BY_INSANI?>" width="150"></span>
            </div>
        </div> -->

        

        <div class="col-md-12 header-fixed" style="background: #00669F; color: white; padding: 5px; border-top-left-radius: 15px; border-top-right-radius: 15px; position: sticky; top: 0; z-index: 1000;">
          <div style="font-size: 30px; font-weight: bold; float: left; padding-left: 20px">ANTRIAN RESEP OBAT</div>
          <div style="text-align: right; font-size: 25px; margin-top: 3px; float: right; margin-right: 20px" >
            <i class="fa fa-calendar"></i> <?php date_default_timezone_set("Asia/Jakarta"); echo date('l, d F Y') ?> &nbsp; <i class="fa fa-clock-o"></i>  
            <span id="refresh">&nbsp;
                <span id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></span> WIB
          </div>
        </div>

        <div class="main-content-inner" style="background: black">
          <div class="page-content" style="background: black">
            <div id="page-area-content" style="background: black">
              <!-- section antrian farmasi -->
              <div id="section_antrian_farmasi" class="row" style="margin-top: 10px">
                
                <!-- CONTENT HERE -->
                <div class="col-sm-3">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat" style="padding: 10px">
                      <span class="widget-title center" style="text-align: center !important; font-size: 3em">RESEP DITERIMA</span>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <div class="scroll-table-wrapper"><div class="scroll-table-inner" id="scroll-diterima">
                        <table width="100%" style="margin-bottom: 0;">
                          <thead>
                            <tr style="font-size: 1.8em; border-bottom: 1px solid black;">
                              <th width="40px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center"><i class="fa fa-clock-o"></i></th>
                            </tr>
                          </thead>
                          <tbody id="resep-diterima-tbody">
                            <?php 
                              $no=0; 
                              $arr_resep_diterima = [];
                              // echo '<pre>';print_r($resep_diterima);die;
                              foreach($resep_diterima as $row) :
                                  $no++;
                                  $arr_resep_diterima[] = $row;
                            ?>
                            <tr style="font-size: 1.8em; border-bottom: 1px solid grey;">
                              <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                              <td style="vertical-align: top">
                              <?php
                              $nama = str_replace($text_hide,'', $row->nama_pasien);
                              $nama = trim(preg_replace('/\s+/', ' ', $nama));
                              $parts = explode(' ', $nama);
                              if(count($parts) <= 2) {
                                echo strtoupper(implode(' ', $parts));
                              } else {
                                $output = array_slice($parts, 0, 2);
                                for($i=2; $i<count($parts); $i++) {
                                  $output[] = strtoupper(substr($parts[$i],0,1)).'';
                                }
                                echo strtoupper(implode(' ', $output));
                              }
                              ?>
                              </td>
                              <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                            </tr>
                            <?php endforeach;?>
                          </tbody>
                        </table></div></div>
                      </div>
                    </div>
                    <div style="width: 100%; text-align: center; margin-bottom: 10px; background: #0765a1; margin-top: 10px;">
                    <span style="color: white; font-size: 20px">TOTAL RESEP DITERIMA</span><br><span style="font-size: 5em; color: white; font-weight: bold"><?php echo count($arr_resep_diterima)?></span>
                    </div>
                  </div>
                </div>

                <div class="col-sm-3 no-padding">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat" style="padding: 10px">
                      <h4 class="widget-title center" style="text-align: center !important; font-size: 3em">OBAT RACIKAN</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <div class="scroll-table-wrapper"><div class="scroll-table-inner" id="scroll-racikan">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 1.8em; border-bottom: 1px solid black;">
                              <th width="40px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center"><i class="fa fa-clock-o"></i></th>
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
                          <tr style="font-size: 1.8em; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top">
                              <?php
                              $nama = str_replace($text_hide,'', $row->nama_pasien);
                              $nama = trim(preg_replace('/\s+/', ' ', $nama));
                              $parts = explode(' ', $nama);
                              if(count($parts) <= 2) {
                                echo strtoupper(implode(' ', $parts));
                              } else {
                                $output = array_slice($parts, 0, 2);
                                for($i=2; $i<count($parts); $i++) {
                                  $output[] = strtoupper(substr($parts[$i],0,1)).'';
                                }
                                echo strtoupper(implode(' ', $output));
                              }
                              ?>
                            </td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endforeach;?>
                        </table></div></div>
                        
                      </div>
                    </div>
                    <div style="width: 100%; text-align: center; margin-bottom: 10px; background: #0765a1; margin-top: 10px;">
                    <span style="color: white; font-size: 20px">TOTAL RESEP RACIKAN</span><br><span style="font-size: 5em; color: white; font-weight: bold"><?php echo count($arr_racikan)?></span>
                    </div>
                    
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat" style="padding: 10px">
                      <h4 class="widget-title center" style="text-align: center !important; font-size: 3em">ETIKET</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <div class="scroll-table-wrapper"><div class="scroll-table-inner" id="scroll-etiket">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 1.8em; border-bottom: 1px solid black;">
                              <th width="40px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center"><i class="fa fa-clock-o"></i></th>
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
                          <tr style="font-size: 1.8em; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top">
                              <?php
                              $nama = str_replace($text_hide,'', $row->nama_pasien);
                              $nama = trim(preg_replace('/\s+/', ' ', $nama));
                              $parts = explode(' ', $nama);
                              if(count($parts) <= 2) {
                                echo strtoupper(implode(' ', $parts));
                              } else {
                                $output = array_slice($parts, 0, 2);
                                for($i=2; $i<count($parts); $i++) {
                                  $output[] = strtoupper(substr($parts[$i],0,1)).'';
                                }
                                echo strtoupper(implode(' ', $output));
                              }
                              ?>
                            </td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endforeach;?>
                        </table></div></div>
                        
                      </div>
                    </div>
                    <div style="width: 100%; text-align: center; margin-bottom: 10px; background: #0765a1; margin-top: 10px;">
                    <span style="color: white; font-size: 20px">TOTAL PROSES ETIKET</span><br><span style="font-size: 5em; color: white; font-weight: bold"><?php echo count($arr_etiket)?></span>
                    </div>

                  </div>
                </div>

                <div class="col-sm-3" style="padding-left: 0px !important; padding-right: 10px !important">
                  <div class="widget-box">
                    <div class="widget-header widget-header-flat" style="padding: 10px">
                      <h4 class="widget-title center" style="text-align: center !important; font-size: 3em">SIAP DIAMBIL</h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main">
                        <div class="scroll-table-wrapper"><div class="scroll-table-inner" id="scroll-siapdiambil">
                        <table width="100%">
                          <thead>
                          <tr style="font-size: 1.8em; border-bottom: 1px solid black;">
                              <th width="40px">No</th>
                              <th>Nama Pasien</th>
                              <th class="center"><i class="fa fa-clock-o"></i></th>
                          </tr>
                          </thead>
                          <?php 
                            $no=0; 
                            $arr_siap_diambil = [];
                            foreach($resep as $row) : 
                              if($row->log_time_5 != null && $row->log_time_6 == null) : 
                                if($no <= 30) :
                                $no++;
                                $arr_siap_diambil[] = $row;
                          ?>
                          <tr style="font-size: 1.8em; border-bottom: 1px solid grey;">
                            <td style="vertical-align: top"><?php echo strtoupper($no)?></td>
                            <td style="vertical-align: top">
                              <?php
                                $nama = str_replace($text_hide,'', $row->nama_pasien);
                                $nama = trim(preg_replace('/\s+/', ' ', $nama));
                                $parts = explode(' ', $nama);
                                if(count($parts) <= 2) {
                                  echo strtoupper(implode(' ', $parts));
                                } else {
                                  $output = array_slice($parts, 0, 2);
                                  for($i=2; $i<count($parts); $i++) {
                                    $output[] = strtoupper(substr($parts[$i],0,1)).'';
                                  }
                                  echo strtoupper(implode(' ', $output));
                                }
                              ?>
                            </td>
                            <td align="center" style="vertical-align: top"><?php echo date('H:i', strtotime($row->tgl_trans))?></td>
                          </tr>
                          <?php endif; endif; endforeach;?>
                        </table></div></div>
                        
                      </div>
                    </div>
                    <div style="width: 100%; text-align: center; margin-bottom: 10px; background: #0765a1; margin-top: 10px;">
                    <span style="color: white; font-size: 20px">TOTAL RESEP SIAP DIAMBIL</span><br><span style="font-size: 5em; color: white; font-weight: bold"><?php echo count($arr_siap_diambil)?></span>
                    </div>
                  </div>
                </div>

              </div>
              
            </div>

          </div><!-- /.page-content -->
        </div>

      </div><!-- /.main-content -->
      
      <div class="footer footer-fixed" style="background: black">
        <div class="footer-inner" style="background: #0765a1; color: white; width: 100vw; ">
          <div class="footer-content" style="background: #0765a1; color: white; display: flex; justify-content: space-between; align-items: center; width: 100vw; padding: 0 2vw; min-height: 60px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <span class="bigger-120" style="font-size: 2.2em !important; font-weight: bold;">
              <span class="white bolder">RS Setia Mitra</span>
              | <i>Smart Hospital System 4.0 </i> &copy; 2018-<?php echo date('Y')?>
            </span>

            <span class="pull-right bigger-120" style="font-size: 2em; font-weight: bold;">
              <i>Rata-rata Waktu Tunggu Obat : </i> 
              <?php
                // Hitung rata-rata waktu tunggu obat
                $arr_seconds = [];
                if (isset($resep) && is_array($resep)) {
                  foreach($resep as $row) {
                    if($row->log_time_5 != null && $row->log_time_1 != null) {
                      $arr_seconds[] = $this->tanggal->diffHourMinuteReturnSecond($row->log_time_1, $row->log_time_5);
                    }
                  }
                }
                if (count($arr_seconds) > 0) {
                  $rata2 = $this->tanggal->convertHourMinutesSecond(array_sum($arr_seconds)/count($arr_seconds));
                } else {
                  $rata2 = '00:00:00';
                }
              ?>
              <br>
              <span id="avg-waktu-tunggu" style="font-size: 3em; font-weight: bold; color: #ffeb3b"><?php echo $rata2; ?></span>
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
        // Auto scroll vertikal berjalan untuk setiap tabel

        // Scroll berjalan vertikal untuk setiap tabel
        // Scroll berjalan vertikal untuk setiap tabel, reload page setelah 1 siklus scroll penuh (bawah-atas)
        function autoScrollTable(id, onFullCycle) {
          var el = document.getElementById(id);
          if (!el) return false;
          var direction = 1;
          var scrollStep = 1;
          var scrollDelay = 30;
          var scrollInterval;
          var hasCycled = false;
          var hasScrollable = (el.scrollHeight > el.clientHeight + 1);
          function scrollFn() {
            if (!hasScrollable) return;
            if (direction === 1) {
              if (el.scrollTop + el.clientHeight < el.scrollHeight - 1) {
                el.scrollTop += scrollStep;
              } else {
                direction = -1;
                setTimeout(scrollFn, 1000); // jeda di bawah
                return;
              }
            } else {
              if (el.scrollTop > 0) {
                el.scrollTop -= scrollStep;
              } else {
                direction = 1;
                if (!hasCycled) {
                  hasCycled = true;
                  if (typeof onFullCycle === 'function') onFullCycle();
                }
                setTimeout(scrollFn, 1000); // jeda di atas
                return;
              }
            }
            scrollInterval = setTimeout(scrollFn, scrollDelay);
          }
          scrollFn();
          // Simpan interval agar bisa direset jika reload data
          return hasScrollable ? function stopScroll() { clearTimeout(scrollInterval); } : false;
        }

        // Jalankan scroll untuk semua tabel, reload page setelah salah satu tabel selesai 1 siklus scroll
        var stopScrollers = [];
        var hasReloaded = false;
        function onAnyTableFullCycle() {
          if (!hasReloaded) {
            hasReloaded = true;
            reload_page();
          }
        }
        var reloadTimeout = null;
        function startAllScrollers() {
          stopAllScrollers();
          hasReloaded = false;
          if (reloadTimeout) { clearTimeout(reloadTimeout); reloadTimeout = null; }
          stopScrollers = [
            autoScrollTable('scroll-diterima', onAnyTableFullCycle),
            autoScrollTable('scroll-racikan', onAnyTableFullCycle),
            autoScrollTable('scroll-etiket', onAnyTableFullCycle),
            autoScrollTable('scroll-siapdiambil', onAnyTableFullCycle)
          ];
          // Jika semua tabel tidak scrollable, reload otomatis 1 menit
          if (stopScrollers.every(function(s){return s===false;})) {
            reloadTimeout = setTimeout(reload_page, 30000);
          }
        }
        function stopAllScrollers() {
          stopScrollers.forEach(function(stop){ if(typeof stop === 'function') stop(); });
          stopScrollers = [];
        }
        startAllScrollers();

        // antrian farmasi reload
        $.getJSON("<?php echo site_url('display_antrian/reload_antrian_farmasi') ?>", '', function (data) {   
          $.each(data.result, function (key, val) { 
            $.each(val, function (keys, vals) {  
              $('#table_'+key+'_'+keys+' tbody').remove();
              var length = vals.length;
              $.each(vals, function (k, v) {  
                if(k < 2){
                  var prefix = (v.kode_perusahaan == 120)?'B':'A';
                  var lgth_no_antrian = v.no_antrian.toString();
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
          // Restart auto scroll setelah reload data
          setTimeout(startAllScrollers, 300);
        });
  // Hapus interval reload_page otomatis, reload hanya setelah scroll selesai
      });
      function reload_page(){
        location.reload(location.href);
      }
    </script>

    
</body>
</html>
