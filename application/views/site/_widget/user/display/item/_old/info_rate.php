<?php if(isset($row->rate)):?>
<?php
	/*
$i = 1;
while ($i < $row->rate) {
    ?>
    <i></i>
    <?php
    $i++;
}
if ($row->rate - ($i - 1) > 0) {
    ?>
    <i style="width: <?php echo 18 * ($row->rate - ($i - 1)) ?>px"></i>
    <?php
}
	*/
?>

<span class="reviews-row db pt5 pb5 pl10 pr10 fxw">
	<span class="star-rating--static star-rating--smaller mr5">
		<span
			style="width: <?php echo 18 * ($row->rate ) ?>px;">
		</span>
	</span>
	<span translate="" class="fx">
		<span class="reviews__stats">
			<?php echo $row->rate ?>
			<span class="reviews__count">
				(<?php echo number_format($row->rate_total) ?> )
			</span>
		</span>
	</span>
</span>
<?php endif; ?>