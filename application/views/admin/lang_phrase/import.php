
    <?php
    $_data_file =function()
    {
        ob_start();?>
        <input name="file" id="param_file"  type="file" />
        <?php return ob_get_clean();
    };
    $_macro = $this->data;
   
    $_macro['form']['rows'][] = array(
        'param'=>'file', 'name' => lang('file'),
        'type' => 'ob',
        'value' => $_data_file(),
    );

    echo macro()->page($_macro);