<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css"> -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/print.css" type="text/css" media="print">
  <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"> -->
  <style type="text/css">
      body{
        /* width: 500px; */
        margin:0px 0px 0px 0px;
        /*font: 12px/normal tahoma;*/
      }
      .navbar{
        width:100%;
        background-color: lightgrey;
        border-top: 1px solid #C1CFF2;
        border-bottom: 1px solid #33618F;
        padding: 0px;
        color: white;
        font: bold 11px/normal verdana;
        height: 30px;
        /* text-align:right; */
        
      }

      .btn{
        display: inline-block;
        color: #FFF !important;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-image: none !important;
        background-color: #428bca;
        border: 5px solid #FFF;
        border-radius: 0;
        box-shadow: none !important;
        -webkit-transition: background-color 0.15s, border-color 0.15s, opacity 0.15s;
        -o-transition: background-color 0.15s, border-color 0.15s, opacity 0.15s;
        transition: background-color 0.15s, border-color 0.15s, opacity 0.15s;
        cursor: pointer;
        vertical-align: middle;
        margin: 0;
        position: relative;
        
        padding: 0 4px;
        line-height: 18px;
        border-width: 2px;
        font-size: 26px;
      }

      .btn-danger, .btn-danger:focus {
          background-color: #d15b47 !important;
          border-color: #d15b47;
      }

      .btn-primary, .btn-primary:focus {
          background-color: #1b6aaa !important;
          border-color: #428bca;
      }
      
      .header{
        width: 100%;
        height:auto;
        line-height: 1.6;
      }
      .kanan{
        /* font-size:14px; */
        /* font-weight:bold; */
        width: 50%;
        float:right;
      }
      .kiri{
        /* font-size:14px; */
        /* font-weight:bold; */
        width: 50%;
        float:left;
        margin-bottom:10px;
      }
      .tindakan{
        clear:both;
        /* font-size:18px; */
        /*font-weight:bold;*/
      }
      .center{
        text-align:center;
      }
      .data{
        /* margin-left:5px; */
      }
      footer{
        /* width: 50%;
        float:right; */
        width: 100%;
        height:auto;
        /*font: 12px/normal tahoma,verdana;*/
      }
      #table_tindakan{
        border-collapse:collapse;
        width:100%;
        line-height: 1.6;
      }
      #table_tindakan thead th { 
        border-top: 1px solid #000; 
        border-bottom: 1px solid #000; 
      }

      @media print {
        .navbar {
          display: none;
        }
      }

  </style>

</head>
<body>

   <!-- <div class="navbar"> -->

   <div class="navbar">
			<button class="btn btn-danger" onClick="window.close()"><i class="fa fa-times-circle"></i></button>
			<button class="btn btn-primary" onClick="window.print()"><i class="fa fa-print"></i></button>
		</div>

	<!-- </div> -->

  <div class="center">
      <h3>NOTA <?php echo strtoupper($value[0]->bagian) ?></h3><br>
  </div>

  <div class="header">
    <div class="kiri">
      
        <table border="0" width="100%">
          <tr>      
            <td width="50%" valign="top">Nama Pasien  <span style="float:right">:</span></td>
            <td width="50%"><?php echo $nama ?></td>
          </tr>
          <tr>
            <td width="50%" valign="top">No Registrasi  <span style="float:right">:</span></td>
            <td width="50%"> <?php echo $no_registrasi ?></td>
          </tr>
          <tr>
            <td width="50%" valign="top">Tgl Periksa  <span style="float:right">:</span></td>
            <td width="50%"> <?php echo $this->tanggal->formatDate($value[0]->tgl_transaksi) ?></td>
          </tr>
          <tr>
            <td width="50%" valign="top">No. MR  <span style="float:right">:</span></td>
            <td width="50%"> <?php echo $no_mr ?></td>
          </tr>
        </table>
      
    </div>

    <div class="kanan">
      
      <table border="0" width="100%">
        <tr>
          <td width="40%" valign="top">Dokter <span style="float:right">:</span></td>
          <td width="59%"><?php echo $dokter_1 ?></td>
        </tr>
        <?php if(isset($dokter_2)): ?>
          <tr>
            <td width="40%">Dokter Anastesi <span style="float:right">:</span></td>
            <td width="60%"><?php echo $dokter_2 ?></td>
          </tr>
        <?php endif ?>
        <tr>
          <td width="40%">Asal <span style="float:right">:</span></td>
          <td width="59%"><?php echo $bagian_asal ?></td>
        </tr>
      </table>

    </div>

  </div>

  <div class="tindakan">

    <table id="table_tindakan">
      <thead style="border-bottom:1px bold black">
        <th width="5%" valign="top">No.</th>
        <th width="65%">Nama Pemeriksaan</th>
        <th width="30%">Biaya (Rp.)</th>
      </thead>
      
      <tbody style="margin:10px;">
        <?php $no=0;$tot_biaya=0; foreach ($value as $val) :
          $no++;
          $bill_rs = $val->bill_rs;
          $bill_dr1 = $val->bill_dr1;
          $bill_dr2 = $val->bill_dr2;
          $alat_rs=$val->alat_rs;
          $biaya=$bill_rs+$bill_dr1+$bill_dr2;
          $tot_biaya=$tot_biaya+$biaya;?>
          <tr>
            <td class="center"><?php echo $no ?>.</td>
            <td><?php echo $val->nama_tindakan ?></td>
            <td class="center"><?php echo number_format($biaya) ?></td>
          </tr>
          
        <?php endforeach ?>

        <tr style=" border-top: 1px solid #000">
          <td colspan="2" style="text-align:right"><b>Total</b></td>
          <td class="center"><?php echo number_format($tot_biaya) ?></td>
        </tr>

      </tbody>
      
    </table>

  </div>
  
</body>
<footer>
  <div class="kanan" style="text-align:center">
   
    <p> Jakarta, <?php echo $this->tanggal->formatDate(date('Y-m-d')) ?> </p>
    <p> Petugas, </p>
    <br><br><br>
    <p>(------------------------)</p>
   
  </div>
</footer>
</html>








