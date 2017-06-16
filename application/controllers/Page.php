<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MY_Controller
{
    /**
     * Trang thong tin
     */
    public function index()
    {
        // Tai cac file thanh phan
        $this->load->model('page_model');
        // Lay thong tin
        $id = $this->uri->rsegment(3);
        $id = (!is_numeric($id)) ? 0 : $id;
        $this->_page($id);
        $tmpl='index';
        switch($id){
            case 5:
                $this->_faq();
                $tmpl='faq';
                break;
            case 2:
                $this->aboutus();
                $tmpl='aboutus';
                break;
        }
        // Hien thi view
        $this->_display($tmpl);

    }
    public function _faq()
    {
        // lay faq
        $cats= model('faq_cat')->get_list_rule(array('status'=>1));
        if($cats)
            foreach($cats as $it){
                $it->faqs= model('faq')->get_list_rule(array( 'cat_id'=>$it->id,'status'=>1));
                if($it->faqs){
                    foreach($it->faqs as $faq){
                        $faq->answer= handle_content($faq->answer, 'output');
                    }
                }

            }
        $this->data['cats']=$cats;
        // Hien thi view
    }

    public function aboutus()
    {
        // lay quan quan tri
        $list= model('aboutus')->get_list_rule(array('status'=>1));
        if($list)
            foreach($list as $it){
                $it= mod('aboutus')->add_info($it);
            }
        $this->data['list']=$list;
        // Hien thi view
    }


    public function _page($id)
    {
        // Tai cac file thanh phan
        $this->load->model('page_model');

        // Lay thong tin
        $page = $this->page_model->get_info_rule(array('id' => $id, 'status' => 1));
        if (!$page) {
            redirect();
        }
        // Xu ly thong tin cua info
        $page = $this->_mod()->add_info($page);
        $page->description = (!$page->description) ? $page->title : $page->description;
        $page->keywords = (!$page->keywords) ? $page->title : $page->keywords;
        $this->data['page'] = $page;
        //pr($page);
        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array($page->_url_view, word_limiter($page->title, 10), $page->title);
        page_info('breadcrumbs', $breadcrumbs);

        // Gan thong tin page
        page_info('title', $page->title);
        page_info('description', $page->description);
        page_info('keywords', $page->keywords);

    }
}
