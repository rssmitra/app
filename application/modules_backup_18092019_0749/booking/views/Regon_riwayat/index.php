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
    <!--  -->
    <form class="form-horizontal" method="post" id="form_Tmp_mst_function" action="<?php echo site_url('setting/Tmp_mst_function/process')?>" enctype="multipart/form-data">
        <br>
        <div class="form-group">

          <label class="control-label col-md-2"><b>Pilih Tahun</b></label>            

          <div class="col-md-3">            

            <div class="input-group">

              <?php echo $this->master->get_tahun('','tahun','tahun','form-control','','')?>

              <span class="input-group-btn">

                <button type="button" id="btn_search_pasien" class="btn btn-default btn-sm">

                  <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>

                  Cari

                </button> &nbsp;&nbsp;&nbsp;
                

              </span>

            </div>

          </div>

        </div>
    </form>

    <p><h4 style="margin-left:1.5% !important"><i class="fa fa-history"></i> Riwayat Booking</h4></p>

    <div id="timeline-1">
      <div class="row">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
          <!-- #section:pages/timeline -->
          <?php 
            foreach($riwayat as $row_riwayat) : 
              $pasien = json_decode($row_riwayat->log_detail_pasien);
              $transaksi = json_decode($row_riwayat->log_transaksi);
          ?>

          <div class="timeline-container">
            <div class="timeline-label">
              <!-- #section:pages/timeline.label -->
              <span class="label label-primary arrowed-in-right label-lg">
                <b><?php echo $this->tanggal->formatDateTime($row_riwayat->created_date)?></b>
              </span>
              <!-- /section:pages/timeline.label -->
            </div>



            <div class="timeline-items">

              <!-- #section:pages/timeline.item -->
              <div class="timeline-item clearfix">
                <!-- #section:pages/timeline.info -->
                <div class="timeline-info">
                  <img alt="Susan't Avatar" src="<?php echo isset($profile_owner->path_foto) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$profile_owner->path_foto:base_url().'assets/avatars/user.jpg'?>" />
                </div>

                <!-- /section:pages/timeline.info -->
                <div class="widget-box transparent">

                  <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">CODE <?php echo $row_riwayat->regon_booking_kode?></h5>
                    <span class="widget-toolbar">
                      <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                      </a>
                    </span>
                  </div>

                  <div class="widget-body">
                    <div class="widget-main">
                    <h5 style="margin-top:-1%"><b>Kode Booking (<?php echo $row_riwayat->regon_booking_kode?>)</b></h5>
                      <?php echo ucwords($transaksi->klinik->nama_bagian)?><br>
                      <?php echo $this->tanggal->formatDateForm($row_riwayat->regon_booking_tanggal_perjanjian)?><br>
                      <?php echo $transaksi->dokter->nama_pegawai?><br>
                      Status : <?php echo ($row_riwayat->regon_booking_status==0)?'<b>Dalam proses menunggu...</b>':'Selseai'?>
                      <div class="widget-toolbox clearfix">
                        <div class="pull-left">
                          <i class="ace-icon fa fa-hand-o-right grey bigger-125"></i>
                          <a href="#" class="bigger-110" onclick="click_to_show_detail('<?php echo $row_riwayat->regon_booking_kode?>')">Klik selengkapnya &hellip;</a>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>
              
              <?php if($row_riwayat->regon_booking_status==1) :?>
                <!-- CANCEL -->
                <div class="timeline-item clearfix">

                  <div class="timeline-info">
                    <i class="timeline-indicator ace-icon fa fa-bug btn btn-danger no-hover"></i>
                  </div>

                  <div class="widget-box widget-color-red2">
                    <div class="widget-header widget-header-small">
                      <h5 class="widget-title smaller">Transaksi Dibatalkan</h5>
                      <span class="widget-toolbar no-border">
                        <i class="ace-icon fa fa-clock-o bigger-110"></i>
                        9:15
                      </span>
                      <span class="widget-toolbar">
                        <a href="#" data-action="collapse">
                          <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                      </span>
                    </div>
                    <div class="widget-body">
                      <div class="widget-main">
                        Transaksi dengan Kode Booking <b>SD124</b> telah dibatalkan
                      </div>
                    </div>
                  </div>

                </div>
              <?php endif; ?>

              <?php if($row_riwayat->regon_booking_status==2) :?>
                <div class="timeline-item clearfix">

                  <div class="timeline-info">
                    <i class="timeline-indicator ace-icon fa fa-bug btn btn-success no-hover"></i>
                  </div>

                  <div class="widget-box widget-color-green">
                    <div class="widget-header widget-header-small">
                      <h5 class="widget-title smaller">Pemesanan Berhasil</h5>
                      <span class="widget-toolbar no-border">
                        <i class="ace-icon fa fa-clock-o bigger-110"></i>
                        9:15
                      </span>
                      <span class="widget-toolbar">
                        <a href="#" data-action="collapse">
                          <i class="ace-icon fa fa-chevron-up"></i>
                        </a>
                      </span>
                    </div>
                    <div class="widget-body">
                      <div class="widget-main">
                        Pemesanan dengan Kode Booking <b>SD124</b> telah dibatalkan
                      </div>
                    </div>
                  </div>

                </div>
              <?php endif;?>


            </div><!-- /.timeline-items -->

          </div>
          <?php endforeach;?>
          <!-- /.timeline-container -->
        </div>
      </div>
    </div>
    

  </div><!-- /.col -->
</div><!-- /.row -->

<script type="text/javascript">

  function click_to_show_detail(kode_booking)

  {  

    $('#title_modal_id').text('RIWAYAT BOOKING - '+ kode_booking);

    $('#show_detail_riwayat_div_id').load('booking/regon_riwayat/detail_riwayat/'+kode_booking+'');

    $("#modal_detail_riwayat").modal();
      
  }
</script>
<!-- MODAL DAFTAR PERJANJIAN -->

<div id="modal_detail_riwayat" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px; width:100%; padding-right:20px">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="title_modal_id">RIWAYAT PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

      <!-- show detail -->
      <div id="show_detail_riwayat_div_id"></div>

      </div>

      <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>





