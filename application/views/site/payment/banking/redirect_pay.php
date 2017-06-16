
<style>
.payment_banking .box-content {
    width: 700px;
    min-height: 300px;
}
.payment_banking .pay_banking td:first-child {
   width: 30%;
}
.payment_banking .pay_banking td:last-child {
    font-weight: bold;
}
</style>


<div class="t-box payment_banking" id="main_popup">
	<div class="box-title">
		<h1><?php echo lang('payment_banking_notice'); ?></h1>
	</div>
	
	<div class="box-content">
		
		<p><?php echo lang('notice_transfer'); ?></p>
		
		<table cellpadding="0" cellspacing="0" width="100%" class="tDefault myTable pay_banking">
		<tbody>
		
			<tr>
				<td><?php echo lang('bank'); ?></td>
				<td><?php echo $bank->name; ?></td>
			</tr>
		
			<tr>
				<td><?php echo lang('acc_id'); ?></td>
				<td><?php echo $bank->acc_id; ?></td>
			</tr>
			
			<tr>
				<td><?php echo lang('acc_name'); ?></td>
				<td><?php echo $bank->acc_name; ?></td>
			</tr>
			
			<tr>
				<td><?php echo lang('tran_amount'); ?></td>
				<td><?php echo $_total; ?></td>
			</tr>
			
			<tr>
				<td><?php echo lang('tran_content'); ?></td>
				<td><?php echo $content; ?></td>
			</tr>
			
			<tr>
				<td colspan="2" class="textC">
				    
				    <a href="<?php echo $url_back; ?>" class="button medium black left m0"
				        onclick="lightbox(this); return false;"
				    ><?php echo lang('button_back'); ?></a>
				
				    <a href="<?php echo $url_confirm; ?>" class="button medium blue right m0"
				    ><?php echo lang('button_confirm_transfer'); ?></a>
				    
                </td>
			</tr>
			
		</tbody>
		</table>
		
		<div class="clear"></div>
	</div>
</div>