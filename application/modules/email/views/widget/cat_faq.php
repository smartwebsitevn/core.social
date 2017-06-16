
<div class="t-box">
	<div class="box-title">
		<h6><?php echo $widget->name; ?></h6>
	</div>
	
	<div class="box-content">
	
		<?php foreach ($cats as $cat): ?>
		
			<strong><?php echo $cat->name; ?></strong>
			
			<?php if (count($cat->faqs)): ?>
				<ol class="list faq-list">
					<?php foreach ($cat->faqs as $faq): ?>
						<li><strong class="step_text"
						><?php echo $faq->question; ?>?</strong></li>
						
						<div class="payment">
							<?php echo $faq->answer; ?>
							<div class="clear"></div>
						</div>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
			
		<?php endforeach; ?>
	
		<div class="clear"></div>
	</div>
</div>