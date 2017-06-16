
<?php echo macro()->page(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<form class="form" id="form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/edit.png" class="titleIcon" />
					<h6><?php echo lang('edit'); ?> <?php echo lang('mod_partner'); ?></h6>
				</div>
				
				<?php foreach (array('name', 'phone', 'email', 'address', 'cat', 'web') as $p): ?>
					<?php 
						$_l = array();
						$_l['cat'] = 'partner_cat';
						$_l = (isset($_l[$p])) ? $_l[$p] : $p;
					?>
				
					<div class="formRow">
						<label class="formLeft" for="param_<?php echo $p ?>">
							<?php echo lang($_l); ?>:
							<?php if (in_array($p, array('name', 'phone'))): ?>
								<span class="req">*</span>
							<?php endif; ?>
						</label>
						
						<div class="formRight">
							<span class="threeOne"><input name="<?php echo $p ?>" value="<?php echo $info->{$p} ?>" id="param_<?php echo $p ?>" type="text" /></span>
							<span name="<?php echo $p ?>_autocheck" class="autocheck"></span>
							<div name="<?php echo $p ?>_error" class="clear error"></div>
						</div>
						<div class="clear"></div>
					</div>
				<?php endforeach; ?>
				
				<div class="formRow">
					<label class="formLeft" for="param_desc"><?php echo lang('desc'); ?>:</label>
					<div class="formRight">
						<span class="threeOne"><textarea name="desc" id="param_desc" rows="4" cols=""><?php echo $info->desc ?></textarea></span>
						<span name="desc_autocheck" class="autocheck"></span>
						<div name="desc_error" class="clear error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
           		<div class="formSubmit">
           			<input type="submit" value="<?php echo lang('button_update'); ?>" class="redB" />
           			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
           		</div>
        		<div class="clear"></div>
        		
			</div>
		</fieldset>
	</form>
	
</div>
