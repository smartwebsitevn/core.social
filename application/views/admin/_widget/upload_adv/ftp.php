	<div id="ftpUpload">
    	<table cellpadding=0 cellspacing=0 class="div1" style="width: 480px;">
			<tr>
				<td style="width: 80px">FTP:</td>
				<td><span style="text-decoration: underline; color: #098cad">zendscript.com</span></td>
				<td><input class="gradbtn_back" type="submit" onclick="window.prompt ('Copy to clipboard: Ctrl+C, Enter', 'zendscript.com');" value="Copy" style="height: 32px;"></td>
			</tr>
			<tr>
				<td>Port:</td>
				<td>21</td>
				<td><input class="gradbtn_back" type="submit" onclick="window.prompt ('Copy to clipboard: Ctrl+C, Enter', '21');" value="Copy" style="height: 32px;"></td>
			</tr>
			<tr>
				<td>Login:</td>
				<td><?php echo @$user->name ? @$user->name : '<a href="'.$user->_url_login.'">Please Login</a>'?></td>
				<td><input class="gradbtn_back" type="submit" onclick="window.prompt ('Copy to clipboard: Ctrl+C, Enter', '<?php echo @$user->name ? @$user->name : ''?>');" value="Copy" style="height: 32px;"></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><span style="height: 32px; line-height: 32px;"><?php echo @$user->name ? '***' : 'Please Login'?></span></td>
				<td><input class="gradbtn_back" onclick="window.location='<?php echo site_url('user/edit')?>'" type="submit" value="<?php echo lang('change_password')?>" style="height: 32px;"></td>
			</tr>
		</table>
	</div>