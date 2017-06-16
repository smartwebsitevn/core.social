
<?php $_id = '_'.random_string('unique'); ?>
<div class="panel panel-default">
   <div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('title_topup_offline'); ?></h3>
	</div>
	
	<div class="panel-body"> 
	     <div class="cd-tabs">
		
		<div id="tab-content-home">
                <ul class="nav nav-tabs">
                      <?php foreach (array('mobile', 'game') as $i => $p): ?>
                      <li class="<?php if ( ! $i) echo 'active'; ?>"><a data-toggle="tab" href="#form_<?php echo $p?>"><?php echo lang('title_topup_'.$p); ?></a></li>
                      <?php endforeach; ?>
                </ul>
                <div class="tab-content">
                    <?php foreach (array('mobile', 'game') as $i => $p): ?>
                    <div class="tab-pane fade in <?php if ( ! $i) echo 'active'; ?>" id="form_<?php echo $p?>">
                    	    <?php t('widget')->topup_offline->{'form_'.$p}(); ?>
                    </div>
                    <?php endforeach; ?>
                 </div>
         </div>
		</div>
				
		<div class="clear"></div>
	</div>
</div>
