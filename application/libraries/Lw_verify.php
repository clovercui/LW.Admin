<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lw_verify
{

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * 生成验证码
     *
     * @return string
     */
    function generateCode()
    {
        $this->CI->load->helper('captcha');
        $this->CI->load->library('lw_string');
        $code = $this->CI->lw_string->buildRandomString(4, 4);
        $this->CI->session->set_userdata('verifyCode', $code);
        $vals = [
            'word' => $code,
            'img_path' => 'captcha/',
            'img_url' => base_url() . 'captcha/',
            'font_path' => '',
            'img_width' => '100',
            'img_height' => 30,
            'expiration' => 600,
            'word_length' => 100,
            'font_size' => 100,
            'img_id' => 'verifyCode',
            'pool' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',

            // White background and border, black text and red grid
            'colors' => [
                'background' => [255, 255, 255],
                'border' => [255, 255, 255],
                'text' => [0, 0, 0],
                'grid' => [100, 40, 40]
            ]
        ];
        $cap = create_captcha($vals);
        return $cap;
    }


    public function checkVerifyCode($code)
    {
        $rs = ['success' => false, 'msg' => ''];
        if(strtolower($code) == strtolower($this->CI->session->verifyCode)) {
            $rs['success'] = true;
            $rs['msg'] = '验证码正确';
            // session 置空
            $this->CI->session->set_userdata('verifyCode', null);
        } else {
            $rs['msg'] = '验证码错误';
        }
        return $rs;
    }
}