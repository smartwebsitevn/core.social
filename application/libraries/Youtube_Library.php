<?php

/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
//require_once "YoutubeAPI_Library.php";

/**
 * Extends the BaseFacebook class with the intent of using
 * PHP sessions to store user ids and access tokens.
 */
class Youtube_Library  {

	public function __construct() {
       /* $CI = &get_instance();
		$params['key'] = 'AIzaSyBGBcOhZBtp3b8SzyKdV1QeS2QixzEySkE';//$CI->config->item('config_youtube_api_key','main');
		parent::__construct($params);*/

	}

    // kiem tra xem url co phai link youtube
    function checkUrl($url,&$id='')
	{
         if(empty($url))
 			return false;

        $parse_url = parse_url ( $url );
        if(!isset($parse_url ['query']))
            return false;
        $array = explode ( "&", $parse_url ['query'] );
	    $param = explode ( "=", $array [0] );
				//pr($param);
				if ($parse_url ['path'] != '/watch' || $param [0] != "v") {
					return false;
				}
  	    $key=$param [1];

		/*if (!$this->checkVideoExist($key)) {
				return false;
		}*/
        $id=$key;
        return true;

	}
    function checkVideoExist($key)
	{
        if(empty($key))
			return false;
		$rs = $this->_api($key);
		if(empty($rs))
			return false;
        return true;
	}


	function getVideoInfo($key,$is_key=true)
	{
		if(!$is_key){
			if(!$this->checkUrl($key,$key))
				return;
		}
		$rs = $this->_api($key);
		if(empty($rs))
			return false;
		// echo $video_image;
		//========================
		$entry = json_decode($rs,true);
		$entry = $entry['items'][0];
		//pr($entry);
		$snippet =$entry['snippet'];
		$contentDetails =$entry['contentDetails'];

		$statistics =$entry['statistics'];

		//pr($entry);
		$info= new stdClass();
		//===========
		$info->id	 	    = $key;
		$info->title	    = $snippet['title'];
		$info->description	= $snippet['description'];

		$info->url_image = $snippet['thumbnails']['medium']['url'] ;

		$dr =$contentDetails['duration'];
		$dr = str_replace('M',':',$dr);
		$dr = str_replace(array('PT','S'),'',$dr);
		$info->duration	 =$dr;

		$info->author_id  = '';
		$info->author_name  ='';

		$info->count_favorite=isset($statistics['favoriteCount'])?$statistics['favoriteCount']:0;
		$info->count_view= isset($statistics['viewCount'])?$statistics['viewCount']:0;
		$info->count_comment  = isset($statistics['commentCount'])?$statistics['commentCount']:0;
		$info->count_like  =  isset($statistics['likeCount'])?$statistics['likeCount']:0;
		$info->count_dislike  = isset($statistics['dislikeCount'])?$statistics['dislikeCount']:0;


		//===
		$info->rating_rate =  $info->rating_average = $info->rating_min =$info->rating_max =0;
		/*if(isset($entry['gd$rating'])){
			$info->rating_rate      = $entry['gd$rating']['numRaters'];
			$info->rating_average   = $entry['gd$rating']['average'];
			$info->rating_min       = $entry['gd$rating']['min'];
			$info->rating_max       = $entry['gd$rating']['max'];
		}*/
		//	pr($info);
		return $info;
	}


	function _api($key){
		$CI =& get_instance();
		$CI->load->library ( 'Curl_library', NULL, 'Lcurl' );
		$url = "https://www.googleapis.com/youtube/v3/videos?id=".$key."&key=AIzaSyD7fIriHLZyCFoaWRk_S4qm26T09oaBLW8&part=id,snippet,contentDetails,statistics,player,status";// player,status
		$rs = $CI->Lcurl->get ($url);
		return $rs;
	}
}
