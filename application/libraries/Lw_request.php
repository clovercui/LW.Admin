<?php

/**
 * Created by PhpStorm.
 * User: 伟
 * Date: 2016/8/12
 * Time: 15:29
 */
class Lw_request
{
    function post($url, $post = [], $autofollow = true, $debug = false, &$info = '')
    {
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        if (defined("COOKIEJAR")) {
            curl_setopt($c, CURLOPT_COOKIEJAR, COOKIEJAR);
            curl_setopt($c, CURLOPT_COOKIEFILE, COOKIEJAR);
        }
        curl_setopt($c, CURLOPT_HTTPHEADER, ["User-Agent: Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.2; Trident/4.0;)"]);
        if ($autofollow) curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($c, CURLOPT_AUTOREFERER, 1);
        curl_setopt($c, CURLOPT_TIMEOUT, 30);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        if ($post) {
            if (is_array($post)) {
                $poststr = '';
                foreach ($post as $key => $item) {
                    $poststr .= "&" . $key . "=" . urlencode($item);
                }
                $poststr = substr($poststr, 1);
            } else {
                $poststr = $post;
            }
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $poststr);
        }
        if ($debug) {
            curl_setopt($c, CURLOPT_HEADER, 1);
            curl_setopt($c, CURLINFO_HEADER_OUT, 1);
        }
        $content = trim(curl_exec($c));
        $info = curl_getinfo($c);
        curl_close($c);
        return $content;
    }

    /************************************* 下面测试一下几个post函数 ****************************************/

    public static function post1($url, $post_data = '', $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    public static function post2($url, $data)
    {
        $postdata = http_build_query(
            $data
        );
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public static function post3($host, $path, $query, $others = '')
    {
        $post = "POST $path HTTP/1.1\r\nHost: $host\r\n";
        $post .= "Content-type: application/x-www-form-";
        $post .= "urlencoded\r\n${others}";
        $post .= "User-Agent: Mozilla 4.0\r\nContent-length: ";
        $post .= strlen($query) . "\r\nConnection: close\r\n\r\n$query";
        $h = fsockopen($host, 80);
        fwrite($h, $post);
        for ($a = 0, $r = ''; !$a;) {
            $b = fread($h, 8192);
            $r .= $b;
            $a = (($b == '') ? 1 : 0);
        }
        fclose($h);
        return $r;

    }
}