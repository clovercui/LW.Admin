<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lw_string
{

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * 生成唯一字符号
     *
     * @return string
     */
    function getUniName()
    {
        return md5(uniqid(microtime(true), true));
    }

    /**
     * 得到文件的扩展名
     *
     * @param string $fileName
     * @return string
     */
    function getExt($fileName)
    {
        @$ext = strtolower(end(explode(".", $fileName)));
        return $ext;
    }

    function buildRandomString($type = 1, $length = 4)
    {
        // 生产一个含有所有字符的字符串
        if ($type == 1) {
            $chars = join("", range(0, 9));
        } elseif ($type == 2) {
            $chars = join("", array_merge(range("a", "z"), range(0, 9)));
        } elseif ($type == 3) {
            $chars = join("", array_merge(range("a", "z"), range("A", "Z"), range(0, 9)));
        } elseif ($type == 4) {
            // 验证码: 全部大写字母+数组-(0, O)
            $chars = join("", array_merge(range("A", "Z"), range(1, 9)));
        }
        if ($length > strlen($chars)) {
            exit ("字符串长度不够");
        }
        // 打乱字符串
        $chars = str_shuffle($chars);
        return substr($chars, 0, $length);
    }
}