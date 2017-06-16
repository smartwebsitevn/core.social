<?php namespace Modules\News;

class Module extends \MY_Module
{
    var $key = 'news';

    /**
     * Lay config cua widget
     */
    public function widget_get_config()
    {
        $config = parent::widget_get_config();

        $this->widget_set_config_cat($config);

        return $config;
    }

    /**
     * Gan config cat
     *
     * @param array $config
     */
    protected function widget_set_config_cat(array &$config)
    {
        foreach (model('news_cat')->get_list() as $row) {
            if (isset($config['list']['setting']['cat'])) {
                $config['list']['setting']['cat']['values'][$row->id] = $row->name;
            }

            if (isset($config['cats_news']['setting']['cat'])) {
                $config['cats_news']['setting']['cat']['values'][$row->id] = $row->name;
            }
            if (isset($config['cats_tab']['setting']['cat'])) {
                $config['cats_tab']['setting']['cat']['values'][$row->id] = $row->name;
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Xu ly widget list
     */
    protected function widget_run_list($widget)
    {

        $filter = $this->make_filter_get_list($widget->setting);
        if (!empty($widget->setting['cat'])) {
            $filter['news_cat'] = $widget->setting['cat'];
        }
        $input = $this->make_input_get_list($widget->setting);

        $list = $this->make_list($filter, $input);
        $url_more= $this->make_url_more($widget,$filter);
        return compact('list','url_more');


    }


    /**
     * Xu ly widget cat
     */
    protected function widget_run_cat($widget)
    {
        $list = mod('news_cat')->get_list();

        $url_parent = url_get_parent(array_pluck($list, '_url_view'));

        foreach ($list as $row) {
            $row->_is_active = ($row->_url_view == $url_parent);
        }

        return compact('list');
    }

    /**
     * Xu ly widget news_cat
     */
    protected function widget_run_cats_news($widget)
    {
        $filter = $this->make_filter_get_list($widget->setting);
        if (!empty($widget->setting['cat'])) {
            $filter['cat_news'] = $widget->setting['cat'];
        }
        $input = $this->make_input_get_list($widget->setting);

        $news= $this->make_list($filter, $input);

        $url_more= $this->make_url_more($widget,$filter);
        return compact('news','url_more');
    }

    /**
     * Xu ly widget news_cat
     */
    protected function widget_run_cats_tab($widget)
    {
        $filter = array_filter(['cat_news' => $widget->setting['cat']]);
        $cats=[];
        $cats_name=[];
        if($widget->setting['cat']){
            foreach($widget->setting['cat'] as $id){
                $filter = $this->make_filter_get_list($widget->setting);
                $filter['cat_news'] = $id;
                $input = $this->make_input_get_list($widget->setting);
                $cats[] = mod('news')->get_list($filter, $input);
                $cats_name[]= model('news_cat')->get_info($id,'name');


            }
        }
        return compact('cats','cats_name');
    }


    /**
     * Tao filter dung trong get list
     *
     * @param array $args
     * @return array
     */
    protected function make_filter_get_list(array $args)
    {
        $filter['show']=true;

        foreach (array( 'feature') as $f) {
            if (isset($args[$f]) && !empty($args[$f])) {
                $filter[$f] = $args[$f] == 'yes' ? 1 : 0;
            }
        }

        foreach (array( 'image') as $f) {
            if (isset($args[$f]) && !empty($args[$f])) {
                $filter[$f] = $args[$f];
            }
        }
        //pr($args,0);
        // pr($filter);
        return $filter;
    }

    /**
     * Tao input dung trong get list
     *
     * @param array $args
     * @return array
     */
    protected function make_input_get_list(array $args)
    {
        $input = array();

        if (isset($args['order'])) {
            $orders = [
                'feature' => ['news.feature', 'desc'],
                'new' => ['news.id', 'desc'],
                'view' => ['news.count_view', 'desc'],
                'random' => ['news.id', 'random'],
            ];

            $order = $args['order'];

            if (isset($orders[$order])) {
                $input['order'] = $orders[$order];
            }
        }

        if (isset($args['total'])) {
            $total = max(0, (int)$args['total']);

            $input['limit'] = [0, $total];
        }

        return $input;
    }

    /**
     * Lay danh sach
     *
     * @param array $filter
     * @param array $input
     * @return \TF\Support\Collection
     */
    protected function make_list(array $filter, array $input = [])
    {
        $list = model('news')->filter_get_list($filter, $input);
        //pr_db($filter);
        $list = $this->make_info($list);
        return $list;
    }

    /**
     * Tao thong tin cho lesson
     *
     * @param array
     * @return array
     */
    protected function make_info(array $list)
    {
        foreach ($list as $row) {
            $row = mod('news')->add_info($row);
        }
        return $list;
    }
    protected function make_url_more($widget,$filter)
    {
        // pr($widget->setting,0);
        //= su ly url more
        if(isset($widget->setting['url_more']) && $widget->setting['url_more'])
            $url_more = $widget->setting['url_more'];
        else
            $url_more = site_url('news');

        if(isset($filter['show']))
            unset($filter['show']);
        if(count($filter)>0)
            $url_more  .='?' . url_build_query($filter);

        return $url_more;
    }
}
