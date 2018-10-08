<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lw_regex
{

    public function __construct()
    {

    }

    private function check($pattern, $str){
        return preg_match($pattern,$str)? true : false;
    }

    // 大于6位数字字母组合密码
    public function checkPassword($str)
    {
        $pattern = '^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,}$^';
        return $this->check($pattern,$str);
    }
}