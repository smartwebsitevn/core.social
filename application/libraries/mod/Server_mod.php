<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Server_mod extends MY_Mod
{
    /**
     * Them cac thong tin phu vao thong tin cua movie
     */
    function add_info($row)
    {
        $row = parent::add_info($row);
        if (isset($row->setting))
        {
            $row->setting =json_decode($row->setting);
        }

        $row = $this->url($row);
        //pr($row);
        return $row;
    }
    function get_default()
    {
        return $this->_model()->get_info_rule(["default"=>1]);
    }
}