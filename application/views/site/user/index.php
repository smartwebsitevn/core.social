<?php

/*echo macro('mr::box')->box([

    'title' => 'Thông tin ví',

    'content' => macro()->info($user->purses->lists('format:balance', 'number')),

]);*/
//if (!mod("product")->setting('premium_turn_off_function_renew_plan')){
 $data_service = function () use ($user) {
        ob_start(); ?>
        <?php
        $row = model('service_order')->get_info_rule(array('user_id' => $user->id));
        /* $exprice = 'Hết hạn';
         if ($service_order) {
             $exprice = get_date($service_order->expire_to, 'full');
         }*/

        $rows = [];
        if ($row) {
            $row = mod('service_order')->add_info($row);
            $_data_title = function ($row) {
                $status = $row->title;
                $status .= '<p style="margin-top:10px"><b>'.lang('service_day_expired' ).': </b>' . get_date($row->expire_to, 'full') . '</p>';
                if ($row->last_update_status > $row->created)
                    $status .= '<p style="margin-top:10px"><b>' . lang('service_day_last_update') . ': </b>' . get_date($row->last_update_status, 'full') ;
                // if ($row->admin_update)
                // $status .= (($row->admin_update) ? " (<b style='color:red'>" . $row->admin_update . "</b>)" : "") . '</p>';
                $status .= '<p style="margin-top:10px"><b>'.lang('service_day_register' ).': </b>' . $row->_created_full . '</p>';
                return $status;

            };
            $_data_status = function ($row) {
                if ($row->expire_to < now())
                    $status = macro()->status_color('danger',lang('service_status_expired' ));
                else
                    $status = macro()->status_color($row->status, lang('service_status_' . $row->status));

                return $status;

            };
            $_data_action = function ($row) {
                if ($row->status == 'suspended')
                    $action = lang("notice_service_blocked_please_contact_admin");

                else
                    $action = t('html')->a(site_url('renew_plan'), lang("service_renew"), ['class' => 'btn btn-default btn-sm']);

                return $action;

            };
            $_id = 'SV-'.sprintf("%07d", $row->id);

            $rows[] = [
                'id' =>  $_id,
                'title' => $_data_title($row),
                // 'device' => $row->device,
                'status' => $_data_status($row),
                'action' =>  $_data_action($row),//  ,

            ];


            $table['title'] = '';//lang('title_user_info');
            $table['columns'] = [
                'id' =>  lang('service_id'),
                //'amount'           => lang('amount'),
                'title' => lang('service_package'),
                //'device' => lang('device'),
                'status' => lang('status'),
                //'created' => lang('created'),
                // 'expire' => //lang('expire'),
                'action' => lang('action'),
            ];
            $table['rows'] = $rows;


            return macro('mr::table')->table($table);
        } else {
            ?>
            <div class="alert alert-danger">
                <?php echo lang("notice_service_not_register") ?>
                <a href="<?php echo site_url('buy-vip') ?>" class="btn btn-default btn-sm"><?php echo lang("service_register") ?></a>
            </div>
            <?php
        }
        ?>

        <?php return ob_get_clean();
    };

//echo $data_service();
/*echo macro('mr::box')->box([
    'title' => lang("title_service_info"),
    'content' => $data_service(),
   'content' => macro()->info([
          'Hạn xem của bạn' => $exprice,
          t('html')->a(site_url('renew_plan'), 'Gia hạn', ['class' => 'btn btn-default btn-sm']),
      ]),

]);
echo '<hr>';*/
//}
$purse = $user->purses->first();
//pr( $purse);
//pr($user);

/*$subject_name='';
if($user->subject_id){
    $subject =model('cat')->get_info($user->subject_id);
    if($subject) $subject_name =$subject->name;
}*/
echo macro('mr::box')->box([

    'title' => lang('title_user_info'),
    'content' => macro()->info([
        lang('image') => '<img src="'.$user->avatar->url_thumb.' " style="height: 50px;width: 50px; class="img-rounded">',
        // lang('account') => $user->username ?: $user->email,
         lang('account') => $user->email,
       // lang('balance') => '<b>' . $purse->format('balance') . '</b>',//('format:balance', 'number'),
        lang('full_name') => $user->name,
        lang('phone') => $user->phone,
        lang('gender') => $user->_gender,
        lang('birthday') => $user->birthday,
        lang('country') => isset($user->_country)?$user->_country->name:'',
        lang('city') => isset($user->_city)?$user->_city->name:'',
        lang('address') => $user->address,
        lang('user_date_added') => get_date($user->created,"full"),
        lang('last_login') => get_date($user->last_login,"full"),
        lang('user_group') => $user->user_group->name,
        lang('full_name') => $user->name,
        t('html')->a(site_url('user/edit'), lang('button_edit'), ['class' => 'btn btn-default btn-sm']),
    ]),

]);

