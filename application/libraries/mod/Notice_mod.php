<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Notice_mod extends MY_Mod
{
    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row)
    {
        $row = parent::add_info($row);

        return $row;
    }

    function get($key, $params)
    {
        $content = '';
        $row = $this->_model()->filter_get_info(['show' => 1, 'key' => $key],'name,content');
        if (!$row)
            return;

        $row = $this->add_info($row);
        foreach (array('name', 'content') as $p) {
            $row->$p = $this->bind($row->$p, $params);
        }
        return $row;
    }

    /**
     * Gan bien vao noi dung
     *
     * @param string $content
     * @param array $params
     * @return string
     */
    protected function bind($content, array $params = array())
    {
        $params = $this->make_replacement($params);

        return strtr($content, $params);
    }

    /**
     * Tao replacement
     *
     * @param array $params
     * @return array
     */
    protected function make_replacement(array $params)
    {
        // $params['site_name'] 	= module_get_setting('site', 'name');
        // $params['site_email'] 	= module_get_setting('site', 'email');

        $result = array();
        foreach ($params as $k => $v) {
            $k = '{' . $k . '}';

            $result[$k] = $v;
        }

        return $result;
    }
}