<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>Display Antrian Loket Pendaftaran</title>
    <script src='<?php echo base_url()?>/assets/js/jquery.js'></script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />

    <!-- css default for blank page -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="shortcut icon" href="<?php echo base_url().'assets/insani/favicon_rssm.png'; ?>">
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>

    <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/datatables/js/jquery.dataTables.min.js')?>"></script>

    <script>
      $(document).ready( function(){

        setInterval( function () {
        
          $.getJSON("<?php echo site_url('display_antrian/process') ?>", '', function (data) {              
            console.log(data)
            $('#auto1 h1').remove();
            $('#auto2 h1').remove();
            $('#auto3 h1').remove();
            $('#auto4 h1').remove();

            $.each(data, function (i, o) {    
               //console.log(data);
              if(o!=0){
                no =  pad(o.ant_no, 3);
                type = (o.ant_type=='bpjs')?'A':(o.ant_type=='umum')?'B':'C';

                if( o.ant_no != 0 ){
                  $('<h1 style="margin:0;font-size:88px;font-weight:bold;text-align:center;text-shadow: 5px 3px 6px black;">'+type+' '+no+'</h1>').appendTo($('#auto'+i+''));
                }
                       
              }

            });

          });

        }, 2000 );
      
      });
      
      
      function pad (str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
      }
    </script>

    <style>
      .footer {
        /* position: fixed; */
        left: 0;
        bottom: 0;
        width: 100%;
        height:55px;
        color: white;
        text-align: center;
      }

      .display-video{
        height:740px;
        background-color:white;
      }

      @media only screen 
      and (min-width : 1824px) {
        .display-video{
          height:auto;
          background-color:black;
        }
      }

      @font-face { font-family: MyriadPro; src: url('assets/fonts/MyriadPro-Bold.otf'); } 

      h1 {
        font-family: MyriadPro;
      }

      .inner h1{
        font-family: arial;
      }

    </style>

</head>

<body style="background: url(assets/images/unit-pendaftaran.jpg) fixed; padding-left: 10px">
 
  <div class="page-content" style="padding-left: 10px; background: url(assets/images/unit-pendaftaran.jpg) fixed;" >

    <div class="col-xs-12" >

      <div class="col-lg-8 no-padding" style="margin-left:-12px;margin-top: 5px">

        <div class="row" style="height:120px;background: #f7f7f712 ;color:white; border-radius: 10px; text-align: center">
          <img alt="" src="<?php echo COMP_ICON_INSANI?>" width="300px">
        </div>

        <div class="row" style="padding-top: 5px">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

          <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <li data-target="#myCarousel" data-slide-to="1"></li>
              <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
              <div class="item active">
                <img src="<?php echo base_url().'assets/insani/banner/img_reg_online.png'?>" alt="Los Angeles" style="width:100%;">
              </div>

              <div class="item">
                <img src="<?php echo base_url().'assets/insani/banner/paket_mcu_haji.jpeg'?>" alt="Chicago" style="width:100%;">
              </div>
            
              <div class="item">
                <img src="<?php echo base_url().'assets/insani/banner/img_bpjs_naker.png'?>" alt="New york" style="width:100%;">
              </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>

        <div class="row center" style="background: white; margin-top: 5px; border-radius: 5px;">
          <center><span style="font-weight: bold; font-size: 1.1em; font-style: italic; text-align: center; color: #01679c ">Our Partners & Integrated System : </span></center>
          <div style="padding: 10px; text-align: center">
            <?php for($i=1; $i<16; $i++): if($i != 4) :?>
              <img src="<?php echo base_url().'assets/insani/partner/'.$i.'.png'?>" width="125px" style="padding: 5px">
            <?php endif; endfor; ?>
          </div>
        </div>

        <!-- <div class="row center" style="background: #01679f0d; margin-top: 5px; border-radius: 5px; min-height: 188px !important; height:auto;">
          <div class="inner">
            <center>
              <img src="<?php echo base_url().'assets/insani/banner/adv.gif'?>" style="width: 100%; height: 100%; vertical-align: middle">
            </center>
          </div>
        </div> -->

        <!-- <div class="row display-video">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/videoseries?si=mSLFtkAxMtDCeaWk&amp;autoplay=1&controls=0&mute=1&loop=1&cc_load_policy=1&amp;list=PLgCb4LtDMc4lxlOq7DnccbzVkmM5I_aXy" title="<?php echo COMP_FULL; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"></iframe>
        </div> -->

      </div>

      <div class="col-lg-4" style="margin-left:12px;padding-right:0">

        <div class="row" >

          <div class="small-box" style="height:auto;margin:10px;background: #b6d55f ;color:white;border-radius:5px;">
            
              <div class="header" style="height:30px;background-color:#8eab3d;border-radius:15px 0 0 50px;">
                <p style="font-size:20px;margin-left:12%;"><b>Loket</b><span style="margin-left: 39%">Nomor Urut</span></p>
                
              </div>
              
              <div class="inner">

                <div style="width:30%;float:left;border-right:2px solid white">
                  <h1 style="margin-top:15px;font-size:85px;text-align:center;text-shadow: 5px 3px 6px black;">1</h1>
                </div>

                <div id="auto1" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:120px;"></div>
                        
              </div>
            
          </div>

          <div class="small-box" style="height:auto;margin:10px;background: #f08121;color:white;border-radius:5px;">
            
            <div class="header" style="height:30px;background-color:#ad560b;border-radius:15px 0 0 50px;">
              <p style="font-size:20px;margin-left:12%;"><b>Loket</b><span style="margin-left: 39%">Nomor Urut</span></p>
            </div>
            
            <div class="inner">

              <div style="width:30%;float:left;border-right:2px solid white">
                <h1 style="margin-top:15px;font-size:85px;text-align:center;text-shadow: 5px 3px 6px black;">2</h1>
              </div>

              <div id="auto2" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:120px;"></div>
                      
            </div>
            
          </div>

          <div class="small-box" style="height:auto;margin:10px;background : #ae7bb0 ;color:white;border-radius:5px;">
              <div class="header" style="height:30px;background-color:#915e93;border-radius:15px 0 0 50px;">
                  <p style="font-size:20px;margin-left:12%;"><b>Loket</b><span style="margin-left: 39%">Nomor Urut</span></p>
              </div>
              <div class="inner">

                <div style="width:30%;float:left;border-right:2px solid white">
                  <h1 style="margin-top:15px;font-size:85px;text-align:center;text-shadow: 5px 3px 6px black;">3</h1>
                </div>

                <div id="auto3" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:120px;">
                  
                </div>
                        
              </div>
          </div>

          <div class="small-box" style="height:auto;margin:10px;background: #de1f8c ;color:white;border-radius:5px;">
          
            <div class="header" style="height:30px;background-color: #a5236d;border-radius:15px 0 0 50px;">
              <p style="font-size:20px;margin-left:12%;"><b>Loket</b><span style="margin-left: 39%">Nomor Urut</span></p>
              
            </div>
          
            <div class="inner">

              <div style="width:30%;float:left;border-right:2px solid white">
                <h1 style="margin-top:15px;font-size:85px;text-align:center;text-shadow: 5px 3px 6px black;">4</h1>
              </div>

              <div id="auto4" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:120px;"></div>
                      
            </div>
          
          </div>

          <!-- <div class="small-box" style="height:auto;margin:10px;background: #01679c ;color:white;border-radius:5px;">
          
            <div class="header" style="height:30px;background-color: #3a93c1c7;border-radius:15px 0 0 50px;">
              <p style="font-size:20px;margin-left:12%;"><b>Loket</b><span style="margin-left: 39%">Nomor Urut</span></p>
              
            </div>
          
            <div class="inner">

              <div style="width:30%;float:left;border-right:2px solid white">
                <h1 style="margin-top:15px;font-size:85px;text-align:center;text-shadow: 5px 3px 6px black;">5</h1>
              </div>

              <div id="auto5" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:120px;"></div>
                      
            </div>
          
          </div> -->

          <!-- <div class="small-box" style="min-height: 250px; height:auto;margin:10px;background: #01679f0d ;color:white;border-radius:5px;">
          
            <img src="<?php echo base_url().'assets/insani/banner/Sunday_Clinic.gif'?>" style="width: 100%; height: 450px">
          
          </div> -->

        </div>
        
      </div>

    </div>

  </div>

  <div class="footer" >
    
      <div style="width:90%;float:left; background: #0066a0b3">
        <marquee behavior="scroll" direction="left" style="color: white;font-size:30px;margin-top:5px;font-weight: bold"><?php echo strtoupper('Sayangi kesehatan anda..! Mohon jaga jarak anda, hindari kerumunan dan selalu gunakan masker selama berada di lingkungan Rumah Sakit untuk menekan penyebaran Virus Covid-19.')?> | <?php echo COMP_MOTTO?> </marquee>
      </div>
      <div style="width:10%;float:left; background: #0066a0b3">
        <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
        <p style="margin:0;font-size:20px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
      </div>

  </div>
   
 <script type='text/javascript'>
  // Script to open and close sidebar
  function w3_open() {
      document.getElementById("mySidebar").style.display = "block";
      document.getElementById("myOverlay").style.display = "block";
  }
    
  function w3_close() {
      document.getElementById("mySidebar").style.display = "none";
      document.getElementById("myOverlay").style.display = "none";
  }
  
  // Modal Image Gallery
  function onClick(element) {
    document.getElementById("img01").src = element.src;
    document.getElementById("modal01").style.display = "block";
    var captionText = document.getElementById("caption");
    captionText.innerHTML = element.alt;
  }

  setInterval("my_function();",3000); 

  function my_function(){
    $('#refresh').load(location.href + ' #time');
  }
  
 </script>
 
 
 </body>
</html>





