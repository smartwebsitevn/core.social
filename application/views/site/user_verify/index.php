
<?php 
	$user_verify->paypal_emails = implode('<br>', $user_verify->paypal_emails);
?>

<div class="t-box">
	<div class="box-title">
		<h1><?php echo lang('title_verify'); ?></h1>
	</div>
	
	<div class="box-content">
		
		<table cellpadding="0" cellspacing="0" width="100%" class="tDefault myTable">
		<tbody>
		
			<?php foreach (array(
					'name', 'phone', 'address', 
					'card_no', 'card_place', 'card_date',/*  'paypal_emails', */
				) as $p): ?>
				
				<tr>
					<td><?php echo lang($p); ?></td>
					<td class="fontB"><?php echo $user_verify->$p; ?></td>
				</tr>
			
			<?php endforeach; ?>
			
		</tbody>
		</table>
	
		<div class="clear"></div>
	</div>
</div>
