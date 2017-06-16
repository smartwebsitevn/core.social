<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lesson_owner_mod extends MY_Mod
{
    function add($table_name,$table_id,$user_id,$options=[])
    {
        $where = array('table_name' =>$table_name, 'table_id' =>$table_id, 'owner_id' => $user_id);
        $owner = $this->_model()->get_info_rule($where);
        if($table_name == "product"){
            $obj = model("product")->get_info($table_id,'watch_config,watch_times,watch_expired');
            if(!$obj)
                return;

        }

        else{
            $obj = model("lesson")->get_info($table_id,'watch_config,watch_times,watch_expired ');
            if(!$obj) return;

        }

        if($obj->watch_config)// neu co thiet lap rieng
        {
            $watch_times = (int)$obj->watch_times;
            $watch_expired = (int)$obj->watch_expired*24*60*60;
        }
        else{
            $watch_times = mod("product")->setting('premium_lesson_max_watch_times');
            $watch_expired = (int)mod("product")->setting('premium_product_exprie_time')*24*60*60;

        }

        // neu co truyen thiet lap han xem
        $option_watch_expired =array_get($options,'watch_expired',null);
        if($option_watch_expired)
            $watch_expired = $option_watch_expired;


       // pr($options,0);       pr($watch_expired);

        if (!$owner) {

            if ($watch_expired > 0)
                $where['watch_expired'] = now() + $watch_expired ;
            if ($watch_times > 0)
                $where['watch_max'] = $watch_times;
            //pr($where);

            $this->_model()->create($where);
        }else{
            $data=[];
            if ($watch_expired > 0){
                if($owner->watch_expired <=now())// neu het han
                     $data['watch_expired'] = now() + $watch_expired ;
                else
                    $data['watch_expired'] = $owner->watch_expired + $watch_expired ;

            }
            if ($watch_times > 0){
                $data['watch_max'] = $owner->watch_max + $watch_times;
            }

            //pr($data);

            if($data)
                 $this->_model()->update_rule($where,$data);
        }
    }
}