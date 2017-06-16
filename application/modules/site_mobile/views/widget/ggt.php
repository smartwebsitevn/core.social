
<div class="lang1">

	<div class="title-lang"><?php echo $widget->name; ?></div>

	<?php
		$_ = array(
			'vi' => 'Việt nam',
			'en' => 'English',
			'zh-TW' => 'Đài loan',
			'zh-CN' => 'Trung quốc',
			'ja' => 'Nhật bản',
			'ko' => 'Hàn quốc',
		);
		foreach ($_ as $k => $v):
		
			$img = public_url("site/images/translates/{$k}.png");
			//$img = upload_url($widget->setting['img_'.$k]);
	?>

		<a data-lang="<?php echo $k; ?>" title="<?php echo $v; ?>"
		><?php echo t('html')->img($img); ?></a>
	
	<?php endforeach; ?>
	
</div>


<div id="google_translate_element"></div>


<script type="text/javascript">
function googleTranslateElementInit()
{
	new google.translate.TranslateElement({pageLanguage: 'vi', autoDisplay: false}, 'google_translate_element'); //remove the layout
}
	  
function triggerHtmlEvent(element, eventName)
{
    var event;
    if(document.createEvent) {
        event = document.createEvent('HTMLEvents');
        event.initEvent(eventName, true, true);
        element.dispatchEvent(event);
    } else {
        event = document.createEventObject();
        event.eventType = eventName;
        element.fireEvent('on' + event.eventType, event);
    }
}

<!-- Flag click handler -->
$('.lang1 a').click(function(e)
{
	e.preventDefault();
	var lang = $(this).data('lang');
	$('#google_translate_element select option').each(function(){
		if($(this).val().indexOf(lang) > -1) {
			$(this).parent().val($(this).val());
			var container = document.getElementById('google_translate_element');
			var select = container.getElementsByTagName('select')[0];
			triggerHtmlEvent(select, 'change');
		}
	});
});
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>


<style>
body {
	top: auto !important;
}
body > .container {
	padding: 0 6px !important;
}

.skiptranslate,
#google_translate_element,
#goog-gt-tt {
	display: none !important;
}
.goog-text-highlight {
	background-color: transparent !important;
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
}
</style>
