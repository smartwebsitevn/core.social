
<div class="bank-boxs">
	
	<div class="row box-content">
		<?php foreach ($list as $row){ ?>
		<div class="col-xs-6 bank-box-content">
			<h3><?php echo $row->name ?></h3>
			<p>Số tài khoản: <b><?php echo $row->acc_id ?></b></p>
			<p>Chủ tài khoản: <b><?php echo $row->acc_name?></b></p>
			<p>Chi nhánh: <b><?php echo $row->branch?></b></p>
		</div>
		<?php } ?>

	</div>
</div>
