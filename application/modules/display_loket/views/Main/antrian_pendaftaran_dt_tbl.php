<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- css default for blank page -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>

    <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/datatables/js/jquery.dataTables.min.js')?>"></script>
    <title>Display - Loket</title>
    <link rel="shortcut icon" href="<?php echo PATH_IMG_DEFAULT.$app->app_logo?>">
</head>
<style type="text/css">
/*  table th:nth-child(3), td:nth-child(3) {
    display: none;
  }
  .fixed {
    position: fixed;
    z-index: 1030;
    width: 100%;
  } */

  html, body{
    overflow:hidden;
  }

  body {
 
  color: black;
  background-color:white;
  }

  .container td, .container th {
      /*padding-bottom: 2%;*/
      padding-top: 1%;
    /*padding:2%;  */
  }


</style>
<body style="background-color:black">
  
      <!-- <center>
        <div class="navbar-default">
        <h3>JADWAL PRAKTEK DOKTER</h3>
        <p style="font-size:14px; margin-top:-5px">
          <?php echo COMP_LONG; ?> <br>
          <?php echo date('D, d-M-Y')?>
        </p>
        </div>
      </center> -->
      <table id="dynamic-table" base-url="main/Main"  class="container" width="100%">
      <thead style="font-size:18px">
        <tr>  
          <th style="color: white; font-weight: bold; vertical-align: center">JADWAL PRAKTEK DOKTER HARI INI</th>
        </tr>
      </thead>
      <tbody style="font-size:18px;font-family: arial">
      </tbody>
    </table>

  <!--<script src="<?php //echo base_url().'assets/js/custom/als_datatable_no_style.js'?>"></script>-->

  <script>
  $(document).ready(function() {

    var table = $('#dynamic-table').DataTable( {
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering" :false,
        "paging" : false,
        "info" : false,
        "bProcessing": false,
        "animate": true,
        fixedHeader: {
            header: true,
        },
                
        "ajax": '<?php echo base_url()?>display_loket/Main/get_data_tbl'
    } );

    

    // setInterval( function () {
        
    //     table.ajax.reload( null, false ); // user paging is not reset on reload
        
    //     $('#dynamic-table > tbody  > tr').each(function() {
    //       $('html, body').animate({
    //             scrollTop: $(this).offset().top
    //         }, 800).delay(1500);        
    //     });

    // }, 1800 );
    
    //table.parent().scrollTop(9999);

  } ); 

  // window.onscroll = function() {myFunction()};

  // // Get the header
  // //var header = document.getElementById("dynamic-table").tHead;

  // // Get the offset position of the navbar
  // var sticky = header.offsetTop;

  // // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
  // function myFunction() {
  //   if (window.pageYOffset > sticky) {
  //     header.classList.add("sticky");
  //   } else {
  //     header.classList.remove("sticky");
  //   }
  // }

 
  </script>
   
</body>
</html>





