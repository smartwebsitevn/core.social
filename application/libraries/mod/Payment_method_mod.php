<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Payment_method_mod extends MY_Mod
{
    public function create_filter(array $fields, &$input = array())
    {
        $filter = parent::create_filter($fields, $input);
        /*if ($filter)
            foreach ($filter as $key=>$v) {
                if (in_array($key, ['price', 'price_gt', 'price_lt', 'price_gte', 'price_lte'])){
                    $v = currency_handle_input($filter[$key]);
                    $filter[$key]=$input[$key]=$v;
                }
            }
            */
        //pr($filter);
        return $filter;
    }

    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row)
    {
        $row = parent::add_info($row);
        return $row;
    }


}