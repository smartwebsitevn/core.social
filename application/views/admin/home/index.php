
<?php echo macro()->page(array('toolbar' => [])); ?>

<div class="row">

	<div class="col-md-6">

		<?php //if ($_SERVER['HTTP_HOST'] != 'localhost') widget('product_provider')->balances(); ?>
		<?php //widget('lesson')->stats(); ?>

		<?php widget('stats')->request(); ?>
		<?php widget('user')->newest(); ?>


	</div>

	<div class="col-md-6">


		<?php widget('stats')->stats(); ?>

		<?php widget('invoice_stats')->services(); ?>

		<?php widget('invoice_stats')->times(); ?>


	</div>
</div>

<div class="row">
	<div class="col-md-12">

		<?php widget('invoice_order')->newest(); ?>

	</div>
</div>
