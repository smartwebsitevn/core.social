<?php namespace Modules\Site;
use App\User\UserFactory;
class Module extends \MY_Module
{
    public $key = 'site';
    /**
     * Lay setting cua module
     */
    public function widget_setting($module)
    {
        if ($module->widget =='html')
            $module->setting['content'] = handle_content($module->setting['content'], 'output');

    }
    /**
     * Ham duoc goi truoc khi luu thong tin module
     * @param object $module Thong tin module
     */
    public function widget_setting_save_pre($module)
    {
        if ($module->widget =='html')
            $module->setting['content'] = handle_content($module->setting['content'], 'input');
        //pr($module->setting);
    }
    /**
     * Lay config cua widget
     */
    public function widget_get_config()
    {
        $config = parent::widget_get_config();

        $this->widget_set_config_menu($config);

        $this->widget_set_config_ads_location($config);
        $this->widget_set_config_ads_banner($config);
        $this->widget_set_config_slider($config);

        return $config;
    }
    /**
     * Gan config menu
     *
     * @param array $config
     */
    protected function widget_set_config_menu(array &$config)
    {
        $menus = model('menu')->get_list();
        $menus = array_pluck($menus, 'name', 'key');

        foreach (array('menu', 'header','footer','panel','user_panel') as $type) {
            foreach (array('', 1,2,3,4,5) as $k) {
                if (isset($config[$type]['setting']['menu' . $k])) {
                    $config[$type]['setting']['menu'. $k]['values'] = $menus;
                }
            }
        }
    }
    /**
     * Gan config slider
     *
     * @param array $config
     */
    protected function widget_set_config_slider(array &$config)
    {
        foreach (model('slider')->get_list() as $row) {
            $config['slider']['setting']['slider']['values'][$row->key] = $row->name;
        }
    }
    /**
     * Gan config ads_location
     *
     * @param array $config
     */
    protected function widget_set_config_ads_location(array &$config)
    {
        if (isset($config['ads_location']['setting']['location_id'])) {
            $list = model('ads_location')->get_list();
            $config['ads_location']['setting']['location_id']['values'] = array_pluck(
                $list,
                'name',
                'id'
            );
        }
    }
    /**
     * Gan config ads_banner
     *
     * @param array $config
     */
    protected function widget_set_config_ads_banner(array &$config)
    {
        if (isset($config['ads_banner']['setting']['banner_id'])) {
            $list = model('ads_banner')->get_list();
            $config['ads_banner']['setting']['banner_id']['values'] = array_pluck(
                $list,
                'name',
                'id'
            );
        }
    }
    // --------------------------------------------------------------------
    /**
     * Lay thong tin de hien thi widget
     *
     * @param object $widget Thong tin widget
     * @return array
     */
    public function widget_run($widget)
    {
        $data = array();

        $method = "widget_run_{$widget->widget}";
        if (method_exists($this, $method)) {
            $data = $this->{$method}($widget);
        }

        return $data;
    }
    /**
     * Xu ly widget header
     */
    protected function widget_run_header($widget)
    {
        $is_login = UserFactory::auth()->logged();

        $user = UserFactory::auth()->user();

        $menu = mod('menu')->get($widget->setting['menu']) ;

        return compact('is_login', 'user', 'menu');
    }
    /**
     * Xu ly widget footer
     */
    protected function widget_run_footer($widget)
    {
        $menu1 = mod('menu')->get($widget->setting['menu1']) ;
        $menu1_name= model('menu')->get_info_rule(["key"=>$widget->setting['menu1']]);

        $menu2 = mod('menu')->get($widget->setting['menu2']) ;
        $menu2_name= model('menu')->get_info_rule(["key"=>$widget->setting['menu2']]);

        $menu3 = mod('menu')->get($widget->setting['menu3']) ;
        $menu3_name= model('menu')->get_info_rule(["key"=>$widget->setting['menu3']]);

        /*   $menu4 = mod('menu')->get($widget->setting['menu4']) ;
         $menu4_name= model('menu')->get_info_rule(["key"=>$widget->setting['menu4']]);*/


       /* $image1 = file_get_image_from_name($widget->setting['image1']);
        $image2 = file_get_image_from_name($widget->setting['image2']);
        $image3 = file_get_image_from_name($widget->setting['image3']);*/
        return compact(
           // 'image1', 'image2','image3',
            'menu1', 'menu2','menu3',// 'menu4',
            'menu1_name' ,'menu2_name','menu3_name'//,'menu4_name'
        );
    }

    /**
     * Xu ly widget menu
     */
    protected function widget_run_menu($widget)
    {
        $items = t('mod')->menu->get($widget->setting['menu']);
        return compact('items');
    }

    /**
     * Xu ly widget slider
     */
    protected function widget_run_slider($widget)
    {
        $items = t('model')->slider_item->get($widget->setting['slider']);
        return compact('items');
    }


    /**
     * Xu ly widget lang
     */
    protected function widget_run_lang($widget)
    {
        $list = lang_get_list();
        $list = array_where($list, function ($i, $row) {
            $row->_url_change = site_url($row->directory);

            return ($row->status);
        });
        return compact('list');
    }

    /**
     * Xu ly widget list ads_location
     */
    protected function widget_run_ads_location($widget)
    {
        $location_id = $widget->setting['location_id'];
        $banners = model('ads_banner')->filter_get_list(array('ads_location_id' => $location_id, 'show' => 1));
        if ($banners) {
            foreach ($banners as $banner) {
                $banner = mod('ads_banner')->add_info($banner);
            }
        }
        return compact('banners');
    }

    /**
     * Xu ly widget list ads_banner
     */
    protected function widget_run_question_answer($widget)
    {
        // Tai cac file thanh phan

        $input = $filter = $list = array();
        $input['limit'] = array('0', $widget->setting['total']);
        $filter['status'] = config('verify_yes', 'main');
        $_list = model("question_answer")->filter_get_list($filter, $input);
        //pr_db();
        $list=[];
        foreach ($_list as $row) {
            if (!$row->user_id)
                continue;
            $user = mod("user")->get_info($row->user_id);
            if (!$user)
                continue;
            $image_name = (isset($user->avatar)) ? $user->avatar : '';
            $row->user_avatar = file_get_image_from_name($image_name, public_url('img/user_no_image.png'));
            //$row->user = $user;
            //$row->_created = get_date($row->created);
            $row->_created_time = get_date($row->created, 'time');
            $list[]=$row;
        }
        return compact('list');
    }

    /**
     * Xu ly widget list ads_banner
     */
    protected
    function widget_run_ads_banner_($widget)
    {
        $banner_id = $widget->setting['banner_id'];
        $banner = model('ads_banner')->get_info_rule(array('id' => $banner_id, 'show' => 1));
        $banner = mod('ads_banner')->add_info($banner);
        return compact('banner');
    }
    function widget_run_ads_banner($widget)
    {
        $banner_id = $widget->setting['banner_id'];
        $banners = model('ads_banner')->filter_get_list(array('id' => $banner_id, 'show' => 1));
        foreach($banners as $banner)
            $banner = mod('ads_banner')->add_info($banner);
        return compact('banners');
    }
    protected function widget_run_html($widget)
    {
        $widget->setting['content'] = handle_content($widget->setting['content'], 'output');
        return compact('widget');
    }
    /**
     * Xu ly widget tran_form_tab
     */
    protected function widget_run_tab($widget)
    {
        $widgets = t('module')->get_widgets('tab');
        return compact('widgets');
    }
}
