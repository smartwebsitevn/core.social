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
        echo macro('mr::form')->row([
            'param' => 'avatar', 'name' => lang('Avatar'),
            'type' => 'image',
            '_upload' => $upload_avatar,
        ]);

        echo macro('mr::form')->row([
            'param' => 'name',
            'name' => lang('name'),
            'value' => $user->name,
            'req' => true,
        ]);
        echo macro('mr::form')->row([
            'param' => 'profession',
            'name' => lang('profession'),
            'value' => $user->profession,
            'req' => true,
        ]);

        if ($user->can_edit_email) {
            echo macro('mr::form')->row(array(
                'param' => 'email_edit',
                'name' => lang('email'),
                'value' => $user->email,
                'req' => true,
            ));
        }
        if ($user->can_edit_username) {
            echo macro('mr::form')->row(array(
                'param' => 'username_edit',
                'name' => lang('username'),
                'req' => true,
                'value' => $user->username,
            ));
        }
        if ($user->can_edit_phone) {
            echo macro('mr::form')->row(array(
                'param' => 'phone_edit',
                'name' => lang('phone'),
                'req' => true,
                'value' => $user->phone,
            ));
        }


        echo macro('mr::form')->row([
            'param' => 'facebook',
            'value' => $user->facebook,
        ]);
        echo macro('mr::form')->row([
            'param' => 'website',
            'value' => $user->website,
        ]);
        /* echo macro('mr::form')->row( [
             'param' => 'gender',
             'type' => 'bool',
             //'req' 	=> true,
             'value' => $user->gender ? $user->gender : 1,
             'values' => ['1' => lang('gender_1'), '2' => lang('gender_2'), '3' => lang('gender_3')],
        ]);
         echo macro('mr::form')->row( [
             'param' => 'birthday',
             'type' => 'date',
             //'req' 	=> true,
             'value' => $user->birthday,
             'attr' => ['placeholder' => lang("birthday_hint")]

        ]);*/
        //pr($countrys);
        /* echo macro('mr::form')->row( [
             'param' => 'country',
             'type' => 'select',
             //'req' 	=> true,

             'value' => $user->country,
             'values_row' => [$countrys, 'id', 'name'],
             'attr' => ['_dropdownchild' => "city", "_url" => site_url('user/get_citys')]
        ]);*/
        echo macro('mr::form')->row([
            'param' => 'city',
            'type' => 'select',
            'value' => $user->city,
            //'req' 	=> true,
            'values_row' => [$citys, 'id', 'name'],
            //'attr'=>['_dropdownchild'=>"distric_id","_url"=>site_url('user/get_districs')]
        ]);
        /*echo macro('mr::form')->row(    [
            'param' => 'distric',
            'type' => 'select',
            'value' => $user->distric,
            'values_row' => [ $districs, 'distric_id', 'distric_name'],
       ]);*/
        /*foreach (['profession',
                     'facebook', 'twitter', 'address',] as $f) {
            echo macro('mr::form')->row( [
                'param' => $f,
                'value' => $user->$f,
           ]);
        }
        */

        //city-country
        $countrys = model('country')->filter_get_list(['show' => 1]);
        $citys = model('city')->filter_get_list(["country_id"=>230,'show' => 1]);
        ?>
                <?php echo macro('mr::form')->info_city(array(
                    'name' => 'City',
                    'param' => 'working_city_id',
                    'value' => $user->working_city_id,
                    'values' => $citys,
                )); ?>

                <?php echo macro('mr::form')->info_country_multi(array(
                    'name' => 'Country',
                    'param' => 'working_country_id',
                    'value' => $user->working_country_id,
                    'values' => $countrys,
                )); ?>
        <?php
        echo macro('mr::form')->row([
            'param' => 'desc',
            'value' => $user->desc,
            'type' => "textarea"
        ]);

        /*echo macro('mr::form')->row(    [
            'param' => 'password_old',
            'type' 	=> 'password',
            'req' 	=> true,
       ]);*/


        ?>


    </div>
    <div class="panel-footer text-right">
        <a _submit="true" class="btn btn-default mr20" href="<?php echo site_url('user_account/edit') ?>">Cập
            nhập</a>
        <a class="show-account-info">Hủy</a>

    </div>
</form>
