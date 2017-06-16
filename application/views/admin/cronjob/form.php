<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;
$_data_setting =function($info){
    $info =$info['setting'];
    ob_start();   ?>
    <div id="cronjob_content">
        <div class="row">
            <div class="col-md-6">
                <label style="color:#0b46ab;font-size:15px"><?php echo lang('minute') ?></label><br>
                    <select multiple="" size="14" id="minute" name="minute[]" class="ui_select">
                        <option></option>
                        <?php for($i=0;$i<=11;$i++):?>
                            <option value="<?php echo $i?>"
                                <?php
                                if($info['minute']==1){echo "selected";}else{
                                    echo (is_array($info['minute']) && in_array($i, $info['minute']) ) ? "selected" : "";
                                }
                                ?>
                                ><?php echo $i?></option>
                        <?php endfor;?>
                    </select>
                    <select multiple="" size="14" id="minute" name="minute[]" class="ui_select">
                        <option></option>
                        <?php for($i=12;$i<=23;$i++):?>
                            <option value="<?php echo $i?>"
                                <?php
                                if($info['minute']==1){echo "selected";}else{
                                    echo (is_array($info['minute']) && in_array($i, $info['minute']) ) ? "selected" : "";
                                }
                                ?>
                                ><?php echo $i?></option>
                        <?php endfor;?>
                    </select>
                    <select multiple="" size="14" id="minute" name="minute[]" class="ui_select">
                        <option></option>
                        <?php for($i=24;$i<=35;$i++):?>
                            <option value="<?php echo $i?>"
                                <?php
                                if($info['minute']==1){echo "selected";}else{
                                    echo (is_array($info['minute']) && in_array($i, $info['minute']) ) ? "selected" : "";
                                }
                                ?>
                                ><?php echo $i?></option>
                        <?php endfor;?>
                    </select>
                    <select multiple="" size="14" id="minute" name="minute[]" class="ui_select">
                    <option></option>
                    <?php for($i=36;$i<=47;$i++):?>
                        <option value="<?php echo $i?>"
                            <?php
                            if($info['minute']==1){echo "selected";}else{
                                echo (is_array($info['minute']) && in_array($i, $info['minute']) ) ? "selected" : "";
                            }
                            ?>
                            ><?php echo $i?></option>
                    <?php endfor;?>
                </select>
                    <select multiple="" size="14" id="minute" name="minute[]" class="ui_select">
                    <option></option>
                    <?php for($i=48;$i<=59;$i++):?>
                        <option value="<?php echo $i?>"
                            <?php
                            if($info['minute']==1){echo "selected";}else{
                                echo (is_array($info['minute']) && in_array($i, $info['minute']) ) ? "selected" : "";
                            }
                            ?>
                            ><?php echo $i?></option>
                    <?php endfor;?>
                </select>
            </div>
            <div class="col-md-3">
                <label  style="color:#0b46ab;font-size:15px"><?php echo  lang('hour') ?></label><br>
                            <select multiple="" size="14" id="hour" name="hour[]" class="ui_select">
                                <option></option>
                                <?php for($i=0;$i<=11;$i++):?>
                                    <option value="<?php echo $i?>"
                                        <?php
                                        if($info['hour']==1){echo "selected";}else{
                                            echo (is_array($info['hour']) && in_array($i, $info['hour']) ) ? "selected" : "";
                                        }
                                        ?>
                                        ><?php echo $i?></option>
                                <?php endfor;?>
                            </select>
                            <select multiple="" size="14" id="hour" name="hour[]" class="ui_select">
                                <option></option>
                                <?php for($i=12;$i<=23;$i++):?>
                                    <option value="<?php echo $i?>"
                                        <?php
                                        if($info['hour']==1){echo "selected";}else{
                                            echo (is_array($info['hour']) && in_array($i, $info['hour']) ) ? "selected" : "";
                                        }
                                        ?>
                                        ><?php echo $i?></option>
                                <?php endfor;?>
                            </select>


            </div>
            <div class="col-md-3">
                <label  style="color:#0b46ab;font-size:15px"><?php echo  lang('day') ?></label><br>

                            <select multiple="" size="14" id="day" name="day[]" class="ui_select">
                                <option></option>
                                <?php for($i=1;$i<=12;$i++):?>
                                    <option value="<?php echo $i?>"
                                        <?php
                                        if($info['day']==1){echo "selected";}else{
                                            echo (is_array($info['day']) && in_array($i, $info['day']) ) ? "selected" : "";
                                        }
                                        ?>
                                        ><?php echo $i?></option>
                                <?php endfor;?>
                            </select>
                            <select multiple="" size="14" id="day" name="day[]" class="ui_select">
                                <option></option>
                                <?php for($i=13;$i<=24;$i++):?>
                                    <option value="<?php echo $i?>"
                                        <?php
                                        if($info['day']==1){echo "selected";}else{
                                            echo (is_array($info['day']) && in_array($i, $info['day']) ) ? "selected" : "";
                                        }
                                        ?>
                                        ><?php echo $i?></option>
                                <?php endfor;?>
                            </select>
                            <select multiple="" size="14" id="day" name="day[]" class="ui_select">
                                <option></option>
                                <?php for($i=25;$i<=31;$i++):?>
                                    <option value="<?php echo $i?>"
                                        <?php
                                        if($info['day']==1){echo "selected";}else{
                                            echo (is_array($info['day']) && in_array($i, $info['day']) ) ? "selected" : "";
                                        }
                                        ?>
                                        ><?php echo $i?></option>
                                <?php endfor;?>
                            </select>
            </div>
        </div>
    </div>
<?php return ob_get_clean();
};
$_macro['form']['rows'][] = array(
    'param' => 'title',
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'url',
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'status',
    'type' => 'bool_status',
);

$_macro['form']['rows'][] = array(
    'param' => 'setting',   'type' => 'ob',
    'value'=>$_data_setting($info),
);


$_macro['form']['rows'][] = array(
    'param' => 'desc',
    'type' => 'html',
);
echo macro()->page($_macro);
?>
<script type="text/javascript">
    (function($)
    {
        $(document).ready(function()
        {
            var main = $('#form');


        });
    })(jQuery);
</script>




