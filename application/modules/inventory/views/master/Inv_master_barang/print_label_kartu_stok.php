<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<style type="text/css">
    table {
        font-family: arial;
        font-size: 12px;
        margin-top:0px;
    }
</style>
<body style="background-color:white; align: center" >

<center>

<?php foreach( $barang as $key=>$rows ) : ?>
  <div align="left" style="width:265px; height:112px;">
    <b><span style="font-size: 16px"><?php echo $rows->kode_brg; ?></span></b> <br>
    <span style="font-size: 14px"><?php echo ucwords(strtolower($rows->nama_brg)).' <br>('.$rows->content.' '.$rows->satuan_kecil.'/'.$rows->satuan_besar.')'?></span><br>
    <?php echo $rows->nama_kategori?> / 
	<?php
if ($rows->rak === null || $rows->rak === '') {
    echo 'Belum ada Rak';
} else {
    echo $rows->rak;
}
?>
  </div>
<?php endforeach; ?>

</center>