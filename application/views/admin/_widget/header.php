<!-- BEGIN TOP NAVIGATION -->
<?php
$public_url=public_url();
$public_url_admin=public_url('admin');
$public_url_js=public_url('js');

$logo=public_url('admin/ekoders/images/logo.png') ;
$logo_uploaded=setting_get('config-logo_admin');

if($logo_uploaded){

	$logo_uploaded=file_get_image_from_name($logo_uploaded);

	if($logo_uploaded)
		$logo=$logo_uploaded->url;
}
$langs= lang_get_list();
?>
<nav class="navbar-top" role="navigation">
	<!-- BEGIN BRAND HEADING -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".top-collapse">
			<i class="fa fa-bars"></i>
		</button>
		<div class="navbar-brand">
			<a href="<?php echo admin_url()?>" >
				<img src="<?php echo $logo?>" alt="logo" class="img-responsive">
			</a>
		</div>
	</div>
	<!-- END BRAND HEADING -->
	<div class="nav-top">
		<!-- BEGIN RIGHT SIDE DROPDOWN BUTTONS -->
		<ul class="nav navbar-right">
			<li class="dropdown">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
					<i class="fa fa-bars"></i>
				</button>
			</li>
			<li class="dropdown">
				<?php
				$order = model('invoice_order')->filter_get_total(array('order_status' => 'pending'));
				$contact = model('contact')->filter_get_total(['read' => 0]);
				$comment = model('comment')->filter_get_total(['readed' => 0]);

				$count = $order + $contact + $comment; ?>

				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-bell"></i> <span class="badge up badge-success"><?php echo number_format($count) ?></span>
				</a>
				<?php if (($count)>0): ?>
					<ul class="dropdown-menu dropdown-scroll dropdown-alerts">
						<li class="dropdown-header">
							<i class="fa fa-bell"></i> <?php echo number_format($count) ?> Thông báo
						</li>
						<li id="alertScroll">
							<ul class="list-unstyled">
								<?php if ($order): ?>
									<li>
										<a href="<?php echo admin_url('invoice_order/pending')?>">
											<div class="alert-icon bg-info pull-left">
												<i class="fa fa-download"></i>
											</div>
											Đơn hàng chờ sử lý <span class="badge badge-info pull-right"><?php echo number_format($order) ?></span>
										</a>
									</li>
								<?php endif; ?>
								<?php if ($contact): ?>

									<li>
										<a href="<?php echo admin_url('contact').'?read=no' ?>">

											<div class="alert-icon bg-success pull-left">
												<i class="fa fa-cloud-upload"></i>
											</div>
											Yêu cầu mới khách hàng<span class="badge badge-info pull-right"><?php echo number_format($contact) ?></span>

										</a>
									</li>
								<?php endif; ?>
								<?php if ($comment): ?>

									<li>
										<a href="<?php echo admin_url('comment').'?readed=no' ?>">

											<div class="alert-icon bg-danger pull-left">
												<i class="fa fa-bolt"></i>
											</div>
											Bình luận mới từ thành viên <span class="badge badge-info pull-right"><?php echo number_format($comment) ?></span>
										</a>
									</li>
								<?php endif; ?>

							</ul>
						</li>

					</ul>
				<?php endif; ?>
			</li>
			<!--<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-envelope"></i> <span class="badge up badge-primary">2</span></a>
				<ul class="dropdown-menu dropdown-scroll dropdown-messages">
					<li class="dropdown-header">
						<i class="fa fa-envelope"></i> 2 New Messages
					</li>
					<li id="messageScroll">
						<ul class="list-unstyled">
							<li>
								<a href="#">
									<div class="row">
										<div class="col-xs-2">
											<img class="img-circle" src="<?php /*echo $public_url_admin*/?>/ekoders/images/user-profile-1.jpg" alt="">
										</div>
										<div class="col-xs-10">
											<p>
												<strong>John Smith</strong>: Hi again! I wanted to let you know that the order...
											</p>
											<p class="small">
												<i class="fa fa-clock-o"></i> 5 minutes ago
											</p>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="row">
										<div class="col-xs-2">
											<img class="img-circle" src="<?php /*echo $public_url_admin*/?>/ekoders/images/user-profile-2.jpg" alt="">
										</div>
										<div class="col-xs-10">
											<p>
												<strong>Roddy Austin</strong>: Thanks for the info, if you need anything...
											</p>
											<p class="small">
												<i class="fa fa-clock-o"></i> 3:39 PM
											</p>
										</div>
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="dropdown-footer">
						<a href="#">
							Read All Messages
						</a>
					</li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-bell"></i> <span class="badge up badge-success">3</span>
				</a>
				<ul class="dropdown-menu dropdown-scroll dropdown-alerts">
					<li class="dropdown-header">
						<i class="fa fa-bell"></i> 3 New Alerts
					</li>
					<li id="alertScroll">
						<ul class="list-unstyled">
							<li>
								<a href="#">
									<div class="alert-icon bg-info pull-left">
										<i class="fa fa-download"></i>
									</div>
									Downloads <span class="badge badge-info pull-right">16</span>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="alert-icon bg-success pull-left">
										<i class="fa fa-cloud-upload"></i>
									</div>
									Server #8 Rebooted <span class="small pull-right"><strong><em>12 hours ago</em></strong></span>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="alert-icon bg-danger pull-left">
										<i class="fa fa-bolt"></i>
									</div>
									Server #8 Crashed <span class="small pull-right"><strong><em>12 hours ago</em></strong></span>
								</a>
							</li>
						</ul>
					</li>
					<li class="dropdown-footer">
						<a href="#">
							View All Alerts
						</a>
					</li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-tasks"></i> <span class="badge up badge-info">7</span>
				</a>
				<ul class="dropdown-menu dropdown-scroll dropdown-tasks">
					<li class="dropdown-header">
						<i class="fa fa-tasks"></i> 10 Pending Tasks
					</li>
					<li id="taskScroll">
						<ul class="list-unstyled">
							<li>
								<a href="#">
									<p>
										Purchase Order #439 <span class="pull-right"><strong>52%</strong></span>
									</p>
									<div class="progress progress-striped">
										<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100" style="width: 52%;"></div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<p>
										March Content Update <span class="pull-right"><strong>14%</strong></span>
									</p>
									<div class="progress">
										<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100" style="width: 14%;"></div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<p>
										Client #42 Data Scrubbing <span class="pull-right"><strong>68%</strong></span>
									</p>
									<div class="progress progress-striped">
										<div class="progress-bar" role="progressbar" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100" style="width: 68%;"></div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<p>
										PHP Upgrade Server #6 <span class="pull-right"><strong>85%</strong></span>
									</p>
									<div class="progress">
										<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%;"></div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<p>
										Malware Scan <span class="pull-right"><strong>66%</strong></span>
									</p>
									<div class="progress progress-striped active">
										<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" style="width: 66%;"></div>
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="dropdown-footer">
						<a href="#">
							View All Tasks
						</a>
					</li>
				</ul>
			</li>-->
			<!--Speech Icon-->
			<!--<li class="dropdown">
                <a href="#" class="speech-button">
                    <i class="fa fa-microphone"></i>
                </a>
            </li>-->
			<!--Speech Icon-->
			<li class="dropdown user-box">
				<!-- Account panel top -->
				<?php //t('widget')->admin->account_panel('tpl::_widget/account_panel_top'); ?>

				<!-- Account panel -->
				<?php t('widget')->admin->account_panel(); ?>


			</li>
			<!--Search Box-->
			<!--<li class="dropdown nav-search-icon">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="glyphicon glyphicon-search"></span>
                </a>
                <ul class="dropdown-menu dropdown-search">
                    <li>
                        <div class="search-box">
                            <form class="" role="search">
                                <input type="text" class="form-control" placeholder="Search" />
                            </form>
                        </div>
                    </li>
                </ul>
            </li>-->
			<!--Search Box-->
		</ul>
		<!-- END RIGHT SIDE DROPDOWN BUTTONS -->
		<!-- BEGIN TOP MENU -->
		<div class="collapse navbar-collapse top-collapse">
			<!-- .nav -->
			<ul class="nav navbar-left navbar-nav">
				<?php if(config('language_multi', 'main')): ?>
				<li>
					<div style="float:right;margin: 20px 10px 18px 7px">
						<?php foreach ($langs as $lang): ?>
							<a style="margin: 0;padding: 0" href="<?php echo admin_url('home/lang/'.$lang->id) ?>"><img style="height:11px"  src="<?php echo public_url('img/world/'.strtolower($lang->code).'.gif')?>" alt="<?php echo $lang->code ?>" class="img-responsive pull-right ml5"></a>
						<?php endforeach; ?>
					</div>
				</li>
				<?php endif; ?>
				<li><a><i class="fa fa-eye"></i> <?php echo t('input')->ip_address() ?></a></li>
				<li><a><i class="fa fa-clock-o"></i> <?php echo format_date('','full') ?></a></li>
				<li><a href="<?php echo site_url()?>" target="_blank"><i class="fa fa-share"></i> <?php echo lang('home') ?></a></li>

			</ul>
			<!-- /.nav -->
		</div>
		<!-- END TOP MENU -->
	</div><!-- /.nav-top -->
</nav><!-- /.navbar-top -->
<!-- END TOP NAVIGATION -->
<!-- BEGIN SIDE NAVIGATION -->
<nav class="navbar-side" role="navigation">
	<div class="navbar-collapse sidebar-collapse collapse">

		<!-- BEGIN SHORTCUT BUTTONS -->
		<div class="media">
			<ul class="sidebar-shortcuts">
				<li><a href="<?php echo admin_url('user')?>" class="btn"><i class="fa fa-user icon-only"></i></a></li>
				<li><a href="<?php echo admin_url('contact')?>" class="btn"><i class="fa fa-envelope icon-only"></i></a></li>
				<li><a href="<?php echo admin_url('widget')?>" class="btn"><i class="fa fa-th icon-only"></i></a></li>
				<li><a href="<?php echo admin_url('setting')?>" class="btn"><i class="fa fa-gear icon-only"></i></a></li>
			</ul>
		</div>
		<!-- END SHORTCUT BUTTONS -->


		<!-- BEGIN FIND MENU ITEM INPUT -->
		<div class="media-search">
			<input type="text" class="input-menu" id="input-items" placeholder="Find...">
		</div>
		<!-- END FIND MENU ITEM INPUT -->

		<!-- Left navigation -->
		<?php t('widget')->admin->menu(); ?>


		<!--<div class="sidebar-labels">
			<h4>Labels</h4>
			<ul>
				<li><a href="#"><i class="fa fa-circle-o text-primary"></i> My Recent <span class="badge badge-primary">3</span></a></li>
				<li><a href="#"><i class="fa fa-circle-o text-success"></i> Background</a></li>
			</ul>
		</div>

		<div class="sidebar-alerts">
			<div class="alert fade in">
				<span>Sales Report</span>
				<div class="progress progress-mini progress-striped active no-margin-bottom">
					<div class="progress-bar progress-bar-primary" style="width: 36%"></div>
				</div>
				<small>Calculating daily bias... 36%</small>
			</div>
		</div>-->

	</div><!-- /.navbar-collapse -->
</nav><!-- /.navbar-side -->
<!-- END SIDE NAVIGATION -->