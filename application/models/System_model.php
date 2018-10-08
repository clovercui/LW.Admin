<?php

/**
 * Class System_model 主要针对后台系统层面的数据库操作
 * 1. 登录校验
 * 2. 权限校验
 */
class System_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
}