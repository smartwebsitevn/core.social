<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Content-Type: text/html; charset=utf-8');

class _t extends MY_Controller
{

    function _update_db()
    {
        $tbl = 'movie';
        $info = model($tbl)->get_info(23);

        $list = model($tbl)->get_list();
        foreach ($list as $i) {
            model($tbl)->update_field($i->id, 'demo', $info->demo);
        }
    }

    function _update_product()
    {
        $tbl = 'product';
        $list = model($tbl)->get_list();
        foreach ($list as $i) {
            $name = "Sản phẩm Demo " . $i->id;
            $desc = 'Nội dung đang được cập nhập...';
            model($tbl)->update($i->id,
                ['name' => $name,
                    'seo_url' => convert_vi_to_en($name),
                    'brief' => $desc,
                    'description' => $desc,
                ]
            );
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

    function _update_lesson()
    {
        $tbl = 'lesson';
        $list = model($tbl)->get_list();
        foreach ($list as $i) {
            if ($i->product_id > 0 && $i->group_id) {
                $t = [];
                $t[$i->product_id][] = $i->group_id;
                model($tbl)->update_field($i->id, 'product_group_id', json_encode($t));
                $data = [];
                $data['lesson_id'] = $i->id;
                $data['product_id'] = $i->product_id;
                $data['group_id'] = $i->group_id;
                $e = model('lesson_to_product')->check_exits($data);
                if (!$e)
                    model('lesson_to_product')->create($data, $id_new);
            }
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
        pr(get_date('1462587598', 'full'));
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
        mod('user_notice')->send(1,'Test','Noi dung thong bao Test');
    }

    function user_storage()
    {
        $input =['table'=>'user','table_id'=>'1','action'=>'follow','count'=>5];
        mod('user_storage')->set(1,$input);
    }
}
