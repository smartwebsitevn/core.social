<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class User_storage_mod extends MY_Mod
{
    public function url($row)
    {
        if (isset($row->seo_url) && $row->seo_url)
            $row->_url_view = site_url("user_storage/" . $row->seo_url . '-i' . $row->id);

        return $row;
    }


    // ============= Storage

    /**
     * Get storages
     * @param  [type] $row [description]
     * @return [type]      [description]
     *
     */
    public function get($user_id, $input = [])
    {
        $where = [];
        $where['user_id'] = $user_id;
        $where['action'] = array_get($input, 'type');
        $where['table'] = array_get($input, 'table');
        $where['table_id'] = array_get($input, 'table_id');
        return  model('user_storage')->get_info_rule($where);
    }
    function set($user_id, $input = [])
    {
        $where = [];
        $where['user_id'] = $user_id;
        $where['action'] = array_get($input, 'action');
        $where['table'] = array_get($input, 'table');
        $where['table_id'] = array_get($input, 'table_id');
        $data =$where;
        $storage =  model('user_storage')->get_info_rule($where);
        if (!$storage) {
            if(isset($input['data'])){
                $data['data'] = json_encode($input['data']);
            }
            $data['count'] = array_get($input, 'count',0);
            $data['created'] = now();

            model('user_storage')->create($where);
        } else {
            $update=false;
            if(isset($input['count'])){
                $count= array_get($input, 'count', 1);
                $data['count'] = $count + $storage->count;
                $update =true;
            }
            if(isset($input['data'])){
                $data['data'] = json_encode($input['data']);
                $update =true;
            }
            if ($update)
                model('user_storage')->update_rule($where, $data);
        }
    }

    /**
     * Set trong admin
     * Action update & insert
     * relationship with storage product
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $storages            Ids of storages
     *
     */
    public function set_multi($user_ids, $input=[])
    {
        foreach ($user_ids as $user_id) {
            $this->storage_set($user_id, $input);
        }
    }

}