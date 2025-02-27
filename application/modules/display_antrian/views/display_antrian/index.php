<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>SHS 4.0 - Antrian Pendaftaran</title>
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
                  $('<h1 style="margin:0;font-size:88px;font-weight:bold;text-align:center;">'+type+' '+no+'</h1>').appendTo($('#auto'+i+''));
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

      @font-face { 
        font-family: 'MyriadPro'; 
        src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf'); 
      } 

      body{
        font-family: 'MyriadPro' !important;
      }

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


      .header-logo{
        height:130px;
        background: #e1e1e145 ;
        color:white;     
        /* border-bottom: 5px solid #ea822d; */
        /* border-bottom-right-radius: 65px;  */
        text-align: left
      }

      .title-text{
        font-size: 30px;
        font-weight: bold;
        text-align: center !important;
        width: 100%;
        margin: 20px;
        color: black !important;
        padding-top: 30px;
      }

      .page-content {
          background-color: #E6E7E8;
          margin: 0;
          /* padding: 0px 20px 24px; */
          background-position: center;
          background-repeat: no-repeat;
          background-size: cover;
          height: 100% !important;
          /* min-height: 1050px; */
          font-family: 'MyriadPro' !important
      }

      .item img{
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
        border-bottom-left-radius: 25px;
      }

      ::-webkit-scrollbar {
          display: none;
      }

    </style>

</head>

<body style="background: url(assets/images/unit-pendaftaran.jpg) fixed; padding-left: 10px">
 
  <div class="page-content" style="padding-left: 10px; background: url(assets/images/unit-pendaftaran.jpg) fixed;" >

    <div class="row">

      <div class="col-lg-12">

        <div class="row header-logo">
          <div style="float: left; margin-left: 20px; margin-top: 10px">
            <img alt="" src="<?php echo COMP_ICON_INSANI?>" width="300px">
          </div>
          <div style="float: right; margin-top: 20px; margin-right: 15px">
            <span class="title-text"><img alt="" src="<?php echo COMP_ICON_BY_INSANI?>" width="150"></span>
          </div>
        </div>

      </div>

    </div>

    <div class="row">

      <div class="col-xs-8">
          <div class="row" style="padding-top: 10px; margin-left: 2px">
            
            <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

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
          
          <!-- <div class="row center" style="background: white; margin-top: 5px; border-radius: 5px;">
            <div style="background: #00669F;color: white;">
              <div style="width:85%;float:left; background: #00669F">
                <marquee behavior="scroll" direction="left" style="color: white;font-size:30px;margin-top:5px;font-weight: bold"><?php echo strtoupper('Sayangi kesehatan anda..! Mohon jaga jarak anda, hindari kerumunan dan selalu gunakan masker selama berada di lingkungan Rumah Sakit untuk menekan penyebaran Virus Covid-19.')?> | <?php echo COMP_MOTTO?> </marquee>
              </div>
              <div style="width:15%;float:left; background: #00669F; text-align: center">
                <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
                <p style="margin:0;font-size:20px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
              </div>
            </div>
          </div> -->
      </div>

      <div class="col-xs-4">

        <div class="row">

          <div class="small-box" style="height:auto;margin:10px;background: #00669F ;color:white;border-radius:25px;">
            
              <div class="header" style="height:30px;background-color: #5882B0;border-top-right-radius: 25px">
                <p style="font-size:20px;margin-left:12%; font-weight: bold">Loket<span style="margin-left: 30%">Nomor Antrian</span></p>
                
              </div>
              
              <div class="inner">

                <div style="width:30%;float:left;border-right:2px solid white">
                  <h1 style="margin-top:0px;font-size:85px;text-align:center;font-weight: bold">1</h1>
                </div>

                <div id="auto1" style="margin-top:0px;margin-bottom:-17px;width:70%float:left;min-height: 125px"></div>
                        
              </div>
            
          </div>

          <div class="small-box" style="height:auto;margin:10px;background: #00669F;color:white;border-radius:25px;">
            
            <div class="header" style="height:30px;background-color: #5882B0;border-top-right-radius: 25px">
              <p style="font-size:20px;margin-left:12%; font-weight: bold">Loket<span style="margin-left: 30%">Nomor Antrian</span></p>
            </div>
            
            <div class="inner">

              <div style="width:30%;float:left;border-right:2px solid white">
                <h1 style="margin-top:0px;font-size:85px;text-align:center;font-weight: bold">2</h1>
              </div>

              <div id="auto2" style="margin-top:0px;margin-bottom:-17px;width:70%float:left;min-height: 125px"></div>
                      
            </div>
            
          </div>

          <div class="small-box" style="height:auto;margin:10px;background : #00669F ;color:white;border-radius:25px;">
              <div class="header" style="height:30px;background-color: #5882B0;border-top-right-radius: 25px">
                  <p style="font-size:20px;margin-left:12%; font-weight: bold">Loket<span style="margin-left: 30%">Nomor Antrian</span></p>
              </div>
              <div class="inner">

                <div style="width:30%;float:left;border-right:2px solid white">
                  <h1 style="margin-top:0px;font-size:85px;text-align:center;font-weight: bold">3</h1>
                </div>

                <div id="auto3" style="margin-top:0px;margin-bottom:-17px;width:70%float:left;min-height: 125px">
                  
                </div>
                        
              </div>
          </div>

          <!-- <div class="small-box" style="height:auto;margin:10px;background: #00669F ;color:white;border-radius:25px;">
          
            <div class="header" style="height:30px;background-color: #5882B0; border-top-right-radius: 25px">
              <p style="font-size:20px;margin-left:12%; font-weight: bold">Loket<span style="margin-left: 30%">Nomor Antrian</span></p>
              
            </div>
          
            <div class="inner">

              <div style="width:30%;float:left;border-right:2px solid white">
                <h1 style="margin-top:0px;font-size:85px;text-align:center;font-weight: bold">4</h1>
              </div>

              <div id="auto4" style="margin-top:0px;margin-bottom:-17px;width:70%float:left;min-height: 125px"></div>
                      
            </div>
          
          </div> -->

        </div>
        
      </div>
    </div>

  </div>

  <div class="footer no-padding" style="margin-top: 10px">
    
      <div style="background: #00669F;color: white;">
        <div style="width:85%;float:left; background: #00669F">
          <marquee behavior="scroll" direction="left" style="color: white;font-size:30px;margin-top:5px;font-weight: bold"><?php echo strtoupper('Sayangi kesehatan anda..! Mohon jaga jarak anda, hindari kerumunan dan selalu gunakan masker selama berada di lingkungan Rumah Sakit untuk menekan penyebaran Virus Covid-19.')?> | <?php echo COMP_MOTTO?> </marquee>
        </div>
        <div style="width:15%;float:left; background: #00669F; text-align: center">
          <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
          <p style="margin:0;font-size:20px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
        </div>
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





