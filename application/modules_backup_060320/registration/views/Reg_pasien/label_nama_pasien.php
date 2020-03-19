<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<br><br>

<div style="width: 756px; margin: 35px 35x 35px 35px">
  <div style="width: 680px">
    <?php if($count != '') : for($i=0;$i<$count;$i++) : if( $i % 2 == 0 ) : ?>
    <div style="width: 170px;height: 94px;float:left">
       <b><?php echo $pasien->nama_pasien?></b> <br> <?php echo $pasien->no_mr?> <br>
        <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?> (<?php echo $pasien->jen_kelamin?>)
    </div>
    <?php endif; endfor; endif; ?>

    <?php if($count != '') : for($i=0;$i<$count;$i++) : if( $i % 2 != 0 ) : ?>
    <div style="width: 170px;height: 94px;float:left">
       <b><?php echo $pasien->nama_pasien?></b> <br> <?php echo $pasien->no_mr?> <br>
        <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?> (<?php echo $pasien->jen_kelamin?>)
    </div>
    <?php endif; endfor; endif; ?>

  </div>
  
</div>

<div id="options">
  <input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT ~" onclick="printpage()"/>
</div>

<script type="text/javascript">
  
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

<style type="text/css">
  #options {
    align-content:left;
    align-items:center;
    text-align: center;
    cursor: pointer;
  }

  /*@media print {

    @page {
        margin-left: -10px;
        width: 755px;
        height: 257px;
    }
    body { 
        background-color: white; 
        margin: 1in;
    }
    p {
        font-family: sans-serif;
        font-size: 12px;
        color: black;
    }
}*/

</style>

</body>