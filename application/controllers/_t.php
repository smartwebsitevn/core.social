<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Content-Type: text/html; charset=utf-8');

class _t extends MY_Controller
{

    function api_process()
    {
        $json = file_get_contents('php://input');
        $obj =json_decode($json);
        $kq=$obj->a +$obj->b;
        echo "{kq:$kq}";
    }
    function api_product()
    {
        $limit= $this->input->get('page');
        $limit= $limit?$limit:0;
        $page_size=3;
        $input['limit'] = array($limit, $page_size);
        $input['select'] = 'name,id,image_id,image_name,seo_url';
        $tbl = 'product';
        $list = model($tbl)->get_list($input);
        /*foreach($list as $row){
            $row =mod($tbl)->add_info_image($row);
        }*/
        echo json_encode($list);
    }
    function carbon()
    {
        $max = \Carbon\Carbon::now();
        $i = 1;
        for ($m = $max->month; $m >= 1; $m--) {
            echo '<br>-m='.$max->subMonth(1);
        }
    }

    //=================
    function update_db()
    {
        $this->_update_user();
       // $this->_update_product();
       // $this->_update_product_type();
       // $this->_update_files();

    }

    function _update_user()
    {
        //$post_total = model('product')->filter_get_total(['user_id' => 2]);
        //pr_db($post_total);
        $tbl = 'user';
        $list = model($tbl)->get_list();
        foreach ($list as $row) {
            $post_total = model('product')->filter_get_total(['user_id' => $row->id]);
            $post_is_publish = model('product')->filter_get_total(['user_id' => $row->id, 'status' => 1]);
            $post_is_draft = model('product')->filter_get_total(['user_id' => $row->id, 'is_draft' => 1]);
            $post_is_deleted = model('product')->filter_get_total(['user_id' => $row->id, 'deleted' => 1]);

            $follow_total = model('user_storage')->filter_get_total(['user_id' => $row->id,'action'=>'subscribe']);
            $follow_by_total = model('user_storage')->filter_get_total(['table_id' => $row->id,'action'=>'subscribe']);
            model($tbl)->update($row->id,
                [
                    'post_total' => $post_total,
                    'post_is_publish' => $post_is_publish,
                    'post_is_draft' => $post_is_draft,
                    'post_is_deleted' => $post_is_deleted,

                    'follow_total' => $follow_total,
                    'follow_by_total' => $follow_by_total,
                ]
            );
            echo '<br>--';
            pr_db(0, 0);
        }
    }


    function _update_product()
    {
        $tbl = 'product';
        $list = model($tbl)->get_list();
        foreach ($list as $i) {
           // $name = "Sản phẩm Demo " . $i->id;
           // $desc = 'Nội dung đang được cập nhập...';
            model($tbl)->update($i->id,
                [
                    'point_total' => $i->point_total +$i->point_fake,
                    'point_fake'=>0,
                   /* 'name' => $name,
                    'seo_url' => convert_vi_to_en($name),
                    'brief' => $desc,
                    'description' => $desc,*/
                ]
            );
        }
    }
    function _update_product_type()
    {
        $tbl = 'product';
        $list = model($tbl)->get_list();
        foreach ($list as $i) {
            $types = model('type_table')->filter_get_list(['table'=>'product','table_id'=>$i->id]);
            if($types){
                $type_ids = [];
                $type_item_ids = [];
                foreach ($types as $row) {
                    $type_ids[] = $row->type_id;;
                    $type_item_ids[] = $row->type_item_id;;
                }
                $type_ids = array_unique($type_ids);
                $type_item_ids = array_unique($type_item_ids);
                if($type_ids && $type_item_ids){
                    model($tbl)->update($i->id,
                        [
                            'type_id' =>implode(',',$type_ids),
                            'type_item_id'=>implode(',',$type_item_ids),
                        ]
                    );
                    echo '<br>';pr_db(0,0);
                }

            }

        }
    }
    function _update_files()
    {
        $tbl = 'file';
        $list = model($tbl)->get_list();
       // pr_db($list);
        foreach ($list as $i) {
           $file= file_get_info($i->id);
           $info=get_file_info($file->_path);
           // pr($info);
            model($tbl)->update_field($i->id, 'size', $info['size']);
            echo '<br>-';pr_db(0,0);
        }
    }
    function _update_comment()
    {
        $tbl = 'comment';
        $list = model($tbl)->get_list();
        foreach ($list as $row) {
            $user = model('user')->get_info($row->user_id);
            if (user_is_manager($user) || user_is_active($user)) {
                $data['featured'] = 1;
                model($tbl)->update($row->id,
                    [
                        'featured' => 1,
                    ]);
            }

            echo '<br>--';
            pr_db(0, 0);
        }
    }

    function _update_page()
    {
        $tbl = 'page';
        $list = model($tbl)->get_list();
        foreach ($list as $i) {
            model($tbl)->update_field($i->id, 'url', convert_vi_to_en($i->title));
        }
    }

    function _update_news()
    {
        $tbl = 'news';
        $list = model($tbl)->get_list();
        foreach ($list as $i) {
            model($tbl)->update_field($i->id, 'url', convert_vi_to_en($i->title));
        }
    }


    function _update_lang()
    {
        $tbl = 'lang_phrase';
        $list = model($tbl)->get_list();
        //$list = model($tbl)->get_list_rule(["la"=>""]);
        //pr($list);
        foreach ($list as $i) {
            //	pr($i);
            //model($tbl)->update($i->id,["en"=>$i->vi,"la"=>$i->vi]);
            // if ($i->la == "")
            // model($tbl)->update($i->id, ["la" => $i->vi]);
            if ($i->en == "") {
                model($tbl)->update($i->id, ["en" => $i->vi]);
                // pr_db();

            }
        }
    }


    function glink_token()
    {
        pr(lib('glink'));
    }

    function glink()
    {
        // $link ='https://picasaweb.google.com/107381407253905053463/DungNhanTroChoiMaQuai?authkey=Gv1sRgCPWZvt73l8u8jwE#6220341408683541154';
        $link = 'https://www.youtube.com/watch?v=m4KEhflNFEo'; //private cuong
        //$link ='https://www.youtube.com/watch?v=8rODHN_IR1c';
        pr(lib('glink')->get_link($link));
    }

    function time()
    {
        pr(get_date('1293814800', 'full'), 0);
        pr(get_date('1325350799', 'full'));
    }

    function _login()
    {
        $this->load->helper('admin');
        $this->load->model('admin_model');
        admin_login_set('1');
    }

    function vtc()
    {
        $balance = App\Product\Provider\Vtc\Service::test();
        pr($balance);

        //lay danh sach cac bank tu cong thanh toan
        $payment_default = 'baokimpro';
        $this->data['banks'] = $this->payment->{$payment_default}->get_bank_list();
        pr();

        $provider = 'vtc';
        $api_status = t('payment_card')->$provider->test();
        pr($api_status);
    }

    function products_vnpt_update()
    {
        $input = array();
        $input['select'] = 'id, keys_connection';
        $list = model('product')->get_list($input);
        foreach ($list as $row) {
            $data = array();
            $data['keys_connection'] = str_replace('VnptEpay', 'Vnpt', $row->keys_connection);
            model('product')->update($row->id, $data);
        }
    }

    function test_xss()
    {
        $a = '<html>a</html>';
        $a = strip_tags($a);
        pr($a);
    }

    function test()
    {
        $url = 'https://pay.vtc.vn/WS/GoodsPaygate.asmx?wsdl&op=RequestTransaction';
        $result = '';
        $content = lib('curl')->get($url, array(), $result);
        pr($result);
    }

    function email()
    {
        mod('email')->send('user_forgot_password', 'support@nencer.net,sales@nencer.net,batv.sne@gmail.com', [
            'order_id' => 1,
        ]);
        echo $error_code = mod('email')->error_code;
        echo $total_email = mod('email')->total_email;
        echo $total_email_send = mod('email')->total_email_send;
    }


    function lesson_owner()
    {
        mod('lesson_owner')->add("product", 5, 1);

    }

    function bonus()
    {


        $product = mod('product')->get_info(3);
        $invoice_order = App\Invoice\Model\InvoiceOrderModel::find(3);
        mod("product")->bonus($invoice_order, $product);
    }

    function combo()
    {
        $invoice_order = App\Invoice\Model\InvoiceOrderModel::find(11);
        $invoice_order_com = new \App\Invoice\InvoiceService\ComboOrder();
        $invoice_order_com->active($invoice_order);
    }


    function aff()
    {
        //cong tien cho nguoi gioi thieu
        $invoice_order = App\Invoice\Model\InvoiceOrderModel::find(1);
        $invoice_order_aff = new \App\Invoice\InvoiceService\Affiliate();
        $invoice_order_aff->active($invoice_order);
    }

    function cart_price()
    {
        $id = 10;
        $qty = 1;

        $options = [];
        $addons = [2];

        $product = model('product')->get_info($id);

        $product = mod('product')->add_info_price($product);
        $product = mod('product')->add_info_vat($product);
        $product = mod('product')->add_info_image($product);
        $product = mod('product')->url($product);
        // Insert product
        $product = mod('product')->add_info_price_cart($product, $qty, $options, $addons);
        $data = array(
            'id' => $product->id,
            'name' => $product->name,
            'thumb' => $product->image->url_thumb,
            'qty' => $qty,
            'price' => $product->_price_amount,
            'price_html' => $product->_price,
            'url_view' => $product->_url_view,
            'tax_class' => $product->_tax_rate_ids,
            'additional_amount' => $product->_additional_amount,
            'option_html' => $product->_option_html,
        );
        pr($data);
    }

    function notice()
    {
        $notice = mod('notice')->get('product_order_success', array(
            'title' => 1,
            'id' => 1,
        ));
        pr($notice);
    }

    function user_notice()
    {
        mod('user_notice')->send(1, 'Test', 'Noi dung thong bao Test');
    }

    function user_storage()
    {
        $input = ['table' => 'user', 'table_id' => '1', 'action' => 'follow', 'count' => 5];
        mod('user_storage')->set(1, $input);
    }
}
