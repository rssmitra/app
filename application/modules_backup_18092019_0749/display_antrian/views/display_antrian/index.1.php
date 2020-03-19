<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />

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

    <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/datatables/js/jquery.dataTables.min.js')?>"></script>

    <script>
      $(document).ready( function(){
        $.getJSON("<?php echo site_url('display_antrian/process') ?>", '', function (data) {              
            //console.log(data)
            $('#auto1 h1').remove();
            $('#auto2 h1').remove();
            $('#auto3 h1').remove();
            $('#auto4 h1').remove();
            $.each(data, function (i, o) {    
               
              type = (o.ant_type=='bpjs')?'A':'B';
              $('<h1 style="font-size:800%; text-align: center; margin:0; line-height: 0.7;letter-spacing: 8px">'+type+o.ant_no+'</h1>').appendTo($('#auto'+i+''));                    

            });
           
        });

        refresh();  

      });
      
      function refresh()
      {
        setTimeout( function() {
          
          $.getJSON("<?php echo site_url('display_antrian/process') ?>", '', function (data) {              
            //console.log(data)
            $('#auto1 h1').remove();
            $('#auto2 h1').remove();
            $('#auto3 h1').remove();
            $('#auto4 h1').remove();
            $.each(data, function (i, o) {    
              type = (o.ant_type=='bpjs')?'A':'B';
              $('<h1 style="font-size:800%; text-align: center; margin:0; line-height: 0.7;letter-spacing: 8px">'+type+o.ant_no+'</h1>').appendTo($('#auto'+i+''));                    

            });
           
          });

          refresh();
        }, 2000);
      }
      
    </script>

</head>

<body class="w3-light-grey w3-content" style="max-width:1600px" onload="onload();">
 

  
 <!-- Overlay effect when opening sidebar on small screens -->
 <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
 
 <!-- !PAGE CONTENT! -->
 <div class="w3-main">
 
 
 
   <!-- Push down content on small screens --> 
   <div class="w3-hide-large" style=""></div>
   
   <!-- Photo grid -->
   <div class="w3-row" style="margin-top:0; width: 33%; float: left;z-index:-1;">
      <div class="refrh" style="border: 5px solid black; border-right:none; border-bottom:none; width: 100%; height:240px;">
        <center><h1 style='font-size:600%; letter-spacing: 8px; color: black;font-weight: 500;'>LOKET 1</h1></center>
        <div id="auto1" style="color: black;">
          
        </div>
      
      </div>
     
      <div style="border: 5px solid black; border-right:none; width: 100%; height:245px;">
        <center><h1 style='font-size:600%; letter-spacing: 8px; color: black;font-weight: 500;'>LOKET 2</h1></center>
        <div id="auto2" style="color: black;">
        
        </div>
      </div>
   
      <div>
        <img alt="" src="assets/images/logo.png" width="240" style="margin-left:100px;margin-top:30px;">
      </div>
   
   </div>  
   
   
   <div class="w3-col" style="width: 67%;height:480px;float: left;">

    <div style="border: 5px solid black; height:100%;">
      <video id="idle_video" width="100%" onended="onVideoEnded();" ></video> 
    </div>
     
   
      <div class="w3-half" style="display:inline-block;border: 5px solid black; width: 50%;float:left; height:250px">
          <center><h1 style='font-size:600%; letter-spacing: 8px; color: black;font-weight: 500;'>LOKET 3</h1></center>
          <div id="auto3" style="color: black;">
     
          </div>
      </div>

      <div class= "w3-half" style="display:inline-block;border: 5px solid black;border-left:none; width: 50%;height:250px">
          <center><h1 style='font-size:600%; letter-spacing: 8px; color: black;font-weight: 500;'>LOKET 4</h1></center>
          <div id="auto4" style="color: black;">
     
          </div>
      </div>
   
     
   
   </div>  
 
   <marquee behavior="scroll" direction="left" style="border-top: 5px solid black;color: white;margin-top:2.5%;height: 35px;background-color: #333;font-size:20px;font-weight:bold">HTML scrolling text... </marquee>
   
   
 
 
  
 <!-- End page content -->
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
 
     var video_list      = ["video1.mp4", "video2.mp4"];
         var video_index     = 0;
         var video_player    = null;
 
         function onload(){
             console.log("body loaded");
             video_player= document.getElementById("idle_video");
             video_player.setAttribute("src", video_list[video_index]);
             video_player.play();
         }
 
         function onVideoEnded(){
             console.log("video ended");
             if(video_index < video_list.length - 1){
                 video_index++;
             }
             else{
                 video_index = 0;
             }
             video_player.setAttribute("src", video_list[video_index]);
             video_player.play();
         }
  
 </script>
 
 
 </body>
</html>





