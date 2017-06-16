<!DOCTYPE html>
<html>
<head>
  <title>Phim Công giáo HD</title>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
	  
  <!-- Chang URLs to wherever Video.js files will be hosted -->
  <link href="video-js47.css" rel="stylesheet" type="text/css">
  <!-- video.js must be in the <head> for older IEs to work. -->
  <script src="video.js"></script>
  <script src="youtube.js"></script>
   <script src="video-quality-selector.js"></script>
 <!-- Main style -->

  <!-- Bootstrap -->
	    <link media="all" type="text/css" rel="stylesheet" href="http://www.bilu.vn/assets/css/bootstrap.min.css">
	    <!-- Font-awsome -->
	    <link media="all" type="text/css" rel="stylesheet" href="http://www.bilu.vn/assets/css/font-awesome.min.css">
	    <!-- Bootstrap social -->
	    <link media="all" type="text/css" rel="stylesheet" href="http://www.bilu.vn/assets/css/bootstrap-social.css">
		
 
    <!-- Main style -->
    <link media="all" type="text/css" rel="stylesheet" href="http://www.bilu.vn/assets/css/style.css">

     <link media="all" type="text/css" rel="stylesheet" href="http://www.bilu.vn/assets/css/metro.css">
     <style>
     .clear{clear:both}
     </style>
     
  <!-- Unless using the CDN hosted version, update the URL to the Flash SWF -->
</head>
<body>
<?php
$link = 'https://picasaweb.google.com/lh/photo/oSbE7LmJg7_RD0sEQ7jyW_hOCqZ-rWSgN0iX_85P3x4?feat=directlink';
//link to get direct link
//$link = isset($_GET['url']) ? $_GET['url'] : '';
function picasa_direct($link) {
//get picasa page by default file_get_contents function
$data = file_get_contents($link);
$a = explode('"media":{"content":[', $data);
$a = explode('],"', $a[1]);
$datar = explode('},', $a[0]);
foreach ($datar as $key => $value) {
$value = str_replace("}}", "}", $value . "}");
$mp4s[] = json_decode($value, true);
}
$js = $bt = '';
for ($i = 1; $i < count($mp4s); $i++) {
$mp4 = $mp4s[$i];
//$js .= $mp4['height'] . '<br>' . urlencode($mp4['url']) . '</br>';

$js .= $mp4['height'] . '<source src="' . ($mp4['url']) . '" type="video/mp4" data-res="'.$mp4['height'].'px" ></source>';

}
return $js;
}
//echo all link
$link = picasa_direct($link); 
?> 
<div class="container-fluid">
	         <div class="row">
	             <div id="main">
	                 <h1>Học tiếng anh online qua phim</h1>
	                 <div class="col-md-8">
					     <div class="mediaplayer videoplayer no-poster" data-volume="high" data-state="idle" data-tracks="many" data-playersize="medium" data-playersizes="m" data-useractivity="false">
					         <video id='myplayer' class='video-js vjs-default-skin' controls autoplay width='100%' height='420' poster='https://lh6.googleusercontent.com/-9MFL8ZvT0Ic/U3jZq4Zk1SI/AAAAAAAAICw/vH3vCJjsOiY/w673-h409-no/baigiang.jpg' data-setup='{}'>
					             <?php echo $link; ?>
							 	
							 	  <track label="English" kind="subtitles" srclang="en" src="sub_en.srt" id="en" ></track> 
								<track default  kind="subtitles" label="Vietnamese" srclang="vi" src="sub_vn.srt" id="vi" ></track>  
	
					         </video>
					        <track label="English" kind="subtitles" srclang="en" src="sub_en.srt" id="en" ></track> 
								<track default  kind="subtitles" label="Vietnamese" srclang="vi" src="sub_vn.srt" id="vi" ></track>  
	
					     </div>
					     <div class='clear'></div>
					     <div class="mediaplayer-toolbox">
					         <div class="col-md-3"> <label data-content="Tick vào để chuyển qua chế độ luyện nghe" data-trigger="hover" data-placement="top" data-container="body" data-toggle="popover" class="btn btn-danger btn-block" data-original-title="" title=""> <input type="checkbox" id="hideSub"> Luyện nghe </label> </div> 
					         <div class="col-md-3 col-md-offset-4 text-left"> <div data-content="Bấm lùi lại để nghe lại đoạn hội thoại trước đó. Phím tắt 'Tab'" data-trigger="hover" data-placement="top" data-container="body" data-toggle="popover" class="input-group" data-original-title="" title=""> <span class="input-group-btn"> <button id="undo" class="btn btn-default">Lùi lại</button> </span> <input type="text" style='padding:0px' value="5" placeholder="giây" id="undoValue" class="form-control"> <span class="input-group-addon"> giây </span> </div> </div>
					         <div class="col-md-2"> <div data-content="Tùy chỉnh tốc độ nếu nhân vật nói quá nhanh" data-trigger="hover" data-placement="right" data-container="body" data-toggle="popover" class="input-group speed-controller" data-original-title="" title=""> <span class="input-group-btn"> <button type="button" class="btn btn-default speed-decrease"><i style="color:red" class="fa fa-minus"></i></button> </span> <span class="input-group-addon speed-value"> 1.0 </span> <span class="input-group-btn"> <button type="button" class="btn btn-default speed-increase"><i style="color:green" class="fa fa-plus"></i></button> </span> </div> </div>
					         <div class='clear'></div>
					     </div>
	                    <div class='clear'></div>
				     </div>
				
				     <div id="subtitles-sidebar" class="col-md-4">
				        <div class="show-sub" id="subtitles-container" style="height: 409px;"></div>
				     </div>
			     </div>
	         </div>
	     </div>
	 
		<script type='text/javascript'>
		    videojs('#myplayer',{ 
				plugins : { 
					resolutionSelector : {
						default_res : '360px'
					} 
				} 
			});
			videojs('myplayer').ready(function(){
				var vid = this;
				vid.on('ended', function(){
					//window.location.href = '/html5_picasa_youtube/youtube.html'
				});
			});   

			    
        </script>
      
	 <!-- Custom JS -->
	 
		<script src="movies.play.js"></script>
		<script src="script.js"></script>
		<script src="track.js"></script>
		
</body>
</html>

