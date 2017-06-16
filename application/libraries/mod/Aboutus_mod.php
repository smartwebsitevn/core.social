<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Aboutus_mod extends MY_Mod
{
    public function url($row)
    {
        $row->_url_view = site_url("aboutus/" . $row->seo_url.'-i'. $row->id);

        return $row;
    }
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
        $row = $this->add_info_category($row);

        return $row;
    }


    public function add_info_category($row)
    {

        if (isset($row->cat_id) && $row->cat_id) {
          $name = '';
          $cat = model('aboutus_cat')->get_info($row->cat_id);
          if ($cat) {
              $name = $cat->name;
          }
          $row->{"_cat" } = $cat;
          $row->{"_cat_name"} = $name;
        }
        return $row;
    }
}