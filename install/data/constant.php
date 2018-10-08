<?php 


/**
 * 系统常量
 */
defined('IS_DEBUG')         OR define('IS_DEBUG', false); // 开启测试
defined('WEB_NAME')         OR define('WEB_NAME', '===WEB_NAME==='); // 网站名称
defined('WEB_TITLE')        OR define('WEB_TITLE', '<b>LW</b>Admin'); // 网站名称
defined('WEB_TITLE_SHORT')  OR define('WEB_TITLE_SHORT', '<b>L</b>Ad'); // 网站名称
defined('WEB_COMPANY')      OR define('WEB_COMPANY', 'LW工作室'); // 版权组织

/**
 * 主题常量
 * blue, black, purple, green, red, yellow, blue-light, black-light, purple-light, green-light, red-light, yellow-light
 */
defined('THEME')            OR define('THEME', 'purple'); // 主题
// 请求类型
defined('IS_GET')           OR define('IS_GET', strtolower($_SERVER['REQUEST_METHOD']) == 'get'); // get 请求
defined('IS_POST')          OR define('IS_POST', strtolower($_SERVER['REQUEST_METHOD']) == 'post'); // post 请求
defined('IS_AJAX')          OR define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'); // ajax 请求
defined('VERSION')          OR define('VERSION', '0.1'); // ajax 请求

defined('DEL_STATUS')       OR define('DEL_STATUS', -99); // 整个系统统一使用 删除状态 -99

// 默认头像路径
defined('AVATAR_URL')       OR define('AVATAR_URL', 'assets/img/avatar.jpg');

// true 则隐藏 index.php 反之 false
// 注意: 如果 true 服务器需要配置 URL 重写来隐藏 入口文件
defined('IS_REWRITE')       OR define('IS_REWRITE', false);

/**
 * 表名常量
 */

/*
 * 框架权限系统所需数据表
*/
defined('TB_ADMIN')         OR define('TB_ADMIN', '#__admin');
defined('TB_MENU')          OR define('TB_MENU', '#__menu');
defined('TB_METHOD')        OR define('TB_METHOD', '#__method');
defined('TB_METHOD_MENU')   OR define('TB_METHOD_MENU', '#__method_menu');
defined('TB_ROLE')          OR define('TB_ROLE', '#__role');
defined('TB_ROLE_POWER')    OR define('TB_ROLE_POWER', '#__role_power');
defined('TB_SYS_LOG')       OR define('TB_SYS_LOG', '#__sys_log');