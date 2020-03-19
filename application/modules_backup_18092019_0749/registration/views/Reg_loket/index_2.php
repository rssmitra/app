<link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="page-header">    

      <h1>      

        <?php echo $title?>        

        <small>        

          <i class="ace-icon fa fa-angle-double-right"></i>          

          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>          

        </small>        

      </h1>      

    </div> 

    <!-- MODULE MENU -->

      <?php 
        foreach ($loket as $key => $value) :
          /*hitung waktu buka loket*/
          $loket_open = ($key=='-')?'':$this->tanggal->selisih_waktu($key, '-2');
          /*algoritma untuk mengetahui loket yang dibuka atau belum*/
          $current_time = strtotime( date('Y-m-d H:i') ); 
          //print_r($current_time);die;

          $text = '';
          if($key!='-') :
          $start_date = strtotime( date('Y-m-d').' '.$loket_open ); 
          $end_date_dokter = ($value[0]->jd_jam_selesai==NULL)?'23:00':$value[0]->jd_jam_selesai;
          $end_date = strtotime(date('Y-m-d').' '.$this->tanggal->formatTime( $end_date_dokter ) ); 
                  //10                12             10              14
          if ($current_time >= $start_date && $current_time <= $end_date)
          {
              $text = 'Open';
              //Anda bisa masukkan proses yang dilakukan jika pendaftaran dibuka
          }
          else
          {
              if($current_time <= $start_date)
              {
                   $text = 'Waiting';
                  //Anda bisa masukkan pesan atau proses jika sudah ditutup
                  
              }else{

                $text = 'Closed';

              }

          }


          endif;
          

      ?>
        <div class="row">
        <h3 class="header smaller lighter blue">
            <i class="fa fa-briefcase"></i> Loket <?php echo $loket_open.' '.$text?>
          </h3>
          <?php 
            foreach($value as $row_loket) : 
               $arr_color = array('bg-red','bg-yellow','bg-aqua','bg-blue','bg-light-blue','bg-green','bg-navy','bg-teal','bg-olive','bg-lime','bg-orange','bg-fuchsia','bg-purple','bg-maroon','bg-black', 'bg-grey'); 
                shuffle($arr_color);

                

          ?>
            <div class="col-lg-4 col-xs-3" style="margin-top:-10px">
              <!-- small box -->
              <div class="small-box <?php echo array_shift($arr_color)?>">
                <div class="inner">
                  <h3 style="font-size:14px"><?php echo ucwords($row_loket->nama_bagian)?></h3>
                  <p style="font-size:12px"><?php echo ucwords($row_loket->nama_pegawai)?><br><?php echo $this->tanggal->formatTime($row_loket->jd_jam_mulai)?> s/d <?php echo $this->tanggal->formatTime($row_loket->jd_jam_selesai)?><br>Status : Open</p>
                </div>
                <div class="icon" style="margin-top:-10px">
                  <i class=""></i>
                </div>
                <a href="#" onclick="getMenu('registration/Reg_klinik')" class="small-box-footer"> Open Loket  <i class="fa fa-arrow-circle-right"></i></a>
                
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach;?>
    <!-- END MODULE MENU -->

    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div>