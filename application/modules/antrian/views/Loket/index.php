<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <!-- <meta http-equiv="refresh" content="15;url=<?php echo base_url().'antrian'; ?>" /> -->
    <title>Antrian</title>

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <script src='<?php echo base_url()?>/assets/js/jquery.js'></script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <!-- css default for blank page -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>
    <!-- css default for blank page -->
    <!-- Favicon -->

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.custom.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery.gritter.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-editable.css" />

    <style>
      
        .shadow {
          -webkit-box-shadow: 3px 3px 5px 6px #ccc;  /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
          -moz-box-shadow:    3px 3px 5px 6px #ccc;  /* Firefox 3.5 - 3.6 */
          box-shadow:         3px 3px 5px 6px #ccc;  /* Opera 10.5, IE 9, Firefox 4+, Chrome 6+, iOS 5 */
        }
        
    </style>
   
  </head>

  <body class="no-skin">
    <!-- #section:basics/navbar.layout -->
   

    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>

      <!-- /section:basics/sidebar.horizontal -->
      <div class="main-content">
        <div class="main-content-inner">
          <!-- #section:basics/content.breadcrumbs -->
          
          <div class="page-content">
            
            <div class="row">
              <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <!-- MODULE MENU -->
                
                  <?php 
                    
                      /*$arr_color = array('red','yellow','blue','green','olive','lime','orange','fuchsia','lightgray','lightblue'); */
                      $arr_color = array('yellow','olive','lime','orange','fuchsia','lightgray','lightblue'); 
                    shuffle($arr_color);

                  ?>
                    <div class="row">
                    <h3 class="header smaller lighter blue center">
                        <i class="fa fa-pencil-square-o"></i> PENDAFTARAN PASIEN <?php echo ($_GET['type']=='bpjs')?'BPJS':'NON BPJS ATAU UMUM'?>
                      </h3>
                      <?php foreach($klinik as $row_modul) : ?>
                        <div class="col-lg-3 col-xs-3" style="margin-top:0px;height:170px;">
                          <!-- small box -->
                          <button onclick="add(<?php echo $row_modul->jd_kode_dokter ?>,'<?php echo $row_modul->nama_pegawai?>','<?php echo $row_modul->jd_kode_spesialis ?>','<?php echo $row_modul->nama_bagian?>','<?php echo $row_modul->jd_hari ?>','<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai) ?>','<?php echo $row_modul->jd_jam_selesai ?>',<?php echo $row_modul->kuota ?>)" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
                            <div class="inner" style="margin-top:-10px">
                              <h3 style="font-size:18px;color:black;"><?php echo ucwords($row_modul->nama_bagian)?></h3>
                              <p style="font-size:12px;color:black;">
                                <?php echo $row_modul->nama_pegawai?><br>
                                <?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_modul->jd_jam_selesai)?><br>
                                <?php if($type!='online'){ ?> Sisa Kuota : <?php echo $row_modul->kuota; }?><br>
                                  <?php echo $row_modul->jd_keterangan?>
                              </p>
                            </div> 
                                
                            <input type="hidden" id="kode_dokter" val="<?php echo $row_modul->jd_kode_dokter ?>">
                            <input type="hidden" id="kode_spesialis" val="<?php echo $row_modul->jd_kode_spesialis ?>">                      
                          </button>
                        </div>
                      <?php endforeach; ?>
                      
                      <div class="col-lg-3 col-xs-3" style="margin-top:0px;height:180px;">
                        <button onclick="add_other()" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
                          <div class="inner" style="margin-top:-10px">
                            <h3 style="font-size:18px;color:black;">Antrian Lainnya</h3>
                            <p style="font-size:12px;color:black;">
                              Pendaftaran pasien untuk Penunjang Medis, IGD, Rawat Inap, dan lainnya.
                            </p>
                          </div>                      
                        </button>
                      </div>

                    </div>

                    <a href="<?php echo base_url()?>antrian" class="btn btn-lg btn-success" style="position:absolute;left:45%;">
                      <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                      Kembali 
                    </a>
                  
                <!-- END MODULE MENU -->

                <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->


      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div><!-- /.main-container -->


   <!-- MODAL -->

<div id="modalAntrian" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="max-height:90%;  margin-top: 50px; margin-bottom:50px;width:50%">

  <div class="modal-content">

    <div class="modal-header no-padding">

      <div class="table-header" style="text-align:center">

        <h1 style="margin:0 !important">Nomor Antrian</h1>
      

      </div>

    </div>
    

    <div class="modal-body">

      <div class='w3-modal' style='display: block;'>
      <center>
      <div class='w3-modal-content w3-animate-zoom'>
        
        <div class='w3-container'>
        <h1 style='color:black;' id='klinik_modal'></h1>
          <?php if($type=='bpjs'){ ?>
            <h1 style='font-size:500%;color:black;'><b>A <span id="no_modal"></span></b></h1><?php } else { ?>
            <h1 style='font-size:500%;color:black;'><b>B <span id="no_modal"></span></b></h1><?php } ?> 
            <h2 style='color:black;' id='dokter_modal'></h2>
          <h2 style='color:black;'><?php echo strtoupper($type) ?> </h2> 
        </div>
      </div>
      </center>
	  </div>

    </div>

    <!-- <div class="modal-footer no-margin-top">

      <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

        <i class="ace-icon fa fa-times"></i>

        Close

      </button>

    </div> -->

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

</div>

 <!-- MODAL KUOTA PENUH -->

<div id="modalAntrianPenuh" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="max-height:90%;  margin-top: 50px; margin-bottom:50px;width:50%">

  <div class="modal-content">

    <div class="modal-header no-padding">

      <div class="table-header" style="text-align:center">

        <h1 style="margin:0 !important"><?php echo COMP_LONG; ?></h1>
      

      </div>

    </div>
    

    <div class="modal-body">

      <div class='w3-modal' style='display: block;'>
        <center>
        <div class='w3-modal-content w3-animate-zoom'>
          
          <div class='w3-container'>
          <h1 style='color:black;'>Mohon Maaf, Kuota Sudah Penuh</h1>
        </div>
        </center>
      </div>

    </div>

    <!-- <div class="modal-footer no-margin-top">

      <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

        <i class="ace-icon fa fa-times"></i>

        Close

      </button>

    </div> -->

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

</div>

      
  <script>
   
   function add(dokter,nama_dokter,spesialis,nama_spesialis,hari,jam_mulai,jam_selesai,kuota) {
    /* console.log(dokter);
     console.log(spesialis); */
    var dataString = "<?php echo $type ?>"; 
    if((kuota>0) || (dataString=='online')){
      
      data = [];
      data[0] = dataString;
      data[1] = dokter;
      data[2] = nama_dokter;
      data[3] = spesialis;
      data[4] = nama_spesialis;
      data[5] = hari;
      data[6] = jam_mulai;
      data[7] = jam_selesai;
      console.log(data)
          $.ajax({
            url:"<?php echo base_url(); ?>antrian/loket/process",
            data:{data:data}, 
            dataType: "json", 
            type:"POST",       
            success:function (data) {
              //console.log(data)
             
              no = pad(data['no'], 3);

              $('#klinik_modal').text(data['klinik']);
              $('#dokter_modal').text(data['dokter']);
              $('#no_modal').text(no);

              $("#modalAntrian").modal();  

              openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
              setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
            }
      });
      event.preventDefault();
    }else{
      $("#modalAntrianPenuh").modal();  

      setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
    }
     
  }

  function add_other() {
    /* console.log(dokter);
     console.log(spesialis); */
    var dataString = "<?php echo $type ?>"; 
    $.ajax({
      url:"<?php echo base_url(); ?>antrian/loket/process_other",
      data:{type: dataString}, 
      dataType: "json", 
      type:"POST",       
      success:function (data) {
        //console.log(data)
       
        no = pad(data['no'], 3);

        $('#klinik_modal').text(data['klinik']);
        $('#dokter_modal').text(data['dokter']);
        $('#no_modal').text(no);

        $("#modalAntrian").modal();  

        openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
        setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
      }
});

event.preventDefault();
     
  }

   function pad (str, max) {
      str = str.toString();
      return str.length < max ? pad("0" + str, max) : str;
    }

    function openWin(n,klinik,dokter,type,jam_praktek) {
		date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'Desember');
        short_months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ''+days[day]+', '+d+' '+months[month]+' '+year+', '+h+':'+m+':'+s;
        current_date = ''+days[day]+', '+d+'/'+short_months[month]+'/'+year;
       
	
        myWindow = window.open("", "myWindow", "width=2,height=1");

        if(type=='bpjs'){
          var type_antrian = 'A';
          var text_title = 'BPJS';
        }else{
          var type_antrian = 'B';
          var text_title = 'UMUM';
        }

        var html = 
              '<div style="font-family: calibri" class="center">\
                <center>\
                <table align="center" border="0" width="100%">\
                <tr>\
                  <td colspan="2" align="center"><span style="font-size:150% !important"><?php echo strtoupper(COMP_LONG); ?></span><br><small style="font-size:9px !important"><?php echo COMP_ADDRESS?></small><hr></td>\
                </tr>\
                <tr>\
                  <td align="center" colspan="2"><span style="font-size:11px;margin-top:0">PENDAFTARAN PASIEN '+text_title+'</span><br><span style="font-size:300%;"> '+type_antrian+' '+n+' <small style="font-size:10px !important;margin-top:0"><br>Nomor Antrian</small><br><span style="font-size:20% !important;margin-top:0"><br>'+klinik.toUpperCase()+'<br>'+dokter+'<br>'+current_date+', '+jam_praktek+'</span> </td>\
                </tr>\
                </table>\
                <table align="center" width="100%">\
                <tr style="font-size:11px;">\
                  <td><br><br></td>\
                </tr>\
               ';

        myWindow.document.write(html);

        
        myWindow.print();
        myWindow.close();
      }


  </script>

    
  </body>
</html>

























