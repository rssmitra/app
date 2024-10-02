<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Billing</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  
  <style>
  
    .body_print{
      /* margin: 0px 0px 0px 10px; */
      padding: 0;
    }
  
    .body_print, table, p{
    /* font-family: calibri; */
    font-size: 12px;
    background-color: white;
    }
    .table-utama{
    border: 1px solid black;
    border-collapse: collapse;
    }
    th, td {
    padding: 0px;
    text-align: left;
    }

    .top-row-bottom-line, th {
      border-bottom : 1px solid black;
    }
    @media print{ #barPrint{
        display:none;
      }
    }
  
    .stamp {
        margin-top: -96px;
        margin-left: 600px;
        position: absolute;
        display: inline-block;
        color: black;
        padding: 1px;
        padding-left: 10px;
        padding-right: 10px;
        background-color: white;
        box-shadow:inset 0px 0px 0px 0px;
        /*opacity: 0.5;*/
        -webkit-transform: rotate(25deg);
        -moz-transform: rotate(25deg);
        -ms-transform: rotate(25deg);
        -o-transform: rotate(25deg);
        transform: rotate(0deg);
        
    }
    
  </style>
</head>
<body>

<div class="body_print">
  
  <table border="0">
    <!-- Nama RS dan Alamat -->
    <tr>
      <td valign="bottom" ><b><span style="font-size: 15px"><?php echo COMP_LONG; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
    </tr>
  </table>
  <hr>
  
  <div class="row">
    <!-- Detail Pasien -->
    <div class="col-xs-12">
      <?php echo $header; ?>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    
    <div class="col-xs-12">
      <?php echo $content; ?>
    </div><!-- /.col -->

    <!-- footer -->
    <div class="col-xs-12" width="100%" style="padding-left: 0%;">
      <table width="90%">
        <tr>
          <td style="text-align: right">
            Jakarta, <?php echo $this->tanggal->formatDatedmY($data->reg_data->tgl_jam_masuk)?>
            <br><br><br>
            <?php if( $flag_bill == 'temporary' ) : ?>
            <div class="col-xs-4">
            <span style="margin-left:-31%; margin-top: -13%; font-size: 24px" class="stamp center">BILLING<br>SEMENTARA</span>
            </div>
            <?php endif;?>
            ( <?php echo $this->session->userdata('user')->fullname?> )
            <br>
            <center><p style="font-size: 11px;">Terima Kasih atas kepercayaan anda kepada <?php echo COMP_LONG; ?>, semoga lekas sembuh.</p></center>
          </td>
        </tr>
      </table>
    </div>
      
    <div id="options">
      <button
        id="printpagebutton"
        style="
          font-family: arial;
          background: blue;
          color: white;
          cursor: pointer;
          padding: 20px;
          position:absolute;
          right: 10px;
          cursor: pointer;
        "
        onclick="printpage();"

        >
        PRINT OUT
      </button>
    </div>

  </div><!-- /.row -->
</div>
<script>
  function printpage(){
    //Get the print button and put it into a variable
    var printButton = document.getElementById("printpagebutton");
    //Set the print button visibility to 'hidden' 
    printButton.style.visibility = 'hidden';
    //Print the page content
    window.print()
    printButton.style.visibility = 'visible';
  }
</script>



  
</body>
</html>