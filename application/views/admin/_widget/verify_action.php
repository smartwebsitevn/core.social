<div style="display:none;">
	<div class="widget " id="verify_action">
		<div class="title bg-primary">
			<h6><i class="fa fa-exclamation-circle "></i> <?php echo $this->lang->line('notice'); ?></h6>
		</div>
		
		<div class="body">
			<div id="notice"></div>
			<div class="textC" style="margin-top:10px;">
				<button id="accept" href="" class="btn btn-danger" style="margin: 5px;"><i class="fa fa-thumbs-o-up"></i> <?php echo $this->lang->line('button_accept'); ?></span></button>
				<button id="cancel" href="" class="btn" style="margin: 5px;"><i class="fa fa-times "></i> <?php echo $this->lang->line('button_cancel'); ?></span></button>
			</div>
		</div>
		
		<div id="verify_action_load" class="form_load"></div>
	</div>
</div>