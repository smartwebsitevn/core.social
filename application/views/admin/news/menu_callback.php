<!-- Main content wrapper -->
<?php
$_row_action = function ($row) {
    ob_start();
    ?>
    <a class="btn btn-primary btn-xs select_item" data-url="<?php echo $row->_url_view ?>" ><?php echo lang('choice') ?></a>
    <?php return ob_get_clean();
};
$_macro = $this->data;

$_macro['table'] = array_only($this->data, array('total',));
$_macro['table']['title'] =lang('mod_news');
$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'title' => lang('title'),
    'created' => lang('created'),
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {

    $r = (array)$row;
    $r['title'] 	= t('html')->a($row->_url_view,$row->title,array('target'=>'_blank'));
    $r['created'] = $row->_created;
    $r['action'] 	= $_row_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro('mr::table')->table($_macro['table']);

?>
<script type="text/javascript">
$(document).ready(function(){
    $('.select_item').click(function(){
        $('input[name="url"]', window.parent.document).val($(this).data("url"));
        $.colorbox.close();
    });
})
</script>
