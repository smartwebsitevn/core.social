<form class="form form-horizontal form_action" method="post" action="<?php echo $user->_url_edit; ?>">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h1 class="panel-title">Cài đặt tài khoản</h1>
            </div>
            <div class="col-md-6 text-right">
                <a _submit="true" class="btn btn-default mr20" href="<?php echo site_url('user_account/edit') ?>">Cập
                    nhập</a>
                <a class="show-account-info">Hủy</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php
        $public_url_js = public_url('js');
        ?>
        <script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/plupload.full.js"></script>
        <script type="text/javascript"
                src="<?php echo $public_url_js ?>/jquery/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
        <script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/script.js"></script>
        <?php
        $rows[] = [
            'param' => 'name',
            'name' => lang('full_name'),
            'value' => $user->name,
            'req' => true,
        ];


        if ($user->can_edit_email) {
            $rows[] = array(
                'param' => 'email_edit',
                'name' => lang('email'),
                'value' => $user->email,
                'req' => true,
            );
        }
        if ($user->can_edit_username) {
            $rows[] = array(
                'param' => 'username_edit',
                'name' => lang('username'),
                'req' => true,
                'value' => $user->username,
            );
        }

        if ($user->can_edit_phone) {
            $rows[] = array(
                'param' => 'phone_edit',
                'name' => lang('phone'),
                'req' => true,
                'value' => $user->phone,
            );
        }

        $rows[] = [
            'param' => 'avatar', 'name' => lang('Avatar'),
            'type' => 'image',
            '_upload' => $upload_avatar,
        ];
        $rows[] = [
            'param' => 'gender',
            'type' => 'bool',
            //'req' 	=> true,
            'value' => $user->gender ? $user->gender : 1,
            'values' => ['1' => lang('gender_1'), '2' => lang('gender_2'), '3' => lang('gender_3')],
        ];
        $rows[] = [
            'param' => 'birthday',
            'type' => 'date',
            //'req' 	=> true,
            'value' => $user->birthday,
            'attr' => ['placeholder' => lang("birthday_hint")]

        ];
        //pr($countrys);
        $rows[] = [
            'param' => 'country',
            'type' => 'select',
            //'req' 	=> true,

            'value' => $user->country,
            'values_row' => [$countrys, 'id', 'name'],
            'attr' => ['_dropdownchild' => "city", "_url" => site_url('user/get_citys')]
        ];
        $rows[] = [
            'param' => 'city',
            'type' => 'select',
            'value' => $user->city,
            //'req' 	=> true,

            'values_row' => [$citys, 'id', 'name'],
            //'attr'=>['_dropdownchild'=>"distric_id","_url"=>site_url('user/get_districs')]
        ];

        /*$rows[] =    [
            'param' => 'distric',
            'type' => 'select',
            'value' => $user->distric,
            'values_row' => [ $districs, 'distric_id', 'distric_name'],
        ];*/
        /*$rows[] = array(
            'param' => 'subject_id','name'=>lang('subject'),'type'=>'select',
            'value'=>$user->subject_id,'values_row'=>array($cat_type_subject,'id','name'),
            'req' 	=> true,
        );*/
        foreach ([/*'profession',*/
                     'facebook', 'twitter', 'address',] as $f) {
            $rows[] = [
                'param' => $f,
                'value' => $user->$f,
            ];
        }

        $rows[] = [
            'param' => 'desc',
            'value' => $user->desc,
            'type' => "textarea"
        ];


        /*$rows[] =    [
            'param' => 'password_old',
            'type' 	=> 'password',
            'req' 	=> true,
        ];*/

        foreach ($rows as $row) {
            echo macro('mr::form')->row($row);
        }

        ?>


    </div>
    <div class="panel-footer text-right">
        <a _submit="true" class="btn btn-default mr20" href="<?php echo site_url('user_account/edit') ?>">Cập
            nhập</a>
        <a class="show-account-info">Hủy</a>

    </div>
</form>

