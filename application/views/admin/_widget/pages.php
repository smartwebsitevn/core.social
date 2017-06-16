<?php
	$config['full_tag_open'] 	= '<div class="pull-right"><ul class="pagination">';
	$config['full_tag_close'] 	= '</ul></div>';
	$config['first_link'] 	  	= '&lt;&lt;';
	$config['first_tag_open'] 	= '<li>';
	$config['first_tag_close'] 	= '</li>';
	$config['last_link'] 		= '&gt;&gt;';
	$config['last_tag_open'] 	= '<li>';
	$config['last_tag_close'] 	= '</li>';
	$config['next_link'] 		= '&gt;';
	$config['next_tag_open'] 	= '<li>';
	$config['next_tag_close'] 	= '</li>';
	$config['prev_link']	 	= '&lt;';
	$config['prev_tag_open'] 	= '<li>';
	$config['prev_tag_close'] 	= '</li>';
	$config['cur_tag_open'] 	= '<li class="active"><a>';
	$config['cur_tag_close'] 	= '</a></li>';
	$config['num_tag_open']		= '<li>';
	$config['num_tag_close'] 	= '</li>';

	$this->pagination->initialize($config);
	echo $this->pagination->create_links();
	
?>