<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <?php
  $tgl = date("d");
  $bln = date("m");
  $thn = date("Y");
  $tglsekarang = date("d-m-Y");

  ?>
  <script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
  <script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
  <script>
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }
  </script>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/print.css" class="ace-main-stylesheet" id="main-ace-style" />

  <style>

  </style>
</head>
<body>
	<div class="row" style="margin-top: 37mm;"> 
		<div class="col-xs-8">
			<table style="font-size:12px" border="0">
				<tr> 
					<td width="20%" style="font-size:12px">Telah Terima dari </td> 
					<td width="67%"><font size="2">: <?php echo $name; ?>&nbsp;</font></td>
				</tr>
				<tr>
					<td width="20%" style="font-size:12px">Uang Sejumlah</td> 
					<td width="67%" bgcolor="#EBEBEB" nowrap style="font-size:12px">: <b>Rp <?php echo number_format(intval($total))?>,-</b></td> 
				</tr>
				<tr>
					<td width="20%" valign="top" style="font-size:12px">Terbilang </td> 
					<td width="67%" bgcolor="#EBEBEB" nowrap style="font-size:12px">: 
					<b>
						<i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(intval($total)))?> Rupiah"</i></b>
					</td>
				</tr> 
				<tr>
					<td width="20%">&nbsp;</td> 
					<td width="67%" bgcolor="#EBEBEB">&nbsp;</td> 
				</tr>
				<tr> 
					<td width="20%" style="font-size:12px">Untuk Pembayaran</td> 
					<!-- Update Pengembalian Uang Muka 131011-->
					<td width="67%" bgcolor="#EBEBEB">: Invoice No. <?php echo $inv; ?></td> 
				</tr>
			</table>
			<br>
			<table style="margin-top: 10px;" width="95%" border="0" cellspacing="0" cellpadding="2" align="center"> 
				<tr> 			
					<td valign="top" colspan="3" style="font-weight: bolder; font-size:15px;">
          Pembayaran mohon ditransfer melalui <br>
          BANK MANDIRI <br>
          NO REK : 1270097000275 <br>
          a.n. PT. SETIA MITRA LESTARI  
          &nbsp;</td> 
					<?
						//$nm_perusahaan=baca_tabel("dd_konfigurasi","nama_perusahaan");

					?>
					<td valign="top" width="30%" align="center" style="font-size:12px">
						Jakarta, <?php $date_kui = $_GET['tgl']; echo $this->tanggal->formatDatedmY($date_kui); ?><br><?php echo COMP_FULL;?>
						<br/>Kabag Keuangan
            <br >
            <?php //echo $this->master->get_ttd('ttd_kabag_keu');?>
            <span style="margin-top: 30mm; display: inline-block;">Cumanto, SE.</span>
            <br/>
            <br/>
          </td>
				</tr>
				<tr>
					<td colspan="4" style="text-align: center;">
						Kuitansi ini menjadi SAH bila telah diberi cap & tanda tangan petugas
					</td>
				</tr>
				<!-- <tr>
					<td colspan="4">
						<br/><br/><br/><br/><br/>
					</td>
				</tr> -->
			</table>
      <div id="options">
        <button id="printpagebutton" style="font-family: arial; background: blue; color: white; cursor: pointer; padding: 20px; position:absolute; right: 15px;" onclick="printpage()" style="cursor: pointer">Print Kuitansi</button>
      </div>
		</div>
	</div>
</body>
</html>