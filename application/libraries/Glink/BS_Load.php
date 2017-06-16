<?php
error_reporting(E_ERROR | E_PARSE);
set_time_limit(0);

/** Gọi Class **/
include_once 'BS_Library.php';
$BS_Library = new BS_Library;

/** Check Token **/
if(isset($_POST['csrfToken']) && $_POST['csrfToken'])
$csrfToken = $BS_Library->csrfToken($_POST['csrfToken']);
if(!$csrfToken) exit();


$link = isset($_POST['link'])?$_POST['link']:'';
if(!$link) exit();

/** Sub **/
$sub = isset($_POST['sub'])?$_POST['sub']:'';
if($sub) {
	$array = explode(',', $sub);  
	foreach($array as $array_sub) {
	    $dsub = explode('|', $array_sub);
	    $lists[]= array('file' => $dsub[0], 'label' => $dsub[1], 'kind' => 'subtitles');
	}
	$sub = $lists;
}

$title = isset($_POST['title'])?$_POST['title']:'';

/** Thời gian cache **/
$timeCache = '7200';

/** Get link **/
print $BS_Library->BS_Player();