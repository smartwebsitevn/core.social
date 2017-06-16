
<?php
$info = isset($info) ? (array)$info : null;
$ct =$info['_contents'];
$content_langs=array();
foreach ($langs as $l)
{
	$info = isset($ct[$l->id]) ? (array)$ct[$l->id] : null;
	$lang = array(
		'key' 	=> strtolower($l->code),'name' 	=> $l->name,
	);
	$lang['rows'][] = array(
		'param' 	=> "title[{$l->id}]",
		'name' 	=> lang('title'),
		'value' 	=> $info['title'],
	);

	$lang['rows'][] = array(
		'param' 	=> "content[{$l->id}]",'type' 	=> 'html',
		'name' 		=> lang('content'),
		'value' 	=> $info['content'],
	);
	$content_langs[] = $lang;
}

?>
<div class="tc-tabs">
	<ul class="nav nav-tabs white">
		<?php $i = 1;
		foreach ($content_langs as $lang)://pr($lang);
			?>
			<li <?php echo $i == 1 ? ' class="active" ' : '' ?>>
				<a href="#lang_<?php echo $lang['key']; ?>" data-toggle="tab">
					<img style="height:11px" class="mt3 mr5 img-responsive img-responsive pull-left "
						 src="<?php echo public_url("img/world/{$lang['key']}.gif"); ?>">
					<span class="mt3"><?php echo $lang['name']; ?></span>
				</a>
			</li>
			<?php $i++; endforeach; ?>
	</ul>
	<div class="tab-content">
		<?php $i = 1;
		foreach ($content_langs as $lang): ?>
			<div class="tab-pane <?php echo $i == 1 ? 'active' : '' ?> "	 id="lang_<?php echo $lang['key']; ?>">
				<?php
				foreach ($lang['rows'] as $row) {
					echo macro('mr::form')->row($row);
				}
				?>
			</div>
			<?php $i++; endforeach; ?>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>