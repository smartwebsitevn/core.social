<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Manufacture_mod extends MY_Mod
{
    public function url($row)
    {
        $row->_url_view = site_url("hang-san-xuat-" . $row->seo_url . '-i' . $row->id);

        return $row;
    }

    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row)
    {
        $row = parent::add_info($row);
        //$row = $this->add_info_category($row);

        return $row;
    }

    public function add_info_category($row)
    {
        if (isset($row->cat_id) && $row->cat_id) {
            $name = $cat = '';
            $cat = model('manufacture_cat')->get_info($row->cat_id);
            if ($cat) {
                $name = $cat->name;
            }

            $row->{"_cat"} = $cat;
            $row->{"_cat_name"} = $name;
        }
        return $row;
    }


}