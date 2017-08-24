<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Gan gia tri cho input
 * @param mixed $v Gia tri
 * @param mixed $d Gia tri mac dinh
 */
function form_set_input($v, $d = '')
{
    $v = (empty($v) && $v !== '0') ? $d : $v;

    return $v;
}

/**
 * Gan selected cho option cua select
 * @param mixed $i Gia tri cua option
 * @param mixed $v Gia tri cua select
 * @param mixed $d Gia tri mac dinh cua select
 */
function form_set_select($i, $v, $d = '')
{
    if(!is_array($v) && trim($v) === '') return false;
    //echo '<>br i:'.$i.' - '.$v . ' - d:'.$d;		pr(1);
    $v = (!is_array($v)) ? array($v) : $v;
    $d = (!is_array($d)) ? array($d) : $d;
    $v = (!count($v)) ? $d : $v;
    return (in_array($i, $v)) ? 'selected="selected"' : FALSE;

}

/**
 * Gan checked cho input checkbox
 * @param mixed $i Gia tri cua input checkbox
 * @param mixed $v Gia tri can kiem tra
 * @param mixed $d Gia tri can kiem tra mac dinh
 */
function form_set_checkbox($i, $v, $d = array())
{
    if(!is_array($v) && trim($v) === '') return false;

    $v = (!is_array($v)) ? array($v) : $v;
    $d = (!is_array($d)) ? array($d) : $d;

    $v = (!count($v)) ? $d : $v;
    return (in_array($i, $v)) ? 'checked="checked"' : FALSE;
}

function form_csrf()
{
  echo '<input type="hidden" name="'.csrf_token_name().'" value="'.csrf_token_hash().'"/>';
}