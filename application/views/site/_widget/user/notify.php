<?php if(!empty($ms)):?>
<div class="container">
    <div class="nNote nWarning" style="margin-top:10px">
    	<?php if (count($ms) == 1): ?>
    		<p><?php echo current($ms); ?></p>
    	<?php elseif (count($ms)): ?>
    		<ul>
    			<?php foreach ($ms as $m): ?>
    				<li><?php echo $m; ?></li>
    			<?php endforeach; ?>
    		</ul>
    	<?php endif; ?>
    </div>
</div>
<?php endif;?>