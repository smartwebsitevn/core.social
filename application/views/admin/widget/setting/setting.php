<div class="form-group  ">
	<b class="col-sm-3 fontB blue f14 ">
		<?php echo  lang('tab_setting'); ?>:
	</b><br>
	<div name="setting_error" class="clear error"></div>
</div>


<?php
	foreach ($setting_params as $p => $o)
	{
		/*$_data = array();
		$_data['opt']	= $o;
		$_data['name'] 	= "setting[{$p}]";
		$_data['id'] 	= "param_setting_{$p}";
		$_data['value'] = (isset($info->setting[$p])) ? $info->setting[$p] : $o['value'];
		$this->load->view('admin/_common/param_form', $_data);*/

		$_data = $o;
		$_data['param'] 	= "setting[{$p}]";
		if(isset($info->setting[$p]) && !in_array($o['type'],['separate','ob']))
			$_data['value'] =  $info->setting[$p] ;
		else
			$_data['value'] = $o['value'];
		//echo $p;		pr($o,0); pr($_data);
		echo macro('mr::form')->row($_data);
	}
?>
