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
 
  color:#A7A1AE;
  background-color:#1F2739;
  }

  h1 {
    font-size:3em; 
    font-weight: 300;
    line-height:1em;
    text-align: center;
    color: #4DC3FA;
  }

  h2 {
    font-size:1em; 
    font-weight: 300;
    text-align: center;
    display: block;
    line-height:1em;
    padding-bottom: 2em;
    color: #FB667A;
  }

  h2 a {
    font-weight: 700;
    text-transform: uppercase;
    color: #FB667A;
    text-decoration: none;
  }

  .blue { color: #185875; }
  .yellow { color: #FFF842; }

  .container th h1 {
    font-weight: bold;
    font-size: 1em;
    text-align: left;
    color: #185875;
  }

  .container td {
      font-weight: bold;
      font-size: 1em;
    -webkit-box-shadow: 0 2px 2px -2px #0E1119;
      -moz-box-shadow: 0 2px 2px -2px #0E1119;
            box-shadow: 0 2px 2px -2px #0E1119;
  }

  .container {
      text-align: left;
      overflow: hidden;
      width: 100%;
      margin: 0 auto;
      display: table;
      padding: 0 0 8em 0;
     overflow: auto; 
  }

  .container td, .container th {
      padding-bottom: 2%;
      padding-top: 2%;
    padding-left:2%;  
  }

  /* Background-color of the odd rows */
  .container tr:nth-child(odd) {
      background-color: #323C50;
  }

  /* Background-color of the even rows */
  .container tr:nth-child(even) {
      background-color: #2C3446;
  }

  .container th {
      background-color: #1F2739;
  }

  .container td:first-child { color: #FB667A; }

  .container tr:hover {
    background-color: #464A52;
  -webkit-box-shadow: 0 6px 6px -6px #0E1119;
      -moz-box-shadow: 0 6px 6px -6px #0E1119;
            box-shadow: 0 6px 6px -6px #0E1119;
  }

  .container td:hover {
    background-color: #FFF842;
    color: #403E10;
    font-weight: bold;
    
    box-shadow: #7F7C21 -1px 1px, #7F7C21 -2px 2px, #7F7C21 -3px 3px, #7F7C21 -4px 4px, #7F7C21 -5px 5px, #7F7C21 -6px 6px;
    transform: translate3d(6px, -6px, 0);
    
    transition-delay: 0s;
      transition-duration: 0.4s;
      transition-property: all;
    transition-timing-function: line;
  }

  @media (max-width: 800px) {
  .container td:nth-child(4),
  .container th:nth-child(4) { display: none; }
  }

  @import url('https://fonts.googleapis.com/css?family=Barrio|Montserrat:700');

  button {
    margin: auto;
    text-transform: uppercase;
    color: #fafafa;
    border: none;
    border-radius: 3px;
   
    padding: 2px 10px;;
  }

  .sticky {
  position: fixed;
  top: 0;
  width: 100%
}


</style>
<body style="background-color:white">
  
      <center>
        <div class="navbar-default">
        <h3>JADWAL PRAKTEK DOKTER</h3>
        <p style="font-size:14px; margin-top:-5px">
          Rumah Sakit Setia Mitra <br>
          <?php echo date('D, d-M-Y')?>
        </p>
        </div>
      </center>
      <table id="dynamic-table" base-url="main/Main"  class="container" width="100%">
      <thead style="font-size:18px">
        <tr>  
          <th width="50px">NO</th>
          <th>POLI/KLINIK RAWAT JALAN</th>
          <th>DOKTER PRAKTEK</th>
          <th>JAM PRAKTEK</th>
          <th>KUOTA</th>
          <th>SISA KUOTA</th>
          <th>STATUS LOKET</th>
          <th>KETERANGAN</th>
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
                
        "ajax": 'main/get_data'
    } );

    

    setInterval( function () {
        
        table.ajax.reload( null, false ); // user paging is not reset on reload
        
        $('#dynamic-table > tbody  > tr').each(function() {
          $('html, body').animate({
                scrollTop: $(this).offset().top
            }, 800).delay(900);        
        });

    }, 1800 );
    
    //table.parent().scrollTop(9999);

  } ); 

  window.onscroll = function() {myFunction()};

  // Get the header
  //var header = document.getElementById("dynamic-table").tHead;

  // Get the offset position of the navbar
  var sticky = header.offsetTop;

  // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
  function myFunction() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }

 
  </script>
   
</body>
</html>





