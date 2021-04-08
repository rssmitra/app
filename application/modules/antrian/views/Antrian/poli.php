 <div class="page-content-main" style="background-color: black !important">
    <div class="no-padding" style="width:100%;">
      <marquee behavior="scroll" direction="left" style="color: white;margin-top:3px;"> Bagi pasien yang belum terdaftar pada Display Antrian Poli/Klinik diharapkan untuk menunggu antrian diluar agar tidak terjadi kerumunuan di ruang tunggu poli/klinik. | <?php echo COMP_MOTTO?> </marquee>
    </div>
    <!-- <div style="width:10%;float:left;margin-top: 5px;color: white; text-align: center;">
      <div id="refresh"><h4 style="margin:0;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h4></div>
      <p style="margin:0;font-size:16px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
    </div> -->
    <div class="row no-padding">
      <div class="col-md-12 no-padding" style="padding-right: 5px !important">
          <?php for($box=1;$box<6;$box++) :?>
            <div class="col-sm-6 no-padding" style="color: white; padding: 5px !important">
                <div class="widget-box">
                  <div class="widget-header widget-header-flat widget-header-small" style="background: darkslategrey;color: white;">
                    <h4 class="widget-title" style="font-weight: bold; padding: 5px !important">
                      KLINIK SPESIALIS JANTUNG DAN PEMBULUH DARAH
                    </h4>

                  </div>

                  <div class="widget-body" style=" background: linear-gradient(45deg, #128812, transparent); font-weight: bold">
                    <div class="widget-main">
                      <div class="center">
                        <span style="text-align: left !important">Sedang berlangsung..</span><br>
                        <span style="text-align: center; font-size: 1.8em">MUHAMMAD AMIN LUBIS</span>
                      </div>
                      <div class="hr hr8 hr-double"></div>

                      <div class="clearfix">
                        <div class="grid2">
                          <span class="grey">
                            <i class="ace-icon fa fa-angle-double-left fa-2x blue"></i>
                            <span style="">&nbsp; Selanjutnya..</span>
                          </span>
                          <h4 class="bigger center">Muhammad Zaid Hawari Lubis</h4>
                        </div>

                        <div class="grid2">
                          <span class="grey">
                            <i class="ace-icon fa fa-angle-double-left fa-2x blue"></i>
                            <span style="">&nbsp; Bersiap..</span>
                          </span>
                          <h4 class="bigger center">Hengky Zulkarnaen</h4>
                        </div>
                      </div>
                    </div><!-- /.widget-main -->
                  </div><!-- /.widget-body -->
                </div>
            </div>
            

            <!-- <div class="col-xs-4 widget-container-col ui-sortable no-padding" style="padding-right: 20px" id="widget-container-col-1">
                <div class="alert alert-success" style="background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;">
                  <div class="text-no" style="width:20%;float:left;border-right:2px solid white; margin-right: 20px; text-align: center">
                    <span><?php echo $box?></span>
                  </div>
                  <div class="nama-pasien-antrian" id="antrian-ke-<?php echo $box;?>"></div> 
                </div>
            </div> -->
          <?php endfor; ?>
        
      </div>
    </div><!-- /.row -->
  </div>