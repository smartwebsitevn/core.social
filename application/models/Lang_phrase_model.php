<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lang_phrase_model extends MY_Model
{

    var $table = 'lang_phrase';
    var $order = array('key', 'asc');


    /**
     * Filter handle
     */
    function _filter_get_where(array $filter)
    {
        $where = parent::_filter_get_where($filter);

        foreach (array('lang', 'file',) as $p) {
            $f = (in_array($p, array('lang', 'file'))) ? $p . '_id' : $p;
            $f =$this->table. '.' . $f;
            $m = (in_array($p, array())) ? 'range' : '';
            $this->_filter_set_where($filter, $p, $f, $where, $m);
        }
        if (isset($filter['translate_empty']) && $filter['translate_empty']) {
            // check lang
            $lang = model("lang")->check_exits(["directory"=>$filter['translate_empty']]);
            if($lang)
                $where[$this->table.'.'.$filter['translate_empty']] = "";
        }
        if (isset($filter['file_name'])) {
            $file= model('lang_file')->get_info_rule(array('file'=>$filter['file_name']));
            if($file)
            $where[$this->table.'.file_id'] = $file->id;
        }
        if (isset($filter['key'])) {
            $this->search($this->table, 'key', $filter['key']);
        }
        if (isset($filter['value'])) {
            $this->search($this->table, 'value', $filter['value']);
        }
        
        if (isset($filter['translate'])) {
           $langs = model('lang')->get_list_active();
           foreach ($langs as $key => $lang)
           {
               $this->db->or_like($this->table . '.' . $lang->directory, $filter['translate']);
           }

           //$this->search($this->table, 'translate', $filter['translate']);
        }

        return $where;
    }
    /**
     * Tao filter tu input
     */
    function filter_create($fields, &$input = array())
    {
        // Lay gia tri cua filter dau vao
        $input = array();
        foreach ($fields as $f)
        {
            $v = $this->input->get($f);
            $v = security_handle_input($v, in_array($f, array()));

            $input[$f] = $v;
        }

        if ( ! empty($input['id']))
        {
            foreach ($input as $f => $v)
            {
                $input[$f] = ($f != 'id') ? '' : $v;
            }
        }


        // Tao bien filter
        $filter = array();
        $query 	= url_build_query($input, TRUE);

        foreach ($query as $f => $v)
        {
            switch ($f)
            {
                case 'created':
                {
                    $created_to = $input['created_to'];
                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
                    $v = get_time_between($v);
                    $v = ( ! $v) ? NULL : $v;
                    break;
                }
            }

            if ($v === NULL) continue;

            $filter[$f] = $v;
        }

        
        return $filter;
    }

    /**
     * Tim kiem du lieu
     */
    function _search($field, $key)
    {
        $this->db->like($this->table . '.' . $field, $key);

    }


    /*function set_value($lang_id, $file_id, $key, $value)
    {
        $this->set($lang_id, $file_id, $key, $value);
    }

    function get_value($lang_id, $file_id, $key)
    {
        $result = array();
        $list = $this->get($lang_id, $file_id, $key);
        if ($list) {
            foreach ($list as $key => $row) {
                $result[$key] = $row[0];
            }
        }
        return $result;
    }


    function set_translate($lang_id, $file_id, $key, $value)
    {
        $this->set($lang_id, $file_id, $key, null, $value,true);
    }

    function get_translate($lang_id, $file_id, $key)
    {
        $result = array();
        $list = $this->get($lang_id, $file_id, $key);
        if ($list) {
            foreach ($list as $key => $row) {
                $result[$key] = $row[1];
            }
        }
        return $result;
    }*/

    function get_translates($lang_id, $file_id)
    {
        $result = array();
        $list = $this->get($lang_id, $file_id);
        if ($list) {
            foreach ($list as $key => $row) {
                $result[$key] = $row[1];
            }
        }
        return $result;
    }
    
    /**
     * Luu lang tu file vao db
     */
    function set($language_directory, $file_id, $key, $value = null, $translate = null,$translate_override=false)
    {

        if (is_null($value) && is_null($translate))
            return;

        // Lay ban dich tuong ung
        $where = array();
        $where['file_id'] = $file_id;
        $where['key'] = $key;

        $info = $this->get_info_rule($where);

        $_data = array();

        if ($value)
            $_data['value'] = handle_content($value, 'input');
        if($translate ){
            if ( $translate_override) { //  neu dc phep ghi de ban dich
                $_data[$language_directory] = handle_content($translate, 'input');
            }
            elseif(empty($info->translate)) {// neu khong dc phep ghi de ban dich, thi phai kiem tra ban dich da co noi dung chua
                $_data[$language_directory] = handle_content($translate, 'input');
            }
        }

        // Neu da ton tai thi cap nhat
        if ($info) {

            $this->update($info->id, $_data);
        } // Neu chua ton tai thi them moi
        else {
            $_data['file_id'] = $file_id;
            $_data['key'] = $key;
            $this->create($_data);
        }
    }

    /**
     * Lay cac ban dich  va goc cua row
     */
    function get($lang_id, $file_id, $key = NULL)
    {
        // Tao filter
        $filter = array();
        $filter['lang'] = $lang_id;
        $filter['file'] = $file_id;
    
        if ($key !== NULL) {
            $filter['key'] = $key;
        }
        // pr($filter,false);
        $list = $this->filter_get_list($filter);
        //echo  $this->db->last_query();
        // Xu ly list
        $result = array();
        foreach ($list as $row) {
            if ($row->value)
                $row->value = (handle_content($row->value, 'output'));
            if ($row->translate)
                $row->translate = (handle_content($row->translate, 'output'));
            $result[$row->key] = array($row->value, $row->translate);
        }
    
        return $result;
    }
    
    /**
     * Luu lang tu file vao db
     */
    function lang_set($lang_directory, $file_id, $key, $value = null, $translate = null,$translate_override=false)
    {
    
        if (is_null($value) && is_null($translate))
            return;
    
        // Lay ban dich tuong ung
        $where = array();
        //$where['lang_id'] = $lang_id;
        $where['file_id'] = $file_id;
        $where['key'] = $key;
    
        $info = $this->get_info_rule($where);
    
        $_data = array();
    
        if ($value)
            $_data['value'] = handle_content($value, 'input');
    
        if($translate ){
            if ( $translate_override) //  neu dc phep ghi de ban dich
                $_data[$lang_directory] = handle_content($translate, 'input');
            elseif(empty($info->{$lang_directory}))// neu khong dc phep ghi de ban dich, thi phai kiem tra ban dich da co noi dung chua
            $_data[$lang_directory] = handle_content($translate, 'input');
        }
    
        // Neu da ton tai thi cap nhat
        if ($info) {
    
            $this->update($info->id, $_data);
        } // Neu chua ton tai thi them moi
        else {
            $_data[$lang_directory] = $lang_directory;
            $_data['file_id'] = $file_id;
            $_data['key'] = $key;
            $this->create($_data);
        }
    }
    


    function lang_get_translates($lang_directory, $file_id)
    {
        $result = array();
        $list = $this->lang_get($lang_directory, $file_id);
        if ($list) {
            foreach ($list as $key => $row) {
                $result[$key] = $row[1];
            }
        }
        return $result;
    }
    
    
    /**
     * Lay cac ban dich  va goc cua row
     */
    function lang_get($lang_directory, $file_id, $key = NULL)
    {
        // Tao filter
        $filter = array();
        //$filter['lang'] = $lang_id;
        $filter['file'] = $file_id;
    
        if ($key !== NULL) {
            $filter['key'] = $key;
        }
        // pr($filter,false);
        $list = $this->filter_get_list($filter);
        //echo  $this->db->last_query();
        // Xu ly list
        $result = array();
        foreach ($list as $row) {
            if ($row->value)
                $row->value = (handle_content($row->value, 'output'));
            if ($row->{$lang_directory})
                $row->{$lang_directory} = (handle_content($row->{$lang_directory}, 'output'));
            $result[$row->key] = array($row->value, $row->{$lang_directory});
        }
    
        return $result;
    }
    
  

}