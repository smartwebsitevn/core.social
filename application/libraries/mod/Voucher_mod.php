<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_mod extends MY_Mod
{

    /**
     * Them cac thong tin phu
     *
     * @param object $row
     * @return object
     */
    public function add_info($row)
    {

        if(isset($row->admin_id) &&  $row->admin_id)
            $row->_admin = model('admin')->get_info($row->admin_id,'username,name');

        if(isset($row->user_id) &&  $row->user_id)
            $row->_user = model('user')->get_info($row->user_id,'email,username,name');


        return $row;
    }
    public function caculate($amount,$voucher,&$vourcher_options=[])
    {
        if ($voucher) {
            $voucher_setting = json_decode($voucher->setting);
            if ($voucher->type == 'coupon') {
                if ($voucher_setting->discount > 0) {
                    $voucher_discount = $voucher_setting->discount;
                    $voucher_discount_type = $voucher_setting->discount_type;
                    if ($voucher_setting->discount_type == 1) {

                        if ($amount > $voucher_setting->discount)
                            $amount = $amount - $voucher_setting->discount;
                        else
                            $amount = 0;
                    } else {
                        $amount = $amount - $amount * $voucher_setting->discount / 100;

                    }
                }
            }
            // neu la loai mua dut ma su dung trong don hang
            if ($voucher->type == 'buyout') {
                $amount = 0;
                $voucher_discount = 100;
                $voucher_discount_type = '2';
            }
            $vourcher_options = array(
                'voucher_id' => $voucher->id,
                'voucher_type' => $voucher->type,
                'voucher_key' => $voucher->key,
                'voucher_name' => $voucher->name,
                'voucher_discount' => $voucher_discount,
                'voucher_discount_type' => $voucher_discount_type,
                'voucher_expired' => $voucher->expired,
            );

        }
        return $amount;
    }
    public function used($voucher_id,$user_id,$invoice_id='',$invoice_order_id='')
    {
        $data = array();
        $data['status'] = config('status_on');
        $data['invoice_id'] = $invoice_id;
        $data['invoice_order_id'] = $invoice_order_id;
        $data['used_by'] = $user_id;
        $data['used_time'] = now();
        model('voucher')->update($voucher_id, $data);
    }
}