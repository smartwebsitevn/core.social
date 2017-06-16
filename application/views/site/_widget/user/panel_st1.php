<?php if (!$is_login): ?>
	<div class="dropdown dropdown--login">
		<div class="dropdown__toggle">
			<a class="cd-signin dropdown__main-link"  href="javascript:void(0)"  >Login</a>
		</div>
	</div>
	<div class="dropdown dropdown--signup">
		<div class="dropdown__toggle">
			<a class="cd-signup dropdown__main-link" href="javascript:void(0)" >Sign up</a>
		</div>
	</div>

<?php else:// pr($user);?>
	<div class="c_user-notification">
		<div class="dropdown dropdown--notification">
			<div class="dropdown__toggle">
				<a class="dropdown__main-link" href="#">
					<div class="pos-r">
						<i class="udi udi-envelope dropdown__main-icon"></i>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="c_user-notification">
		<div class="dropdown dropdown--notification">
			<div class="dropdown__toggle">
				<a class="dropdown__main-link" href="#">
					<div class="pos-r">
						<i class="udi udi-bell dropdown__main-icon"></i>
						<b class="badge ng-hide">
							0
						</b>
					</div>
				</a>
			</div>
			<div class="dropdown__menu">
				<div>
					<header class="dropdown__header dropdown__tab-header">
						<div class="dropdown__title"><span>Notifications</span></div>
						<div class="dropdown__setting">
							<a href="#"> <i class="udi udi-settings"></i> </a>
						</div>
					</header>
					<div class="notification__list ng-hide">
					</div>
					<div class="notification__list" style="">
						<div class="p20 fx-c">
							<div><span>No notifications.</span></div>
						</div>
					</div>
				</div>
				<div class="dropdown__footer">
				</div>
			</div>
		</div>
	</div>
	<div class="dropdown dropdown--user">
		<div class="dropdown__toggle">
			<a class="dropdown__main-link" href="#">
				<div class="pos-r">
					<img src="<?php echo $user->avatar->url_thumb ?> "
						 class="dropdown__avatar"></span>
					<!--<b class="badge">
						1
					</b>-->
				</div>
			</a>
		</div>
		<div class="dropdown__menu">
			<ul class="dropdown__menu-list">
				<li class="menu__link ">
					<a href="<?php echo site_url('my-products') ?>" >
						<span class="menu__title"><i class="fa fa-mortar-board"></i> <?php echo lang('user_panel_product_owner') ?></span>
					</a>
				</li>
				<li class="menu__link--bordered "></li>

				<li class="menu__link">
					<a href="<?php echo site_url('user') ?>">
						<span class="menu__title"><i class="fa fa-user"></i> <?php echo lang('user_panel_user') ?></span>
					</a>
				</li>
				<li class="menu__link--bordered "></li>
				<li class="menu__link">
					<a href="<?php echo site_url('deposit_card') ?>">
									<span class="menu__title"><i class="fa fa-bank"></i> <?php echo lang('user_panel_deposit') ?>
							</span>
					</a>
				</li>
				<li class="menu__link--bordered "></li>

				<li class="menu__link">
					<a href="<?php echo site_url('invoice_order') ?>">
						<span class="menu__title"><i class="fa fa-history"></i> <?php echo lang('user_panel_tran') ?></span>
					</a>
				</li>
				<li class="menu__link--bordered "></li>

				<li class="menu__link">
					<a href="<?php echo $user->_url_logout; ?>">
						<span class="menu__title"><i class="fa fa-share"></i> <?php echo lang('button_logout'); ?></span>
					</a>
				</li>
			</ul>
		</div>
	</div>

<?php endif; ?>
