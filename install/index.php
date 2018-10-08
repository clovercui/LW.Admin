<?php
set_time_limit(0);   //设置运行时间
error_reporting(E_ALL & ~E_NOTICE);  //显示全部错误
define('ROOT_PATH', dirname(dirname(__FILE__)));  //定义根目录
define('DBCHARSET','UTF8');   //设置数据库默认编码
if(function_exists('date_default_timezone_set')){
	date_default_timezone_set('Asia/Shanghai');
}
input($_GET);
input($_POST);
function input(&$data){
	foreach ((array)$data as $key => $value) {
		if(is_string($value)){
			if(!get_magic_quotes_gpc()){
				$value = htmlentities($value, ENT_NOQUOTES);
                $value = addslashes(trim($value));
			}
		}else{
			$data[$key] = input($value);
		}
	}
}
//判断是否安装过程序
if(is_file('lock') && $_GET['step'] != 5){
	@header("Content-type: text/html; charset=UTF-8");
    echo "系统已经安装过了，如果要重新安装，那么请删除install目录下的lock文件";
    exit;
}

$html_title = '程序安装向导';
$html_header = <<<EOF
<div class="header">
  <div class="layout">
    <div class="title">
      <h5>LW.Admin管理系统</h5>
      <h2>系统安装向导</h2>
    </div>
    <div class="version">版本: 20181005</div>
  </div>
</div>
EOF;

$html_footer = <<<EOF
<div class="footer">
  <h5>Powered by <font class="blue">LW.Admin</font><font class="orange"></font></h5>
  <h6>版权所有 2016-2018 &copy; <a href="http://www.lwadmin.cn" target="_blank">LW.Admin</a></h6>
</div>
EOF;
require('./include/function.php');
if(!in_array($_GET['step'], array(1,2,3,4,5))){
	$_GET['step'] = 0;
}
switch ($_GET['step']) {
	case 1:
		require('./include/var.php');
		env_check($env_items);
        dirfile_check($dirfile_items);
        function_check($func_items);
		break;
	case 3:
		$install_error = '';
        $install_recover = '';
        $demo_data =  file_exists('./data/utf8_add.sql') ? false : false;
        step3($install_error,$install_recover);
        break;
	case 4:
		
		break;
	case 5:
		$sitepath = strtolower(substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
        $sitepath = str_replace('install',"",$sitepath);
        $auto_site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].$sitepath);

		break;
	default:
		# code...
		break;
}

include ("step_{$_GET['step']}.php");

function step3(&$install_error,&$install_recover){
    global $html_title,$html_header,$html_footer;
    if ($_POST['submitform'] != 'submit') return;
    $db_host = $_POST['db_host'];
    $db_port = $_POST['db_port'];
    $db_user = $_POST['db_user'];
    $db_pwd = $_POST['db_pwd'];
    $db_name = $_POST['db_name'];
    $db_prefix = $_POST['db_prefix'];
    $admin = $_POST['admin'];
    $password = $_POST['password'];
    if (!$db_host || !$db_port || !$db_user || !$db_pwd || !$db_name || !$db_prefix || !$admin || !$password){
        $install_error = '输入不完整，请检查';
    }
    if(strpos($db_prefix, '.') !== false) {
        $install_error .= '数据表前缀为空，或者格式错误，请检查';
    }

    if(strlen($admin) > 15 || preg_match("/^$|^c:\\con\\con$|　|[,\"\s\t\<\>&]|^游客|^Guest/is", $admin)) {
        $install_error .= '非法用户名，用户名长度不应当超过 15 个英文字符，且不能包含特殊字符，一般是中文，字母或者数字';
    }
    if ($install_error != '') reutrn;
        $mysqli = @ new mysqli($db_host, $db_user, $db_pwd, '', $db_port);
        if($mysqli->connect_error) {
            $install_error = '数据库连接失败';return;
        }

    if($mysqli->get_server_info()> '5.0') {
        $mysqli->query("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET ".DBCHARSET);
    } else {
        $install_error = '数据库必须为MySQL5.0版本以上';return;
    }
    if($mysqli->error) {
        $install_error = $mysqli->error;return ;
    }
    if($_POST['install_recover'] != 'yes' && ($query = $mysqli->query("SHOW TABLES FROM $db_name"))) {
        while($row = mysqli_fetch_array($query)) {
            if(preg_match("/^$db_prefix/", $row[0])) {
                $install_error = '数据表已存在，继续安装将会覆盖已有数据';
                $install_recover = 'yes';
                return;
            }
        }
    }

    require ('step_4.php');
    $sitepath = strtolower(substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
    $sitepath = str_replace('install',"",$sitepath);
    $auto_site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].$sitepath);
    $domain = $_SERVER['HTTP_HOST'].$sitepath;
    // 过滤domain末的 / 字符
    $domain = substr($domain, -1, 1) == '/' ? substr($domain, 0, -1) : $domain;
    // 写入配置文件
    write_config($domain);
    
    $_charset = strtolower(DBCHARSET);
    $mysqli->select_db($db_name);
    $mysqli->set_charset($_charset);
    $sql = file_get_contents("data/lwadmin.sql");
    $sql = str_replace("\r\n", "\n", $sql);
     /**
     * 录入默认数据
     */
    $securityCode = 'lwlw';
    $adminName = $_POST['admin'];
    $password = md5($securityCode.$_POST['password']);
    $sql = str_replace('===adminName===', $adminName, $sql);
    $sql = str_replace('===securityCode===', $securityCode, $sql);
    $sql = str_replace('===password===', $password, $sql);
    $sql = str_replace('===record_timestamp===', time(), $sql);
    runquery($sql,$db_prefix,$mysqli);

    showjsmessage('初始化数据 ... 成功 ');

    //新增一个标识文件，用来屏蔽重新安装
    $fp = @fopen('lock','wb+');
    @fclose($fp);
    exit("<script type=\"text/javascript\">document.getElementById('install_process').innerHTML = '安装完成，下一步...';document.getElementById('install_process').href='index.php?step=5&sitename={$sitename}&username={$username}&password={$password}';</script>");
    exit();
}

//execute sql
function runquery($sql, $db_prefix, $mysqli) {
    if(!isset($sql) || empty($sql)) return;
    $sql = str_replace("\r", "\n", str_replace('#__', $db_prefix, $sql));
    $ret = array();
    $num = 0;
    foreach(explode(";\n", trim($sql)) as $query) {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        foreach($queries as $query) {
            $ret[$num] .= (isset($query[0]) && $query[0] == '#') || (isset($query[1]) && isset($query[1]) && $query[0].$query[1] == '--') ? '' : $query;
        }
        $num++;
    }
    unset($sql);
    foreach($ret as $query) {
        $query = trim($query);
        if($query) {
            if(substr($query, 0, 12) == 'CREATE TABLE') {
                $line = explode('`',$query);
                $data_name = $line[1];
                showjsmessage('数据表  '.$data_name.' ... 创建成功');
                $mysqli->query(droptable($data_name));
                $mysqli->query($query);
                unset($line,$data_name);
            } elseif (substr($query, 0, 11) == 'INSERT INTO') {
                $line = explode('`',$query);
                $data_name = $line[1];
                showjsmessage('数据表  '.$data_name.' ... 录入数据成功');
                $mysqli->query($query);
                unset($line,$data_name);
            } else {
                $mysqli->query($query);
            }
        }
    }
}
//抛出JS信息
function showjsmessage($message) {
    echo '<script type="text/javascript">showmessage(\''.addslashes($message).' \');</script>'."\r\n";
    flush();
    ob_flush();
}
//写入config文件
function write_config($domain) {
    extract($GLOBALS, EXTR_SKIP);
    // 重写配置文件
    $config = 'data/config.php';
    $configFile = @file_get_contents($config);
    $configFile = trim($configFile);
    $configFile = substr($configFile, -2) == '?>' ? substr($configFile, 0, -2) : $configFile;
    $charset = 'UTF-8';
    // 替换默认域名
    $configFile = str_replace("===domain===",  $domain, $configFile);
    @file_put_contents('../conf/config.php', $configFile);
    // 重写 数据库配置文件
    $database = 'data/database.php';
    $databaseFile = @file_get_contents($database);
    $databaseFile = trim($databaseFile);
    $databaseFile = substr($databaseFile, -2) == '?>' ? substr($databaseFile, 0, -2) : $databaseFile;
    $charset = 'UTF-8';
    // 服务器IP
    $host = $_POST['db_host'];
    // 端口号
    $port = $_POST['db_port'];
    // 数据库用户名
    $username = $_POST['db_user'];
    // 数据库密码
    $password = $_POST['db_pwd'];
    // 数据库
    $database = $_POST['db_name'];
    $databaseFile = str_replace("===host===",      $host, $databaseFile);
    $databaseFile = str_replace("===port===",      $port, $databaseFile);
    $databaseFile = str_replace("===username===",  $username, $databaseFile);
    $databaseFile = str_replace("===password===",  $password, $databaseFile);
    $databaseFile = str_replace("===database===",  $database, $databaseFile);
    @file_put_contents('../conf/database.php', $databaseFile);
    // 重写 常量配置文件
    $constant = 'data/constant.php';
    $constantFile = @file_get_contents($constant);
    $constantFile = trim($constantFile);
    $constantFile = substr($constantFile, -2) == '?>' ? substr($constantFile, 0, -2) : $constantFile;
    $charset = 'UTF-8';
    // 数据库前缀
    $prefix = $_POST['db_prefix'];
    // 网站名称
    $webName = $_POST['site_name'];
    $constantFile = str_replace("#__",      $prefix, $constantFile);
    $constantFile = str_replace("===WEB_NAME===",      $webName, $constantFile);
    @file_put_contents('../conf/constant.php', $constantFile);
}