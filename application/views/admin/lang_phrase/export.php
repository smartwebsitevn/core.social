<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=langs.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
		<thead>
			<tr>
				<td>stt</td>
				<td>id</td>
				<td>file</td>
				<td>key</td>
				<?php 
				foreach ($langs as $l)
				{
				    echo '<td>'.$l->name.'</td>';
				}
				?>
				
			</tr>
		</thead>
		
		<tbody>
		<?php $i = 1;?>
		<?php foreach ($list as $row): ?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $row->id; ?></td>
				<td><?php echo $row->_file->file; ?></td>
				<td><?php echo $row->key; ?></td>
				<?php 
				foreach ($langs as $l)
				{
				    echo '<td>'.$l->directory.'</td>';
				}
				?>
				<?php $i++?>
			</tr>
		<?php endforeach; ?>
		</tbody>
</table>
		