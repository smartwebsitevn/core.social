<?php
	$mr = [];

	$mr['list'] = function() use ($list, $pages_config, $affiliate_link)
	{
		ob_start();?>

        <div class="row ng-scope">


            <div class="col-lg-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="title-accent">
                            <h3 style="margin-top:0px">
                                <?php echo lang('affiliate_link')?>: <b style="color:blue;text-align:center"><?php echo $affiliate_link?></b>
                            </h3>
                        </div>
                    </div>
                </div>

            </div>
            <hr/>
            
           
            <div class="col-lg-12">
                <div class="content-wrapper"">
                <div class="table-responsive main-container">
                    <?php
                    $table = array();
                    $i=1;
                    $table['columns'] = [
                        'stt'              => lang('STT'),
                        'username'         => lang('username'),
                       // 'level'            => lang('level'),
                        'created'          => lang('created'),
                    ];

                    $table['rows'] = [];

                    foreach ($list as $row)
                    {
                        $table['rows'][] = [
                            'stt'             => $i,
                            'username'          => $row->username,
                           // 'level'          => lang('user_level_'.$row->level),
                            'created'         => $row->_created,
                        ];
                        $i++;
                    }

                    echo macro('mr::table')->table($table);
                    ?>
              
            </div>
            <?php $this->widget->site->pages($pages_config); ?>

            <div class="clear"></div>
            <div class="row ng-scope">
                <div class="col-lg-12 text-right">
                    <a  class="btn btn-default" href="<?php echo site_url('invoice_order').'?service_key=Affiliate'?>" style="margin-top:20px"><?php echo lang('invoice_order_affiliate')?></a></div>
            </div>

        </div>


		<div class="clear"></div>

		<?php return ob_get_clean();
	};

	echo macro('mr::box')->box([
		'title' => lang('affiliate_list'),
		'body'  => $mr['list'](),
	]);
