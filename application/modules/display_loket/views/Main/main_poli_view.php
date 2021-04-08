<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />


    <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/datatables/js/jquery.dataTables.min.js')?>"></script>
    <title>Display - Loket</title>
    <link rel="shortcut icon" href="<?php echo PATH_IMG_DEFAULT.$app->app_logo?>">
</head>
<body style="background-color:black">
  
      <center>
      </center>
      <table id="dynamic-tablexx" base-url="main/Main"  class="table" width="100%">
      <thead style="font-size:18px">
        <tr style="color: white">  
          <th width="50px">NO</th>
          <th>NAMA PASIEN</th>
        </tr>
      </thead>
      <tbody style="font-size:18px;font-family: arial">
        <?php for($i=0;$i>11;$i++) :?>
        <tr style="color: white">  
          <td width="50px" align="center"><?php echo $i; ?></td>
          <td>Muhammad Amin Lubis</td>
        </tr> 
        <?php endfor; ?>
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

    

    // setInterval( function () {
        
    //     table.ajax.reload( null, false ); // user paging is not reset on reload
        
    //     $('#dynamic-table > tbody  > tr').each(function() {
    //       $('html, body').animate({
    //             scrollTop: $(this).offset().top
    //         }, 800).delay(900);        
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





