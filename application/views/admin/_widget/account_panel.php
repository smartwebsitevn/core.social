<a href="#" class="dropdown-toggle" data-toggle="dropdown">
<img class="img-circle" src="<?php echo $acount->_avata_thumb; ?>" alt=""> <span class="user-info"><?php echo $acount->username; ?></span> <b class="caret"></b>
</a>
<ul class="dropdown-menu dropdown-user">
<li>
<a href="<?php echo admin_url('admin/profile')?>">
	<i class="fa fa-user"></i> My Profile
</a>
</li>
<!--<li>
<a href="#">
	<i class="fa fa-envelope"></i> My Messages
</a>
</li>
<li>
<a href="#">
	<i class="fa fa-tasks"></i> My Tasks
</a>
</li>
<li>
<a href="#">
	<i class="fa fa-gear"></i> Settings
</a>
</li>-->
<li>
<a href="<?php echo admin_url('logout')?>">
	<i class="fa fa-power-off"></i> <?php echo lang('logout')?>
</a>
</li>

</ul>
<?php // pr($acount);?>