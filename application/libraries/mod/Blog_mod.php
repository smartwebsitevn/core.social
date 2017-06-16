<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Blog_mod extends MY_Mod
{
    public function url($row)
    {
        $row->_url_view = site_url("blog/" . $row->seo_url.'-i'. $row->id);

        return $row;
    }
    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row)
    {
        $row = parent::add_info($row);
        $row = $this->add_info_category($row);
        $row = $this->add_info_author($row);
        return $row;
    }


    public function add_info_category_($row)
    {
        $name = '';
        $cat = model('blog_cat')->get_info($row->cat_id);
        if ($cat) {
            $name = $cat->name;
        }
        $row->{"_cat" } = $cat;
        $row->{"_cat_name"} = $name;
        return $row;
    }
    public function add_info_category($row)
    {
        $names = array();
        $list = array();
        if (isset($row->_cat_id))
            foreach ($row->_cat_id as $id) {
                $it = model('blog_cat')->get_info($id, 'id,name,image_id,image_name,seo_url');
                if ($it) {
                    $it = mod('blog_cat')->add_info_url($it);
                    $list[] = $it;
                    $names[] = $it->name;
                }
            }
        $row->{"_cat"} = $list;
        $row->{"_cat_name"} = implode(', ', $names);
        return $row;
    }
    public function add_info_author($row)
    {
        $author_id = 1;
        if (isset($row->author_id) && $row->author_id)
            $author_id = $row->author_id;

        $name = '';
        $info = model('blog_author')->get_info($author_id, 'id,name,image_id,image_name,description,seo_url');
        if ($info) {
            $info = mod('blog_author')->add_info($info);
            $name = $info->name;
        }
        $row->{"_author"} = $info;
        $row->{"_author_name"} = $name;

        return $row;
    }
}