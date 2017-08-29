<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Product_mod extends MY_Mod
{
    public function create_filter(array $fields, &$input = array())
    {
        $filter = parent::create_filter($fields, $input);
        if ($filter)
            foreach ($filter as $key => $v) {
                if (in_array($key, ['price', 'price_gt', 'price_lt', 'price_gte', 'price_lte'])) {
                    if (!is_array($v)) {
                        $v = currency_handle_input($filter[$key]);
                        $filter[$key] = $input[$key] = $v;
                    }
                } elseif ($key == 'cat_id') {
                    $ids = $v;
                    if (!is_array($ids))
                        $ids = mod('product_cat')->get_child_ids($v);
                    $filter[$key] = $ids;
                }
            }
        return $filter;
    }

    public function url($row)
    {
        $row->_url_view = site_url("xem-ban-tin/" . $row->seo_url . '-i' . $row->id);
        $row->_url_demo = site_url("xem-ban-tin/demo/" . $row->seo_url . '-d' . $row->id);
        $row->_url_reup = site_url("xem-ban-tin/demo/" . $row->seo_url . '-d' . $row->id);
        $row->_url_comment =  site_url("product/comment/" . $row->id);
        $row->_url_comment_add =  site_url("product/comment/" . $row->id).'?_act=add';
        $row->_url_comment_reply =  site_url("product/comment/" . $row->id).'?_act=reply';
        $row->_url_comment_show =  site_url("product/comment/" . $row->id).'?_act=show';

        // edit
        $row->_url_status_show =  site_url("product_post/on/" . $row->id);
        $row->_url_status_hide = site_url("product_post/off/" . $row->id);
        $row->_url_user_edit = site_url("product_post/edit/" . $row->id);
        $row->_url_user_del = site_url("product_post/del/" . $row->id);
        $row->_url_user_del = site_url("product_post/del/" . $row->id);
        //$row->_url_buy = site_url("product_order") . '?id=' . $row->id;

        return $row;
    }

    // Chu y: ham nay chi dc phep goi trong admin
    function del($id)
    {
        // Thuc hien xoa
        $this->_model()->del($id);


        // Xoa file
        file_del_table($this->_get_mod(),$id);

    }
    /**
     * Them cac thong tin phu vao thong tin cua product
     */
    function add_info($row, $full_data = false)
    {
        $row = parent::add_info($row);
        $row = $this->add_info_author($row);
        //$row = $this->add_info_manufacture($row);
        $row = $this->add_info_category($row);
        $row = $this->add_info_country($row);
        //$row = $this->add_info_price($row);
        if ($full_data) {
            //$row = $this->add_info_vat($row);
            //$row = $this->add_info_attribute($row);
            //$row = $this->add_info_option($row);
            //$row = $this->add_info_addon($row);
            $row = $this->add_info_images($row);
            $row = $this->add_info_files($row);
        }
        //$row->_can_order = $this->can_do($row, 'order');

        if($row->description){
            $row->description = str_replace("\n",'<br/>',$row->description);

        }

        $point_total=  $row->point_total + $row->point_fake;
        $row->_point_total = number_format($point_total) ;
        return $row;
    }

    /**
     * Price
     * @param  [type] $row [description]
     * @return [type]      [description]
     *
     */
    public function add_info_price($row)
    {

        // Price Prepare
        $row->_price_amount = 0;

        if ($row->price_is_contact) {
            $row->price = 0;
            $row->_price = lang('price_contact');
            return $row;
        }

        //== Gi� g?c
        $price_suffix = $row->price_suffix ? '/' . $row->price_suffix : '';
        if (!$row->price)
            $row->_price = lang('price_free');
        else {
            $row->_price = currency_format_amount($row->price) . $price_suffix;
            $row->_price_amount = $row->price;
        }
        //== Gi� khuy?n m�i
        // Get special of current product
        $row->special = model('product_to_special')->filter_get_list(array('product_id' => $row->id));
        if (count($row->special) > 0) {

            $selected = null;
            // L?y khuy?n m�i ?ang trong ho?t ??ng v?i ratio cao nh?t
            foreach ($row->special as $special) {
                if ($special->begin_date > time())
                    continue;
                if ($special->end_date < time() && $special->end_date)
                    continue;

                if ($selected) {
                    if ($selected->sort < $special->sort)
                        $selected = $special;
                } else
                    $selected = $special;

            }

            if ($selected) {
                $price = $row->price - $selected->price;
                $row->_price = currency_format_amount($price) . $price_suffix;
                $row->_price_old = currency_format_amount($row->price) . $price_suffix;

                $row->_price_special_reduce = currency_format_amount($selected->price);
                $row->_price_special_percent = round(($selected->price * 100) / $row->price, 2);
                $row->_price_special_begin = format_date($special->begin_date);
                $row->_price_special_end = format_date($special->end_date);
                $row->_price_amount = $price;
                // pr($row);
            }
        }

        //== Chi?t kh?u
        $row->discount = model('product_to_discount')->filter_get_list(array('product_id' => $row->id));
        //pr($row->discount);
        //== T�nh gi� theo chi?t kh?u
        if (count($row->discount) > 0) {
            $row->_price_discount = array();
            // L?y chi?t kh?u ?ang trong ho?t ??ng v?i ratio cao nh?t th?a m�n s? l??ng ??t h�ng
            foreach ($row->discount as $discount) {
                if ($discount->begin_date > time())
                    continue;
                if ($discount->end_date < time() && $discount->end_date)
                    continue;

                if (isset($row->_price_discount[$discount->quantity])) {
                    if ($row->_price_discount[$discount->quantity][0] < $discount->sort)
                        $row->_price_discount[$discount->quantity] = array($discount->sort, currency_format_amount($discount->price));
                } else {
                    $row->_price_discount[$discount->quantity] = array($discount->sort, currency_format_amount($discount->price));
                }
            }

            krsort($row->_price_discount);

        }


        return $row;
    }

    /**
     * H?p nh?t gi?m gi�
     * + Gi?m theo s? l??ng chi?t kh?u
     * + Gi?m theo s?n ph?m t�y ch?n: m�u s?c, khu v?c, v.v...
     *
     */
    public function add_info_price_cart($row, $quantity, $options = array(), $addons = array())
    {
        $row->_additional_amount = 0;
        $row->_option_html = '';

        /*  if (!$row->price)
              return $row;*/

        // Chi?t kh?u
        $row->discount = model('product_to_discount')->filter_get_list(array('product_id' => $row->id));

        //== T�nh gi� theo chiet khau
        if (count($row->discount) > 0) {
            $selected = null;
            // L?y chi?t kh?u ?ang trong ho?t ??ng v?i ratio cao nh?t th?a m�n s? l??ng ??t h�ng
            foreach ($row->discount as $discount) {
                if ($discount->begin_date > time())
                    continue;
                if ($discount->end_date < time() && $discount->end_date)
                    continue;
                if ($discount->quantity > $quantity)
                    continue;

                if (!$selected || $selected->quantity < $discount->quantity)
                    $selected = $discount;
                else if ($selected->quantity == $discount->quantity) {
                    if ($selected->sort < $discount->sort)
                        $selected = $discount;
                }

            }
            // thiet lap lai gia san pham theo muc giam gia so luong
            if ($selected) {
                $row->_price_amount = $selected->price;

            }
        }


        //== T�nh gi� theo option
        $this->_add_info_price_cart_option($row, $quantity, $options);
        //== T�nh gi� theo addon
        $this->_add_info_price_cart_addon($row, $quantity, $addons);

        //==========================================
        $row->_price = '
			<span class="list-product-old-price">' . currency_format_amount($row->price) . '</span>
			<span class="list-product-meta-price">' . currency_format_amount($row->_price_amount) . ' </span>
		';


        if ($row->price) {
            $row->_price_reduce = currency_format_amount($row->price - $row->_price_amount);
            $row->_price_percent = round(($row->price - $row->_price_amount) / ($row->price / 100));
        }

        return $row;
    }

    public function _add_info_price_cart_option(&$row, $quantity, $option)
    {
        if (!$option) return $row;

        //== Su ly gia Options
        $option_html = '';
        // VAT
        if ($row->_tax_class)
            $option_html .= '<br/>( <i>' . $row->_tax_class->name . '</i> )';

        $option_selected = model('product_to_option')->get_list_rule(array('product_id' => $row->id));                // Tin b�i n�y ?� ch?n nh?ng options
        $option_value_selected = model('product_to_option_value')->get_list_rule(array('product_id' => $row->id));    // C?u h�nh c?a nh?ng options ?� ch?n
        $options = model('option')->get_list(array('type' => 'asc'));                                                // Th�ng tin v? options
        $option_values = model('option_value')->get_list(array('type' => 'asc'));                                            // Th�ng tin v? options
        foreach ($option_selected as $sel_opt) {
            if (isset($option[$sel_opt->id])) {
                $tmp = array();
                if (is_array($option[$sel_opt->id])) {
                    $tmp = $option[$sel_opt->id];
                } else {
                    $tmp[] = $option[$sel_opt->id];
                }

                // Get name of option
                $inf_opt = objectExtract(array(
                    'id' => $sel_opt->option_id
                ), $options, true);

                foreach ($tmp as $sel_value_id) {
                    $sel_val = objectExtract(array(
                        'product_option_id' => $sel_opt->id,
                        'id' => $sel_value_id
                    ), $option_value_selected, true);

                    if ($sel_val) {
                        // Get name of value
                        $inf_val = objectExtract(array(
                            'id' => $sel_val->option_value_id
                        ), $option_values, true);

                        $option_html .= '<br/> - ' . $inf_opt->name . ': ' . $inf_val->name;

                        $amount = $sel_val->price;
                        if ($sel_val->subtract) {
                            $amount = $amount * $quantity;
                        }

                        if ($sel_val->price_prefix == '+')
                            $row->_additional_amount += $amount;
                        else
                            $row->_additional_amount -= $amount;

                        if ($amount)
                            $option_html .= ' ( '
                                . $sel_val->price_prefix . ' '
                                . currency_format_amount($sel_val->price) . ' '
                                . ($sel_val->subtract ? ' x ' . $quantity : '')
                                . ' ) ';
                    }
                }

            }
        }

        $row->_option_html .= $option_html;

    }

    public function _add_info_price_cart_addon(&$row, $quantity, $addons)
    {
        if (!$addons) return $row;

        //== Su ly gia Options
        $option_html = [];
        // Kiem tra lai xem co dung cac addo cua san pham ko
        $option_selected = model('product_to_addon')->filter_get_list(array('product_id' => $row->id, 'addon_id' => $addons));                // Tin b�i n�y ?� ch?n nh?ng options

        $addon_ids = array_gets($option_selected, 'addon_id');
        $options = model('addon')->filter_get_list(array('id' => $addon_ids, 'show' => 1));
        foreach ($option_selected as $sel_opt) {
            $tmp = '';
            if (in_array($sel_opt->addon_id, $addon_ids)) {
                // Get name of option
                $inf_opt = objectExtract(array(
                    'id' => $sel_opt->addon_id
                ), $options, true);
                $tmp .= '+ ' . $inf_opt->name;
                $amount = $sel_opt->price;

                if ($amount) {
                    if ($sel_opt->price_prefix == '+')
                        $row->_additional_amount += $amount;
                    else
                        $row->_additional_amount -= $amount;

                    $tmp .= ' ( '
                        . $sel_opt->price_prefix
                        . currency_format_amount($sel_opt->price) . ' '
                        . ' ) ';
                }
            }
            if ($tmp) $option_html[] = $tmp;
        }
        if ($option_html)
            $row->_option_html .= implode('<br>', $option_html);
        // pr($option_html);
    }


    public function add_info_vat($row)
    {
        $row->_tax_class = model('tax_class')->get_info($row->taxclass);

        $row->_tax_rate_ids = null;
        if ($row->_tax_class) {
            $_to_rate = model('tax_class_to_rate')->filter_get_list(array('class_id' => $row->_tax_class->id));
            if ($_to_rate)
                $row->_tax_rate_ids = array_gets($_to_rate, array('rate_id', 'piority'));
        }

        return $row;
    }


    public function add_info_category($row)
    {
        $info = model('product_cat')->get_info($row->cat_id, 'id,name,image_id,image_name,seo_url');
        $row->{"_cat_name"}  ='';
        if ($info) {
            $info = mod('product_cat')->add_info($info);
            $name = $info->name;
            $row->{"_cat"} = $info;
            $row->{"_cat_name"} = $name;
        }

        return $row;
    }

    public function add_info_country($row)
    {
        $info = model('country')->get_info($row->cat_id, 'id,name');
        $row->{"_country_name"} = '';
        if ($info) {
            $name = $info->name;
            $row->{"_country"} = $info;
            $row->{"_country_name"} = $name;
        }

        return $row;
    }

    public function add_info_manufacture($row)
    {
        if (isset($row->manufacture_id) && $row->manufacture_id) {
            $name = '';
            $info = model('manufacture')->get_info($row->manufacture_id, 'id,name,cat_id,image_id,image_name,seo_url');
            if ($info) {
                $info = mod('manufacture')->add_info($info);
                $name = $info->name;
            }
            $row->{"_manufacture"} = $info;
            $row->{"_manufacture_name"} = $name;
        }

        return $row;
    }

    public function add_info_author($row)
    {

        if (isset($row->user_id) && $row->user_id) {
            $it = mod('user')->get_info($row->user_id);//,'id,name,phone,avatar,profession,desc,');

        } else {
            //if (mod("product")->setting('author_auto_default')) {
            $it = mod('user')->get_info(user_get_id_root());//, 'id,name,phone,avatar,profession,desc');
            $row->author_id = $it->id;
        }
        if ($it) {
            $row->{"_author"} = $it;
            $row->{"_author_name"} = $it->name;
        }

        return $row;
    }

    public function add_info_author_($row)
    {
        $ids = $names = $list = array();
        //pr($row->author_id);
        if ($row->author_id) {
            $ids = explode(',', $row->author_id);
            foreach ($ids as $id) {
                $it = mod('user')->get_info($id);//,'id,name,phone,avatar,profession,desc,');
                if ($it) {
                    $names[] = $it->name;
                    $list[] = $it;
                }
            }
        } else {
            // neu bat chuc nang set tu dong
            $it = null;
            //if (mod("product")->setting('author_auto_default')) {
            $it = mod('user')->get_info(user_get_id_root());//, 'id,name,phone,avatar,profession,desc');

            if ($it) {
                $ids[] = $it->id;
                $names[] = $it->name;
                $list[] = $it;
            }

            // }
        }
        $row->{"_author"} = $list;
        $row->{"_author_name"} = $it ? implode(', ', $names) : '';
        $row->{"_author_id"} = $it ? $ids : '';
        return $row;
    }

    /**
     * ---------------------
     * Product Attribute
     * ---------------------
     *
     */
    public function add_info_attribute($row)
    {
        // Get attributes of current product
        $row->attribute_selected = model('product_to_attribute')->get_list_rule(array('product_id' => $row->id));
        return $row;
    }

    /**
     * ---------------------
     * Product Option
     * ---------------------
     *
     */
    public function add_info_option($row)
    {
        // Get option of current product
        $row->option_selected = model('product_to_option')->get_list_rule(array('product_id' => $row->id));
        // Get option value of current product
        $row->option_value_selected = model('product_to_option_value')->get_list_rule(array('product_id' => $row->id));
        return $row;
    }

    public function add_info_addon($row)
    {
        $list = model('product_to_addon')->filter_get_list(array('product_id' => $row->id));
        foreach ($list as $it) {
            $addon = model('addon')->get_info($it->addon_id, 'id,name,description');
            if ($addon) {
                $it->_data = mod('addon')->add_info($addon);
            }
        }
        // pr_db($list);


        $row->_addons = $list;
        return $row;
    }

    public function add_info_owner($row)
    {
        $row->_owners = $this->owner_get($row->id, $this->_get_mod());
        return $row;
    }

    /**
     *
     * Action update & insert
     * relationship with discount
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $discount        Discount rows
     *
     */
    public function to_discount($product_id, $discount)
    {
        $rela = model('product_to_discount')->get_list_rule(array('product_id' => $product_id));
        // Delete & update
        if ($rela) {
            $delete = array();
            foreach ($rela as $row) {
                if (!empty($discount)) {
                    if (isset($discount[$row->id])) {
                        // Update old discount
                        $dates = array();
                        $price = (String)$discount[$row->id]['price'];
                        $price = str_replace(',', '', $price);

                        foreach (array('begin_date', 'end_date') as $date) {
                            if (preg_match("/^\d{2}\-\d{2}\-\d{4}$/", $discount[$row->id][$date])) {
                                $tmp = explode('-', $discount[$row->id][$date]);
                                $dates[$date] = mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);
                            } else
                                $dates[$date] = 0;
                        }


                        model('product_to_discount')->update($row->id,
                            array(
                                'customer_group_id' => $discount[$row->id]['customer_group_id'],
                                'quantity' => $discount[$row->id]['quantity'],
                                'price' => (double)$price,
                                'begin_date' => $dates['begin_date'],
                                'end_date' => $dates['end_date'],
                                'sort' => $discount[$row->id]['sort']
                            )
                        );
                    } else {
                        $delete[] = $row->id;
                    }
                } else {
                    $delete[] = $row->id;
                }
            }

            if (count($delete))
                model('product_to_discount')->del_rows(array("id" => $delete));
        }

        // Insert
        $data = array();

        // Loop all values of fields
        if ($discount)
            foreach ($discount as $key => $value) {
                if (substr($key, 0, 1) == 'n') {
                    // Prepare insert data
                    $dates = array();
                    $price = (String)$discount[$key]['price'];
                    $price = str_replace(',', '', $price);

                    foreach (array('begin_date', 'end_date') as $date) {
                        if (preg_match("/^\d{2}\-\d{2}\-\d{4}$/", $discount[$key][$date])) {
                            $tmp = explode('-', $discount[$key][$date]);
                            $dates[$date] = mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);
                        } else
                            $dates[$date] = 0;
                    }

                    $data[] = array(
                        'product_id' => $product_id,
                        'customer_group_id' => $discount[$key]['customer_group_id'],
                        'quantity' => $discount[$key]['quantity'],
                        'price' => (double)$price,
                        'begin_date' => $dates['begin_date'],
                        'end_date' => $dates['end_date'],
                        'sort' => $discount[$key]['sort']
                    );
                }
            }


        if (count($data))
            model('product_to_discount')->create_rows($data);
    }

    /**
     *
     * Action update & insert
     * relationship with special
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $special        Special rows
     *
     */
    public function to_special($product_id, $special)
    {
        $rela = model('product_to_special')->get_list_rule(array('product_id' => $product_id));

        // Delete & update
        if ($rela) {
            $delete = array();
            foreach ($rela as $row) {
                if (!empty($special)) {
                    if (isset($special[$row->id])) {
                        // Update old special
                        $dates = array();
                        $price = (String)$special[$row->id]['price'];
                        $price = str_replace(',', '', $price);

                        foreach (array('begin_date', 'end_date') as $date) {
                            if (preg_match("/^\d{2}\-\d{2}\-\d{4}$/", $special[$row->id][$date])) {
                                $tmp = explode('-', $special[$row->id][$date]);
                                $dates[$date] = mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);
                            } else
                                $dates[$date] = 0;
                        }


                        model('product_to_special')->update($row->id,
                            array(
                                'customer_group_id' => $special[$row->id]['customer_group_id'],
                                'price' => (double)$price,
                                'begin_date' => $dates['begin_date'],
                                'end_date' => $dates['end_date'],
                                'sort' => $special[$row->id]['sort']
                            )
                        );
                    } else {
                        $delete[] = $row->id;
                    }
                } else {
                    $delete[] = $row->id;
                }
            }

            if (count($delete))
                model('product_to_special')->del_rows(array("id" => $delete));
        }

        // Insert
        $data = array();

        // Loop all values of fields
        if ($special)
            foreach ($special as $key => $value) {
                if (substr($key, 0, 1) == 'n') {
                    // Prepare insert data
                    $dates = array();
                    $price = (String)$special[$key]['price'];
                    $price = str_replace(',', '', $price);

                    foreach (array('begin_date', 'end_date') as $date) {
                        if (preg_match("/^\d{2}\-\d{2}\-\d{4}$/", $special[$key][$date])) {
                            $tmp = explode('-', $special[$key][$date]);
                            $dates[$date] = mktime(0, 0, 0, $tmp[1], $tmp[0], $tmp[2]);
                        } else
                            $dates[$date] = 0;
                    }

                    $data[] = array(
                        'product_id' => $product_id,
                        'customer_group_id' => $special[$key]['customer_group_id'],
                        'price' => (double)$price,
                        'begin_date' => $dates['begin_date'],
                        'end_date' => $dates['end_date'],
                        'sort' => $special[$key]['sort']
                    );
                }
            }


        if (count($data))
            model('product_to_special')->create_rows($data);
    }

    /**
     *
     * Action update & insert
     * relationship with attributes
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $attributes        Info of attributes
     *
     */
    public function to_attribute($product_id, $attributes)
    {
        $rela = model('product_to_attribute')->get_list_rule(array('product_id' => $product_id));

        // Delete
        if ($rela) {
            $delete = array();
            foreach ($rela as $row) {
                if (!empty($attributes)) {
                    if (!isset($attributes[$row->id]))
                        $delete[] = $row->id;
                    else {
                        if ($row->value != $attributes[$row->id]['value'] || $row->attribute_id != $attributes[$row->id]['attribute'])
                            model('product_to_attribute')->update(
                                $row->id,
                                array(
                                    'attribute_id' => $attributes[$row->id]['attribute'],
                                    'value' => $attributes[$row->id]['value']
                                )
                            );
                    }
                } else {
                    $delete[] = $row->id;
                }
            }

            if (count($delete))
                model('product_to_attribute')->del_rows(array("id" => $delete));
        }

        // Insert
        $data = array();

        // Loop all values of fields
        if ($attributes)
            foreach ($attributes as $key => $value) {
                if (substr($key, 0, 1) == 'a') {
                    $data[] = array(
                        'product_id' => $product_id,
                        'attribute_id' => $value['attribute'],
                        'value' => $value['value']
                    );
                }
            }


        if (count($data))
            model('product_to_attribute')->create_rows($data);
    }

    /**
     *
     * Action update & insert
     * relationship with option & option value
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $options        Options rows
     * @param  [array]    $option_values  Option values rows
     *
     */
    public function to_option($product_id, $options, $option_values)
    {
        // X�t c�c option c� trong db
        $rela = model('product_to_option')->get_list_rule(array('product_id' => $product_id));
        if ($rela) {
            $delete = array();
            foreach ($rela as $row) {
                if (!empty($options)) {
                    // N?u post data c� product option n�y th� update
                    if (isset($options[$row->id])) {
                        if (in_array($options[$row->id]['type'], array('radio', 'select', 'checkbox'))) {
                            // update product option
                            model('product_to_option')->update($row->id,
                                array(
                                    'required' => $options[$row->id]['required']
                                )
                            );

                            // D?ng radio, select, checkbox th� ph?i x�t c? option_value c� trong db
                            // ?? x�a ho?c s?a
                            $value_rela = model('product_to_option_value')->get_list_rule(array(
                                'product_id' => $product_id,
                                'product_option_id' => $row->id,
                                'option_id' => $row->option_id
                            ));
                            if ($value_rela) {
                                $value_delete = array();
                                foreach ($value_rela as $row2) {
                                    // N?u post data c� product option value n�y th� update
                                    if (isset($option_values[$row->id][$row2->id])) {
                                        $price = (String)$option_values[$row->id][$row2->id]['price'];
                                        $price = str_replace(',', '', $price);

                                        model('product_to_option_value')->update($row2->id,
                                            array(
                                                'option_value_id' => $option_values[$row->id][$row2->id]['option_value_id'],
                                                'quantity' => $option_values[$row->id][$row2->id]['quantity'],
                                                'subtract' => $option_values[$row->id][$row2->id]['subtract'],
                                                'price' => $price,
                                                'price_prefix' => $option_values[$row->id][$row2->id]['price_prefix'],
                                                'points' => $option_values[$row->id][$row2->id]['points'],
                                                'points_prefix' => $option_values[$row->id][$row2->id]['points_prefix'],
                                                'weight' => $option_values[$row->id][$row2->id]['weight'],
                                                'weight_prefix' => $option_values[$row->id][$row2->id]['weight_prefix']
                                            )
                                        );
                                    } else {
                                        $value_delete[] = $row2->id;
                                    }
                                }

                                // N?u kh�ng c� th� delete
                                if ($value_delete)
                                    model('product_to_option_value')->del_rows(array("id" => $value_delete));
                            }

                            // Ki?m tra post option value xem c� c�i m?i th� th�m
                            $data_values = array();
                            foreach ($option_values[$row->id] as $key => $value) {
                                if (substr($key, 0, 1) == 'v') {
                                    $price = (String)$value['price'];
                                    $price = str_replace(',', '', $price);

                                    $data_values[] = array(
                                        'product_option_id' => $row->id,
                                        'product_id' => $product_id,
                                        'option_id' => $row->option_id,
                                        'option_value_id' => $value['option_value_id'],
                                        'quantity' => $value['quantity'],
                                        'subtract' => $value['subtract'],
                                        'price' => $price,
                                        'price_prefix' => $value['price_prefix'],
                                        'points' => $value['points'],
                                        'points_prefix' => $value['points_prefix'],
                                        'weight' => $value['weight'],
                                        'weight_prefix' => $value['weight_prefix']
                                    );
                                }
                            }

                            if (count($data_values))
                                model('product_to_option_value')->create_rows($data_values);


                        } else {
                            // D?ng text, textarea th� ch? c?n x�t product option
                            model('product_to_option')->update($row->id,
                                array(
                                    'value' => $options[$row->id]['value'],
                                    'required' => $options[$row->id]['required']
                                )
                            );

                            model('product_to_option_value')->del_rows(array("product_option_id" => $row->id));
                        }

                    } else {
                        $delete[] = $row->id;
                    }
                } else {
                    $delete[] = $row->id;
                }
            }

            if (count($delete)) {
                model('product_to_option')->del_rows(array("id" => $delete));
                model('product_to_option_value')->del_rows(array("product_option_id" => $delete));
            }
        }

        // Insert
        $data = array();

        // x�t c�c product option ???c post l�n
        if ($options)
            foreach ($options as $key => $value) {
                if (substr($key, 0, 1) == 'o') {
                    if (in_array($value['type'], array('radio', 'select', 'checkbox'))) {
                        // insert ngay ?? l?y id
                        $product_option_id = 0;
                        model('product_to_option')->create(array(
                            'product_id' => $product_id,
                            'option_id' => $value['id'],
                            'required' => $value['required']
                        ), $product_option_id);

                        // Insert option value t??ng ?ng
                        $data_values = array();
                        foreach ($option_values[$key] as $key2 => $value2) {
                            if (substr($key2, 0, 1) == 'v') {
                                $price = (String)$value2['price'];
                                $price = str_replace(',', '', $price);

                                $data_values[] = array(
                                    'product_option_id' => $product_option_id,
                                    'product_id' => $product_id,
                                    'option_id' => $value['id'],
                                    'option_value_id' => $value2['option_value_id'],
                                    'quantity' => $value2['quantity'],
                                    'subtract' => $value2['subtract'],
                                    'price' => $price,
                                    'price_prefix' => $value2['price_prefix'],
                                    'points' => $value2['points'],
                                    'points_prefix' => $value2['points_prefix'],
                                    'weight' => $value2['weight'],
                                    'weight_prefix' => $value2['weight_prefix']
                                );
                            }
                        }

                        if (count($data_values))
                            model('product_to_option_value')->create_rows($data_values);

                    } else {
                        // Ch? c?n th�m product option v�o db
                        $data[] = array(
                            'product_id' => $product_id,
                            'option_id' => $value['id'],
                            'value' => $value['value'],
                            'required' => $value['required']
                        );
                    }


                }
            }


        if (count($data))
            model('product_to_option')->create_rows($data);
    }

    /**
     *
     * Action update & insert
     * relationship with attributes
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $attributes        Info of attributes
     *
     */
    public function to_addon($product_id, $values)
    {
        $rela = model('product_to_addon')->get_list_rule(array('product_id' => $product_id));
        //pr($values);
        // Delete
        if ($rela) {
            $delete = array();
            foreach ($rela as $row) {
                if (!empty($values)) {
                    if (!isset($values[$row->id]))
                        $delete[] = $row->id;
                    else {
                        $price = currency_handle_input($values[$row->id]['price']);
                        if ($row->addon_id != $values[$row->id]['id'] ||
                            $row->sort != $values[$row->id]['sort'] ||
                            $row->price_prefix != $values[$row->id]['price_prefix'] ||
                            $row->price != $price
                        )

                            model('product_to_addon')->update(
                                $row->id,
                                array(
                                    'addon_id' => $values[$row->id]['id'],
                                    'sort' => $values[$row->id]['sort'],
                                    'price_prefix' => $values[$row->id]['price_prefix'],
                                    'price' => $price

                                )
                            );
                    }
                } else {
                    $delete[] = $row->id;
                }
            }

            if (count($delete))
                model('product_to_addon')->del_rows(array("id" => $delete));
        }

        // Insert
        $data = array();

        // Loop all values of fields
        if ($values)
            foreach ($values as $key => $value) {
                if (substr($key, 0, 1) == 'n') {

                    $price = currency_handle_input($value['price']);
                    $data[] = array(
                        'product_id' => $product_id,
                        'addon_id' => $value['id'],
                        'price_prefix' => $value['price_prefix'],
                        'price' => $price
                    );
                }
            }

        if (count($data))
            model('product_to_addon')->create_rows($data);
    }

    public function to_types($product_id, $values, $type_cat_id)
    {
        $this->_to_types($values, $type_cat_id, $product_id, 'product');
    }

    function _to_types($types, $type_cat_id, $table_id, $table)
    {
        //pr($types);
        if (!$types || !$type_cat_id || !$table_id || !$table) return false;
        // xoa cac ban ghi cu
        $where = array();
        $where['type_cat_id'] = $type_cat_id;
        $where['table'] = $table;
        $where['table_id'] = $table_id;
        model('type_table')->del_rule($where);
        // them moi tag
        foreach ($types as $type=>$item) {
            //echo '<br> type:'.$type.' - item:'.$item;
            if ($type && $item) {
                $where['type_id'] = $type;
                $where['type_item_id'] = $item;
                model('type_table')->create($where);
               // pr_db($where,0);
            }
        }
       // pr(1);
        return true;
    }

    public function can_do($row, $action)
    {

        if (in_array($action, ['order'])) {
            switch ($action) {
                case 'order':
                    // kiem tra xem con hang khong
                    if (isset($row->is_alway_in_stock) && !$row->is_alway_in_stock) {
                        if ($row->quantity <= 0)
                            return false;
                    }
                    return true;

            }
        } else
            return parent::can_do($row, $action);

        return false;
    }


    public function action($row, $action)
    {
        // Lay thong tin
        if (is_numeric($row)) {
            $row = $this->_model()->get_info($row);
        }
        if (in_array($action, ['order'])) {
            if ($this->can_do($row, $action)) {

                switch ($action) {
                    case 'order': {
                        return;
                        break;
                    }
                }
            }
        } else
            return parent::action($row, $action);

        return false;


    }

    /* Su ly cong hoa hong*/

    public function bonus($invoice_order, $obj)
    {
        //= tinh hoa hong
        // pr($obj->user_options);
        if (isset($obj->user_options) && $obj->user_options) {
            if (is_string($obj->user_options))
                $obj->user_options = json_decode($obj->user_options);

            if ($obj->user_options->status && $obj->user_options->user_id && $obj->user_options->amount) {
                $invoice_order->user_options = $obj->user_options;
                $invoice_order_com = new \App\Invoice\InvoiceService\Commisson();
                $invoice_order_com->active($invoice_order);
            }
        }
        //cong tien cho nguoi gioi thieu
        if (!$this->setting('affiliate_turn_on') || !$this->setting('affiliate_commission_constant') || !$this->setting('affiliate_commission_percent')) {
            $invoice_order_aff = new \App\Invoice\InvoiceService\Affiliate();
            $invoice_order_aff->active($invoice_order);
        }

    }

    public function invoice_create_order(array $input, &$output = array())
    {
        // Xu ly input
        $amount = array_get($input, 'amount');
        $cart = array_get($input, 'cart');
        $data = array_get($input, 'data');
        $voucher = array_get($input, 'voucher', null);
        $user = array_get($input, 'user', null);
        // pr($data,0);
        $invoice_status = array_get($input, 'invoice_status', App\Invoice\Library\InvoiceStatus::UNPAID);
        $invoice_options = array(
            'pre_key' => setting_get('config-invoice_pre_key'),
            'pre_number' => setting_get('config-invoice_pre_number'),
            // 'shipping_method' => isset($data['shipping']) ? $data['shipping'] : '',
            // 'payment_method' => isset($data['payment']) ? $data['payment'] : ''
        );

        if (isset($data['shipping'])) {
            $shipping_method = model('shipping_rate')->get_name($data['shipping']);
            //  $shipping_method = objectExtract(['id'=>$data['shipping']],$data['shipping_methods'],1);
            $invoice_options['shipping_method'] = $data['shipping'];
            $invoice_options['shipping_method_name'] = $shipping_method;
            //if($shipping_method)    $invoice_options['shipping_method_name']=$shipping_method->name;
        }
        if (isset($data['payment'])) {
            $payment_method = model('payment_method')->get_info($data['payment'], 'name');
            $invoice_options['payment_method'] = $data['payment'];
            if ($payment_method)
                $invoice_options['payment_method_name'] = $payment_method->name;
        }


        $invoice_order_status = array_get($input, 'invoice_order_status', App\Invoice\Library\OrderStatus::PENDING);
        $invoice_order_options = array_get($input, 'invoice_order_options', []);

        //== tao invoice
        $input = [];
        $input['amount'] = $amount;
        if ($user)
            $input['user_id'] = $user->id;
        $input['invoice_status'] = $invoice_status;
        $input['invoice_options'] = $invoice_options;
        $input['invoice_order_status'] = $invoice_order_status;
        $input['service_key'] = 'ProductOrder';

        $contact = ['name', 'phone', 'email', 'address', 'tax_code',
            'country', 'country_name', 'city', 'city_name', 'district', 'district_name',];

        $input['info_contact'] = [];
        foreach ($contact as $f) {
            $contact_value = '';
            if (isset($data[$f]))
                $contact_value = $input['info_contact'][$f] = $data[$f];
            $input['info_contact'][$f] = $contact_value;
        }

        if (isset($data['get_gtgt']) && $data['get_gtgt']) {
            $input['info_contact']['get_gtgt'] = $data['get_gtgt'];
            foreach ($contact as $f) {
                if (isset($data['company_' . $f]))
                    $input['info_contact']['company_' . $f] = $data['company_' . $f];
            }
        }


        if (isset($data['auction_price'])) {
            $input['info_contact']['auction_price'] = $data['auction_price'];
            $input['info_contact']['auction_intro'] = $data['auction_intro'];
        }
        if (isset($data['shipping_to_other_address']) && $data['shipping_to_other_address']) {
            $input['info_contact']['shipping_to_other_address'] = $data['shipping_to_other_address'];
            foreach ($contact as $f) {
                if (isset($data['shipping_' . $f]))
                    $input['info_shipping'][$f] = $data['shipping_' . $f];
            }

        }

        //===
        if (isset($data['note']))
            $input['info_note'] = $data['note'];
        if (isset($cart['total_shipping']))
            $input['fee_shipping'] = $cart['total_shipping'];
        if (isset($cart['total_tax']))
            $input['fee_tax'] = $cart['total_tax'];


        $input['invoice_order_options'] = $invoice_order_options;
        //echo 'input:';        pr($input);
        // pr($input['invoice_order_options'] );
        $products = [];
        //pr($cart);
        foreach ($cart['list'] as $row) {
            $product = [];
            $product['amount'] = $row->total_price;
            $product['product_id'] = $row->id;
            $product['product_title'] = $row->name;
            $product['product_quatity'] = $row->qty;
            if (isset($row->option_html))
                $product['product_desc'] = $row->option_html;

            if (isset($row->tax_value))
                $product['fee_tax'] = $row->tax_value;
            if (isset($row->profit))
                $product['profit'] = $row->profit;
            $products[] = $product;
        }
        $input['products'] = $products;
        // pr($input);
        $invoice = $this->invoice_create($input);


        return $invoice;

    }

    public function owner_check($info, $user = null)
    {
        if (!$info) {
            return false;
        }
        if ($info->price_option == 0)
            return true;
        // Permission
        if (!$user)
            $user = user_get_account_info();

        if (!$user) {
            return false;
        }

        if ($info->price_option == 1) {

            if (!$user)
                return false;

            $where = [
                'user_id' => $user->id,
                'table_id' => $info->id,
                'table_name' => $this->_get_mod(),
            ];

            $order = model('product_owner')->get_info_rule($where);
            //pr_db($order);

            $ordered = false;
            if ($order) {
                $ordered = TRUE;
                // check status
                if (!$order->status)
                    $ordered = false;

                /*if ($order->watch_max && $order->watch_count >= $order->watch_max) {
                    $ordered = FALSE;// neu het so lan xem
                    // neu het han thi xoa khoi bang
                    model('product_owner')->del_rule($where);
                }
                if ($order->watch_expired && $order->watch_expired <= now()) {
                    $ordered = FALSE;// neu het han
                    // neu het han thi xoa khoi bang
                    model('product_owner')->del_rule($where);
                }*/
            }
            if ($ordered)
                return true;
            return false;
        } else if ($info->price_option == 2) {
            if (
                $user
                && method_exists(mod('user'), '_check_vip')
                && mod('user')->_check_vip()
            ) {
                return true;
            }

            return false;
        }
        return false;
    }


    /**
     * Get owners
     * @param  [type] $row [description]
     * @return [type]      [description]
     *
     */
    public function owner_get($id)
    {
        $relation = model('product_owner')->filter_get_list([
            'table_id' => $id,
            'table_name' => $this->_get_mod(),

        ]);
        $owners_id = array_gets($relation, 'user_id');
        $owners = null;
        if ($owners_id) {
            $owners = model('user')->filter_get_list(['id' => $owners_id]);
            foreach ($owners as $owner) {

                $avatar_name = $owner->avatar;
                $owner->avatar = file_get_image_from_name($avatar_name, public_url('img/user_no_image.png'));
                $owner->_avatar = $owner->avatar->url;
                $owner->_information = $owner->email;
            }
        }

        return $owners;
    }

    function owner_set($id, $user_id, $options = [])
    {
        $obj = $this->_model()->get_info($id, 'limit_data');
        if (!$obj) return;
        if (!$this->_model()->check_id($user_id)) return;

        $where = array('table_name' => $this->_get_mod(), 'table_id' => $id, 'user_id' => $user_id);
        $owner = model('product_owner')->get_info_rule($where);

        /*
        if ($obj && $obj->watch_config)// neu co thiet lap rieng
        {
            $watch_times = (int)$obj->watch_times;
            $watch_expired = (int)$obj->watch_expired * 24 * 60 * 60;
        } else {
            $watch_times = $this->setting('premium_product_max_watch_times');
            $watch_expired = (int)$this->setting('premium_course_exprie_time') * 24 * 60 * 60;

        }*/

        // neu co truyen thiet lap han xem
        /* $option_watch_expired = array_get($options, 'watch_expired', null);
         if ($option_watch_expired)
             $watch_expired = $option_watch_expired;*/

        // pr($options,0);       pr($watch_expired);
        if (!$owner) {

            /* if ($watch_expired > 0)
                 $where['expired'] = now() + $watch_expired;
             if ($watch_times > 0)
                 $where['watch_max'] = $watch_times;*/
            //pr($where);
            $where['status'] = 1;
            $where['created'] = now();
            $where['updated'] = $where['created'];
            $where['updated_status'] = $where['status'];

            model('product_owner')->create($where);
        } else {
            $data = [];
            /*  if ($watch_expired > 0) {
                  if ($owner->watch_expired <= now())// neu het han
                      $data['watch_expired'] = now() + $watch_expired;
                  else
                      $data['watch_expired'] = $owner->watch_expired + $watch_expired;

              }
              if ($watch_times > 0) {
                  $data['watch_max'] = $owner->watch_max + $watch_times;
              }*/

            //pr($data);

            if ($data)
                model('product_owner')->update_rule($where, $data);
        }
    }

    /**
     * Set trong admin
     * Action update & insert
     * relationship with owner product
     * @param  [int]    $product_id        Id of product
     * @param  [array]    $owners            Ids of owners
     *
     */
    public function owner_set_multi($user_ids, $id, $options = [])
    {
        foreach ($user_ids as $user_id) {
            $this->owner_set($user_id, $id, $options);
        }
    }


    /*============*/
    function guest_owner_get($type)
    {
        $list = [];
        switch ($type) {
            case 'favorited':
                static $list = null;
                if (is_null($list)) {
                    $list = get_cookie('products_favorited');
                    // $favorieds=security_encrypt($favorieds,'decode');
                    $list = json_decode($list);
                }
                break;
        }

        return (array)$list;
    }

    function guest_owner_add($id, $type)
    {
        switch ($type) {
            case 'favorited':
                $list = $this->guest_owner_get($type);
                if (!in_array($id, $list)) {
                    //$favorieds = array_merge($favorieds,array($id));
                    array_unshift($list, $id);// them vao dau mang $compare[]=$product_id;
                    $count = count($list);
                    if ($count > 8) // neu hon 4 san pham thi bo phan tu cuoi
                        array_pop($list);
                    $list = json_encode($list);
                    //$favorieds=security_encrypt($favorieds,'encode');
                    set_cookie('products_' . $type, $list, 365 * 24 * 60 * 60);
                }
                break;
        }

    }

    function guest_owner_del($id, $type)
    {
        switch ($type) {
            case 'favorited':
                $list = $this->guest_owner_get($type);
                if (in_array($id, $list)) {

                    if (($key = array_search($id, $list)) !== false) {
                        unset($list[$key]);
                    }
                    // echo $id ;
                    // pr($favorieds);
                    $list = json_encode($list);
                    //$favorieds=security_encrypt($favorieds,'encode');
                    set_cookie('products_' . $type, $list, 365 * 24 * 60 * 60);

                }
                break;
        }

    }
}