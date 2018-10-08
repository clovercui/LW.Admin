<?php

/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 2018/3/10
 * Time: 下午11:13
 */
class LW_Controller extends CI_Controller
{

    protected $rs;

    function __construct()
    {
        parent::__construct();
        if (IS_DEBUG) {
            // 开启测试模式
            $this->output->enable_profiler(TRUE);
        } else {
            // 记录访问日志
            // $this->lw_log->visitLog();
        }
        $this->rs = array('success' => false, 'msg' => '');
    }

    /**
     * 视图渲染
     *
     * @param $template
     * @param null $data
     * @param bool|false $return
     */
    protected function _view($template, $data = null, $return = false)
    {
        if ($return) {
            return $this->load->view($template, $data, $return);
        } else {
            $this->load->view($template, $data);
        }

    }

    /**
     * 登录页模板
     *
     * @param null $data
     */
    protected function _tpl_login($data = null)
    {
        $this->load->view('tpl/login', $data);
    }

    /**
     * 主页模板
     *
     * @param null $data
     */
    protected function _tpl_home($data = null)
    {
        $this->load->view('tpl/home', $data);
    }

    /**
     * 内容页模板
     *
     * @param $template
     * @param null $data
     */
    protected function _tpl_page($template, $data = null)
    {
        $currentData['tpl'] = $this->load->view($template, $data, true);
        $this->load->view('tpl/page', $currentData);
    }
}

/**
 * Class BaseController
 * 常规操作的Controller
 */
class BaseController extends LW_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}

/**
 * Class AdminController
 * 带有权限校验的操作的Controller
 */

class AuthController extends LW_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 鉴权
        $this->load->library('lw_auth');
        $this->lw_auth->check();
    }
}

// 在 LW_Controller 中可以做一些初始化的工作
// 引用继承了当前重写 LW_Controller 的 REST_Controller
require APPPATH . 'core/REST_Controller.php';
class API_Controller extends REST_Controller
{

    protected $rs;

    function __construct()
    {
        parent::__construct();
        $this->rs = [
            'status' => 0,
            'msg' => '',
            'data' => null
        ];
    }

    // 全局过滤参数
    public function checkNull($value)
    {
        if($value === null) {
            $this->rs['status'] = 0;
            $this->rs['msg'] = '参数缺失';
            $this->response($this->rs, REST_Controller::HTTP_OK);
            exit;
        }
        return $value;
    }

}
