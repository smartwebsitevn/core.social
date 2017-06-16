<?php namespace Modules\Service_cat;

class Module extends \MY_Module
{
    var $key = 'service_cat';

    /**
     * Lay config cua widget
     */
    public function widget_get_config()
    {
        $config = parent::widget_get_config();

//		$this->widget_set_config_bank($config);

        return $config;
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
     * Xu ly widget list
     */
    protected function widget_run_list($widget)
    {

        $filter = array();
        $input = [];
        $filter['show'] = 1;
        if ($widget->setting) {
            switch ($widget->setting['order']) {
                case 'news':
                    $input['order'] = [['id', 'desc']];
                    break;
                case 'az':
                    $input['order'] = [['name', 'asc'], ['id', 'desc']];
                    break;
                default:
                    $input['order'] = [['sort_order', 'asc'], ['id', 'desc']];
                    break;
            }
            if ((int)$widget->setting['total']) {
                $input['limit'] = [0, (int)$widget->setting['total']];
            }
        }
        //$input['select'] = 'id,name,brief,SEOurl';
        $list = model('service_cat')->filter_get_list($filter, $input);
        return compact('list');
    }

}
