<?php echo macro()->page_heading(lang('message_send_admin')) ?>
<?php echo macro()->page_body_start() ?>
<?php view('tpl::message/_menu',['current'=>'send']); ?>
    <form class=" form_action" action="<?php echo current_url() ?>" method="post">
        <?php /* ?>
        <div class="form-item ">
            <label  for="param_username"><?php echo lang('username'); ?>:</label>

            <input name="username" id="param_username" type="text" class="form-control form-text" />
            <div class="note">
              <?php echo lang('username_note')?>
            </div>
            <span name="username_autocheck" class="autocheck"></span>
            <div class="clear"></div>
            <div name="username_error" class="error"></div>

            <div class="clear"></div>
        </div>
        <?php */ ?>
        <div class="form-item ">
            <label for="param_title"><?php echo lang('title'); ?>:<span class="req">*</span></label>

            <input name="title" id="param_title" type="text" class="form-control form-text"/>

            <span name="title_autocheck" class="autocheck"></span>

            <div class="clear"></div>
            <div name="title_error" class="error"></div>

            <div class="clear"></div>
        </div>

        <div class="form-item ">
            <label for="param_content"><?php echo lang('content'); ?>:<span class="req">*</span></label>

            <textarea name="content" id="param_content" class="form-control form-text"></textarea>
            <span name="content_autocheck" class="autocheck"></span>

            <div class="clear"></div>
            <?php if ($message_max > 0): ?>
                <p class="note"><?php echo lang('message_max', $message_max) ?></p>
            <?php endif; ?>
            <div name="content_error" class="error"></div>

            <div class="clear"></div>
        </div>

        <div class="form-item ">
            <label for="param_pin"><?php echo lang('pin'); ?>:<span class="req">*</span></label>

            <input name="pin" id="param_pin" type="password" class="form-control form-text"/>
            <span name="pin_autocheck" class="autocheck"></span>

            <div class="clear"></div>
            <div name="pin_error" class="error"></div>

            <div class="clear"></div>
        </div>
        <?php /* ?>
         <div class="form-item ">

            <div class="note">
                <label for="param_send_admin" style="display:block;width:100%">
                     <input name="send_admin" id="param_send_admin" type="checkbox"/> <?php echo lang('send_admin'); ?>
                </label>

                <?php if($user->level >= $level_min_alldownline):?>
                    <label for="param_alldownline"  style="display:block;width:100%">
                         <input name="alldownline" id="param_alldownline" type="checkbox"/> <?php echo lang('alldownline'); ?>
                    </label>
                <?php endif;?>
            </div>

            <div class="clear"></div>
        </div>
        <?php */ ?>
        <div class="form-item form-submit">
            <br>
            <input type="submit" value="<?php echo lang('submit'); ?>" class="btn btn-info"/>
        </div>
    </form>
<?php echo macro()->page_body_end() ?>