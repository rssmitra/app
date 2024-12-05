<span>Dibuat oleh : </span><br>
<?php echo $draw->created_by?> [<?php echo $draw->type_owner?>]
Tanggal. <?php echo $this->tanggal->formatDateTimeFormDmy($draw->created_date)?><br>
<?php echo $draw->jenis_catatan_draw?>
<hr>
<img src="<?php echo $draw->notes?>" width="100%">