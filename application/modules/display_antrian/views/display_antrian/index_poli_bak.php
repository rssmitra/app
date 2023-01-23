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
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        height:55px;
        background-color: red;
        color: white;
        text-align: center;
      }

      .display-video{
        height:740px;
        background-color:black;
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

      .stamp {
        position:absolute;
        top:40%;
        transform: rotate(12deg);
        color: red;
        font-size: 6rem;
        font-weight: 700;
        border: 0.25rem solid red;
        display: inline-block;
        padding: 0.25rem 1rem;
        text-transform: uppercase;
        border-radius: 1rem;
        /*font-family: 'Courier';*/
        -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
        -webkit-mask-size: 944px 604px;
        mix-blend-mode: hard-light;
      }

    .is-nope {
      color: #D23;
      border: 0.5rem double #D23;
      transform: rotate(3deg);
      -webkit-mask-position: 2rem 3rem;
      font-size: 2rem;  
    }

    .is-approved {
      color: #0A9928;
      border: 0.5rem solid #0A9928;
      -webkit-mask-position: 13rem 6rem;
      transform: rotate(-14deg);
      border-radius: 0;
    } 

    .is-draft {
      color: #C4C4C4;
      border: 1rem double #C4C4C4;
      transform: rotate(-5deg);
      font-size: 6rem;
      font-family: "Open sans", Helvetica, Arial, sans-serif;
      border-radius: 0;
      padding: 0.5rem;
    } 

    </style>

</head>

<body style="background-color:#333; padding-top : 5px">
 
  <div class="page-content" >

    <div class="col-xs-12" >

      <div class="col-lg-12 no-padding">

        <div class="row" style="height:100px;background-image: linear-gradient(to right, #ffffff,  #006600);color:white">
          <img alt="" src="<?php echo COMP_ICON?>" width="100px" style="margin:10px 20px;float:left">
          <h1 style="margin:0; font-size: 45px;text-shadow: 3px 2px 5px black;"><?php echo COMP_LONG?></h1>
          <p style="font-family: Helvetica;margin:0; font-size:18px;text-shadow: 2px 2px 3px black;"><b><?php echo COMP_ADDRESS?></b></p>
        </div>
      </div>

      <div class="col-lg-12" style="padding-right:0">
        <div class="row" >
          <div class="small-box" style="height:auto;margin:10px;background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;">
            
            <div class="header" style="height:30px;background-color:grey;border-radius:15px 0 0 50px;">
                <p style="font-size:20px;margin-left:9.5%;margin-right:9.5%"><b>Loket</b></p>    
            </div>
              
            <div class="inner">

              <div style="width:30%;float:left;border-right:2px solid white">
                <h1 style="margin-top:15px;font-size:85px;text-align:center;text-shadow: 5px 3px 6px black;">3</h1>
              </div>

              <div id="auto3" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:120px;"></div>
                      
            </div>
            
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>



  <!-- <div class="footer" >
    
      <div style="width:90%;float:left;">
        <marquee behavior="scroll" direction="left" style="color: white;font-size:30px;margin-top:5px;font-weight: bold"><?php echo strtoupper('Sayangi kesehatan anda..! Mohon jaga jarak anda, hindari kerumunan dan selalu gunakan masker selama berada di lingkungan Rumah Sakit untuk menekan penyebaran Virus Covid-19.')?> | <?php echo COMP_MOTTO?> </marquee>
      </div>
      <div style="width:10%;float:left;margin-top: 5px">
        <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
        <p style="margin:0;font-size:16px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
      </div>

  </div> -->
   

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





