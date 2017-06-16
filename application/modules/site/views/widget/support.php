
<?php 
	$suport = array();
	foreach (array('phone', 'email', 'skype', 'yahoo') as $p)
	{
		$suport[$p] = module_get_setting('site', $p);
	}
?>

<div class="cat-pro-list">

	<h3 class="title title-module"><?php echo $widget->name; ?></h3>
	
	<ul class="list_0">
	
		<li class="item"><a>
			<img src="<?php echo public_url('site/theme') ?>/images/phone.png">
			<?php echo $suport['phone']; ?>
		</a></li>
	
		<li class="item"><a>
			<img src="<?php echo public_url('site/theme') ?>/images/mail.png">
			<?php echo $suport['email']; ?>
		</a></li>
	
		<li class="item"><a href="skype:<?php echo $suport['skype']; ?>?call">
			<img src="<?php echo public_url('site/theme') ?>/images/skype.png">
			<?php echo $suport['skype']; ?>
		</a></li>
	
		<li class="item"><a href="ymsgr:sendIM?<?php echo $suport['yahoo']; ?>">
			<img src="<?php echo public_url('site/theme') ?>/images/ym.png">
			<?php echo $suport['yahoo']; ?>
		</a></li>
		
	</ul>
</div>