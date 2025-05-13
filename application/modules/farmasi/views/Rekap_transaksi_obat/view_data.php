<?php 

  	$filename = 'rekap_transaksi_obat';
	header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
	header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);

?>

<table class="table table-bordered">
	<thead>
		<tr>
		<th>NO</th>
		<?php 
			foreach($fields as $field){
			echo '<th>'.strtoupper($field->name).'</th>';
		}?>
		</tr>
	</thead>
	<tbody>
		<?php $no = 0; foreach($data as $row_data) : $no++; ?>
		<tr>
			<td align="center"><?php echo $no;?></td>
			<?php 
			foreach($fields as $row_field){
				$field_name = $row_field->name;
				echo '<td>'.strtoupper($row_data->$field_name).'</td>';
			}?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>






