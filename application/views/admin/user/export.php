<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=user.xls");
header("Pragma: no-cache");
header("Expires: 0");
$mr = [];
$mr['purses'] = function ($user) {
    $list = [];

    foreach ($user->purses as $purse) {
        $list[] = $purse->number . ': ' . $purse->{'format:balance'};
    }

    return implode('<br>', $list);
};

?>
<table>
    <thead>
    <tr>
        <td>Stt</td>
        <th>Thành viên</th>
        <th>Email</th>
        <th>Điện thoại</th>
        <th>Ngày sinh</th>
        <th>Giới tính</th>
        <th>Quốc gia</th>
        <th>Ví tiền</th>
        <th>Trạng thái</th>
        <th>Ngày đăng ký</th>
        <?php /* ?>
        <td>Stt</td>
        <td><?php echo lang('no.'); ?></td>
        <td><?php echo lang('name'); ?></td>
        <td><?php echo lang('phone'); ?></td>
        <td><?php echo lang('email'); ?></td>
        <td><?php echo lang('phone'); ?></td>
        <td><?php echo lang('balance'); ?></td>
         <?php */ ?>

    </tr>


    </thead>

    <tbody class="list_item">
    <?php $i = 1; ?>
    <?php foreach ($list as $row): ?>
        <tr>
            <td><?php echo $i; ?></td>


            <td>  <?php echo $row->name; ?>            </td>
            <td><?php echo $row->email; ?>            </td>
            <td><?php echo $row->phone; ?>            </td>
            <td>
                <?php echo $row->birthday  ?>
            </td>
            <td>
                <?php echo $row->_gender ?>
            </td>
            <td>
                <?php
                // pr($row->_country);
                if (isset($row->_country))
                    echo $row->_country->name . ' - ';
                if (isset($row->_city))
                    echo $row->_city->name;
                echo '<br><i>' . word_limiter($row->address, 25).'</i>';
                ?>

            </td>
            <td>
                <?php echo $mr['purses']($row); ?>
            </td>

            <td class="textC">
                <?php echo macro()->status_color(($row->activation ? 'on' : 'off'), lang($row->activation ? 'activation_yes' : 'activation_no')) ?>
                <?php echo macro()->status_color(($row->blocked ? 'off' : 'on'), lang($row->blocked ? 'blocked_yes' : 'blocked_no')) ?>
            </td>
            <td>
                <?php echo get_date($row->created, 'time') ?>
            </td>
            <?php $i++ ?>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
		