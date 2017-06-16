<?php
$_macro = $this->data;
$_macro['toolbar'] = array(
    array(
        'url' => admin_url('log_access/admin'), 'title' => lang('title_log_access_admin'), 'icon' => 'user',
    ),
    array(
        'url' => admin_url('log_access/user'), 'title' => lang('title_log_access_user'), 'icon' => 'group',
    ),
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['filters'] = array(
    array(
        'param' => 'table_id', 'name' => lang($table . '_id'), 'value' => $filter['table_id'],
    ),
    array(
        'param' => 'ip', 'name' => lang('ip'), 'value' => $filter['ip'],
    ),

    array(
        'param' => 'created', 'type' => 'date', 'name' => lang('from_date'), 'value' => $filter['created'],
    ),

    array(
        'param' => 'created_to', 'type' => 'date', 'name' => lang('to_date'), 'value' => $filter['created_to'],
    ),
    array('type' => 'sp'),
    array(
        'param' => 'url', 'name' => lang('url'), 'value' => $filter['url'],
    ),

    array(
        'param' => 'ruri', 'name' => lang('ruri'), 'value' => $filter['ruri'],
    ),
);

$_macro['table']['columns'] = array(
    $table . '_id' => lang($table . '_id'),
    $table => lang($table),
    'url' => lang('url'),
    'ip' => 'IP',
    'time' => lang('time'),
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {
    $_acc = '';
    if ($table == 'admin') {
        $_acc = $row->acc->username;
    } elseif ($table == 'user') {
        $_acc = $row->acc->email;
    }

    if (isset($row->user) && $row->user)
        $user = t('html')->a(admin_url('user') . "?user_id={$row->user_id}", $row->user->email, ['target' => 'target']);

    $r = (array)$row;
    $r[$table . '_id'] = t('html')->a('', $row->table_id, ['_param' => 'acc', '_value' => $row->table_id, 'class' => 'view_of_field tipS', 'title' => lang('view_of_acc') . '<br>' . $_acc]);
    $r[$table] = t('html')->a($row->acc->_url_view, $_acc, ['target' => 'target']);
    $r['url'] = t('html')->a($row->url, $row->uri, ['target' => 'target']);

    $r['ip'] = t('html')->a('', $row->ip, ['_param' => 'ip', '_value' => $row->ip, 'class' => 'view_of_field tipS', 'title' => lang('view_of_acc') . '<br>' . $row->ip]);
    $r['time'] = t('html')->a('', $row->_created_time, ['_param' => 'created', '_value' => $row->_created, 'class' => 'view_of_field tipS', 'title' => lang('view_of_created') . '<br>' . $row->_created]);
    $r['action'] = t('html')->a('#log_access_' . $row->id . '?lightbox&inline=true', lang('detail'), ['class' => 'lightbox']);

    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);
?>
<div style="display:none;">
    <?php foreach ($list as $row): ?>
    <?php
    $_acc = '';
    if ($table == 'admin') {
        $_acc = $row->acc->username;
    } elseif ($table == 'user') {
        $_acc = $row->acc->email;
    }
    ?>

    <div id="log_access_<?php echo $row->id; ?>" class="widget mg0 form log_access">
        <div class="portlet">
            <div class="portlet-heading bg-primary">
                <div class="portlet-title">
                    <h4><i class="fa fa-info"></i> <?php echo lang('info'); ?></h4>
                </div>

            </div>
            <div class="portlet-body ">
                <table class="table table-bordered table-striped table-hover tc-table">
                    <tbody>
                    <tr>
                        <td class="row_label"><?php echo lang($table . '_id'); ?></td>
                        <td class="row_item">
                            <?php echo $row->table_id; ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="row_label"><?php echo lang($table); ?></td>
                        <td class="row_item">
                            <a href="<?php echo $row->acc->_url_view; ?>" target="_blank">
                                <?php echo $_acc; ?>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td class="row_label"><?php echo lang('url'); ?></td>
                        <td class="row_item">
                            <input value="<?php echo $row->url; ?>" type="text" class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <td class="row_label"><?php echo lang('ip'); ?></td>
                        <td class="row_item">
                            <?php echo $row->ip; ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="row_label"><?php echo lang('user_agent'); ?></td>
                        <td class="row_item">
                            <?php echo $row->user_agent; ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="row_label"><?php echo lang('time'); ?></td>
                        <td class="row_item">
                            <?php echo $row->_created_time; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
