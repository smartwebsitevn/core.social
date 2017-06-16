
<?php $id = random_string('unique'); ?>

<div class="form-group <?php echo 'param_'.$opt['type']; ?>">
	<label class="col-sm-3  control-label " for="<?php echo $id; ?>">
		<?php echo $opt['name']; ?>:
	</label>

	<div class="col-sm-9">
		<?php if ($opt['type'] == 'text'): ?>
			<input name="<?php echo $name; ?>" value="<?php echo $value; ?>" id="<?php echo $id; ?>" class="form-control" type="text" />
		
		
		<?php elseif ($opt['type'] == 'textarea'): ?>
			<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="autosize form-control" rows="2" cols=""
			><?php echo $value; ?></textarea>
		
		
		<?php elseif ($opt['type'] == 'html'): ?>
			<textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="editor form-control"
				_config='{
					"height": 200
				}'
			><?php echo $value; ?></textarea>
		
		
		<?php elseif ($opt['type'] == 'bool'): ?>
			<label><input type="radio" name="<?php echo $name; ?>" value="0" <?php echo form_set_checkbox((int)$value, 0); ?>
			/><?php echo lang('off');?></label>
			
			<label><input type="radio" name="<?php echo $name; ?>" value="1" <?php echo form_set_checkbox((int)$value, 1); ?>
			/><?php echo lang('on');?></label>
		
		
		<?php elseif ($opt['type'] == 'select' || $opt['type'] == 'select_multi'): ?>
			<?php if ($opt['type'] == 'select'): ?>
				<select name="<?php echo $name; ?>" class="form-control">
			<?php else: ?>
				<select name="<?php echo $name; ?>[]" multiple="multiple" class="form-control select2" style="width:100%;">
			<?php endif; ?>
				<?php foreach ($opt['values'] as $v => $n): ?>
					<option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?>
					><?php echo $n; ?></option>
				<?php endforeach; ?>
			</select>
		
		
		<?php elseif ($opt['type'] == 'radio'): ?>
			<?php foreach ($opt['values'] as $v => $n): ?>

				<label class="tcb-inline">
					<input class="tc" type="radio" name="<?php echo $name; ?>" value="<?php echo $v; ?>" <?php echo form_set_checkbox($v, $value); ?>
				/>
					<span class="labels"><?php echo  $n   ?></span>
				</label>
				
				<?php if (count($opt['values']) > 2): ?>
					<div class="clear"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		
		
		<?php elseif ($opt['type'] == 'checkbox'): ?>
			<?php foreach ($opt['values'] as $v => $n): ?>
				<label class="tcb-inline"><input class="tc" type="checkbox" name="<?php echo $name; ?>[]" value="<?php echo $v; ?>" <?php echo form_set_checkbox($v, $value); ?>
				/>
					<span class="labels"><?php echo  $n   ?></span>

				</label>
				
				<?php if (count($opt['values']) > 2): ?>
					<div class="clear"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		
		
		<?php elseif ($opt['type'] == 'date'): ?>
			<input name="<?php echo $name; ?>" value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>" id="<?php echo $id; ?>" class="datepicker form-control" style="width:100px;" type="text" />
		
		
		<?php elseif ($opt['type'] == 'color'): ?>
			<div class="color_picker">
				<div></div><span>Choose color...</span>
				<input name="<?php echo $name; ?>" value="<?php echo $value; ?>" type="hidden" />
			</div>
		
		<?php elseif (in_array($opt['type'], array('file', 'image', 'file_multi', 'image_multi'))): ?>
			<?php $this->widget->admin->upload($opt['_upload']); ?>
			
			
		<?php endif; ?>
		
		<div name="<?php echo $name; ?>_error" class="clear error"></div>
		
		<?php if ( ! empty($opt['desc'])): ?>
			<div class="formNote"><?php echo $opt['desc']; ?></div>
		<?php endif; ?>

		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>