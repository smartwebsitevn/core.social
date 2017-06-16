
<?php echo macro()->page(); ?>

<!-- Main content wrapper -->
<div class="wrapper">

	<form class="form" id="form" action="<?php echo $action; ?>" method="post">
		<fieldset>
			<div class="widget">
				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/add.png" class="titleIcon" />
					<h6><?php echo lang('add'); ?> <?php echo lang('mod_cat_news'); ?></h6>
				</div>
				
				<?php foreach (array('name') as $p): ?>
					<?php 
						$_l = array();
						$_l = (isset($_l[$p])) ? $_l[$p] : $p;
					?>
				
					<div class="formRow">
						<label class="formLeft" for="param_<?php echo $p ?>">
							<?php echo lang($_l); ?>:
							<?php if (in_array($p, array('name'))): ?>
								<span class="req">*</span>
							<?php endif; ?>
						</label>
						
						<div class="formRight">
							<span class="threeOne"><input name="<?php echo $p ?>" id="param_<?php echo $p ?>" type="text" /></span>
							<span name="<?php echo $p ?>_autocheck" class="autocheck"></span>
							<div name="<?php echo $p ?>_error" class="clear error"></div>
						</div>
						<div class="clear"></div>
					</div>
				<?php endforeach; ?>
				
				<div class="formRow">
					<label class="formLeft"><?php echo lang('status'); ?>:</label>
					<div class="formRight">
						<label>
							<input type="radio" name="status" value="0" />
							<?php echo lang('off');?>
						</label>
						
						<label>
							<input type="radio" name="status" value="1" <?php echo form_set_checkbox((int)1, 1); ?> />
							<?php echo lang('on');?>
						</label>
					</div>
					<div class="clear"></div>
				</div>
				
           		<div class="formSubmit">
           			<input type="submit" value="<?php echo lang('button_add'); ?>" class="redB" />
           			<input type="reset" value="<?php echo lang('button_reset'); ?>" class="basic" />
           		</div>
        		<div class="clear"></div>
        		
			</div>
		</fieldset>
	</form>
	
</div>
