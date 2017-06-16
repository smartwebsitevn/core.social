<?php

if( isset($info->_option) && $info->_option )
{
    ?>
    <div class="info-prod-options">
        Gia tuy chon:
        <?php
        foreach ($info->_option as $selOption)
        {
            $option = objectExtract( array( 'id' => $selOption->option_id ), $options, true );
            ?>
            <div class="info-prod info-options">
                <span class="title"><strong><?php echo $option->name ?> <?php echo $selOption->required ? '*' : '' ?></strong></span>
                <?php
                if( $option->type == 'checkbox' )
                {
                    foreach( $info->_option_value as $selValue )
                    {
                        if( $value = objectExtract( array( 'id' => $selValue->option_value_id, 'option_id' => $option->id ), $option_values, true ) )
                        {
                            $additional = '';
                            if( $selValue->price )
                                $additional = '( ' . $selValue->price_prefix . ' ' . dinhdangtien($selValue->price) . ' )';
                            ?>
                            <label>
                                <input type="checkbox" name="option_<?php echo $selOption->id; ?>" value="<?php echo $selValue->id; ?>" /> <?php echo $value->name; ?> <?php echo $additional ?>
                            </label>
                            <?php
                            unset($value);
                        }

                    }

                }
                else if( $option->type == 'radio' )
                {
                    foreach( $info->_option_value as $selValue )
                    {
                        if( $value = objectExtract( array( 'id' => $selValue->option_value_id, 'option_id' => $option->id ), $option_values, true ) )
                        {
                            $additional = '';
                            if( $selValue->price )
                                $additional = '( ' . $selValue->price_prefix . ' ' . dinhdangtien($selValue->price) . ' )';

                            $value = mod('option')->add_info($value);
                            if( isset( $value->_image ) && $value->_image->url != public_url('img/no_image.png') )
                            {
                                ?>

                                <label class="radio-img">
                                    <input type="radio" name="option_<?php echo $selOption->id ?>" value="<?php echo $selValue->id; ?>" />
                                    <img src="<?php echo $value->_image->url ?>" width="28px" data-toggle="tooltip" title="<?php echo $value->name; ?> <?php echo $additional ?>" />
                                </label>

                                <?php
                            }
                            else
                            {
                                ?>
                                <label>
                                    <input type="radio" name="option_<?php echo $selOption->id ?>" value="<?php echo $selValue->id; ?>" /> <?php echo $value->name; ?> <?php echo $additional ?>
                                </label>
                                <?php
                            }

                            unset($value);
                        }

                    }

                }
                else if( $option->type == 'textarea' )
                {
                    ?>
                    <p>
                        <textarea class="form-control" placeholder="<?php echo $selOption->value ?>" name="option_<?php echo $selOption->id ?>"></textarea>
                    </p>
                    <?php
                }
                else if( $option->type == 'text' )
                {
                    ?>
                    <p>
                        <input typr="text" class="form-control" placeholder="<?php echo $selOption->value ?>" name="option_<?php echo $selOption->id ?>" />
                    </p>
                    <?php
                }
                else if( $option->type == 'select' )
                {
                    ?>
                    <p>
                        <select class="form-control" name="option_<?php echo $selOption->id ?>">
                            <?php
                            foreach( $info->_option_value as $selValue )
                            {
                                if( $value = objectExtract( array( 'id' => $selValue->option_value_id, 'option_id' => $option->id ), $option_values, true ) )
                                {
                                    $additional = '';
                                    if( $selValue->price )
                                        $additional = '( ' . $selValue->price_prefix . ' ' . dinhdangtien($selValue->price)  . ' )';
                                    ?>
                                    <option value="<?php echo $selValue->id ?>"> <?php echo $value->name; ?> <?php echo $additional ?> </option>
                                    <?php
                                    unset($value);
                                }

                            }

                            ?>

                        </select>
                    </p>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}


?>