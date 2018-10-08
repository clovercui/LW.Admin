<?php

class Lw_auth
{
    protected $CI;
    protected $rs;
    protected $ip;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('lw_db', ['tb_name' => TB_METHOD], 'tb_method');
        $this->CI->load->library('lw_db', ['tb_name' => TB_ROLE_POWER], 'tb_role_power');
        $this->CI->load->library('lw_db', ['tb_name' => TB_ROLE], 'tb_role');
        $this->rs = ['success' => false,'msg' => ''];
        $this->setIp();
    }

    private function setIp()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $this->ip = getenv("HTTP_CLIENT_IP");
        } else if(getenv("HTTP_X_FORWARDED_FOR")) {
            $this->ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if(getenv("REMOTE_ADDR")){
            $this->ip = getenv("REMOTE_ADDR");
        } else{
            $this->ip = "Unknow";
        }
    }

    public function check()
    {
        $segments = $this->CI->uri->segments;// 获取URL参数
        // 登录状态校验
        $this->checkLogined($segments);
        // 管理员权限校验
        $this->checkPower($segments);
    }

    protected function checkLogined($segments)
    {
        // 登录状态正常,返回即可
        if ($this->CI->session->adminId != null && $this->CI->session->adminId != '') {
            return;
        }
        // 获取当前的请求路径参数——控制器和方法）
        $controller = (isset($segments[1])) ? strtolower($segments[1]) :'home';
        $method = (isset($segments[2])) ? strtolower($segments[2]) : 'index';
        if(IS_AJAX){
            // 如果是 ajax 请求返回 json
            $rs = [
                'success' => false,
                'msg' => '登录状态失效,请重新登录',
                'relogin' => true
            ];
            lwReturn($rs);
        } else {
            // 如果是 非 ajax 请求返回 跳转页面即可.
            $backUrl = site_url(join('/', $segments));
            redirect('loginout/index?backUrl='.$backUrl);
        }
    }

    public function checkPower($segments)
    {
        // 1.最高权利者——不需要鉴权
        /*if($this->CI->session->topRole === true) {
            return;
        }*/
        // 2.获取当前的请求路径参数——控制器和方法
        $controller = (isset($segments[1])) ? strtolower($segments[1]) :'home';
        $method = (isset($segments[2])) ? strtolower($segments[2]) : 'index';
        $isPoweredRs = $this->checkPowerRecord($controller, $method);
        // 3.如果控制器或方法没有录入到系统中,则不需要鉴权
        if($isPoweredRs['success']  === false) {
            return;
        }
        $controllerInfo = $isPoweredRs['controllerInfo'];
        $methodInfo = $isPoweredRs['methodInfo'];
        $methodId = $methodInfo['id'];
        // 4.默认控制器或方法校验
        if($controllerInfo['is_default'] == 1 || $methodInfo['is_default'] == 1) {
            return;
        }
        // 5.检测管理员是否具有当前操作权限
        $roleId = $this->CI->session->roleId;
        $hasPower = $this->checkPowerRole($roleId, $methodId);
        if($hasPower === true || $this->CI->session->topRole === true) {
            // 如果有权限, 判断一下是否需要记录
            $this->recordLog($controllerInfo, $methodInfo, $segments);
            return;
        }
        // 6.没有权限操作
        $message = '你没有权限执行'.$controllerInfo['name'].'-'.$methodInfo['name'].'操作';
        if(IS_AJAX) {
            // 如果是 ajax 请求返回 json
            $rs = [
                'success' => false,
                'msg' => $message,
                'noPower' => true
            ];
            lwReturn($rs);
        } else {
            show_error($message, 403, '403 你无权访问');
        }
    }

    /**
     * 判断 控制器 或者 方法 是否录入到系统中
     * 注意: 鉴权模块只针对录入到系统中的控制器方法鉴权
     * @param $controller 控制器名（小写）
     * @param $method 方法名（小写）
     * @return array true: 已经录入到系统 false: 没有录入到系统
     */
    private function checkPowerRecord($controller, $method)
    {
        $rowController = $this->CI->tb_method->get_one(['name' => $controller, 'parent_id' => 0]);
        if(!$rowController) {
            return $this->rs;
        }
        $rowMethod = $this->CI->tb_method->get_one(['name' => $method, 'parent_id' => $rowController['id']]);
        if(!$rowMethod) {
            return $this->rs;
        }
        $this->rs['success'] = true;
        $this->rs['controllerInfo'] = $rowController;
        $this->rs['methodInfo'] = $rowMethod;
        return $this->rs;
    }

    /**
     * 通过角色ID 判断角色是否拥有当前操作的权限
     *
     * @param $roleId      TB_ROLE 主键
     * @param $methodId      方法名（小写）
     * @return bool        true: 拥有权限 false: 没有权限
     */
    private function checkPowerRole($roleId, $methodId) {
        // 如果当前权限不存在,则判断为没有权限
        if(!$this->CI->tb_role->get_one(['id' => $roleId])) {
            return false;
        }
        $row = $this->CI->tb_role_power->get_one(['role_id' => $roleId, 'method_id' => $methodId]);
        return $row !== null ? true : false;
    }


    private function recordLog($controllerInfo, $methodInfo, $segments)
    {
        if($controllerInfo['is_log'] == 0 && $methodInfo['is_log'] == 0) {
            return;
        }
        $this->CI->load->library('lw_db', ['tb_name' => TB_SYS_LOG], 'tb_sys_log');
        $field = [
            'admin_id' => $this->CI->session->adminId,
            'admin_name' => $this->CI->session->adminName,
            'ip' => $this->ip,
            'controller_id' => $controllerInfo['id'],
            'controller_name' => $controllerInfo['name'],
            'controller_describe' => $controllerInfo['describe'],
            'method_id' => $methodInfo['id'],
            'method_name' => $methodInfo['name'],
            'method_describe' => $methodInfo['describe'],
            'get_params' => json_encode($segments),
            'post_params' => json_encode($this->CI->input->post()),
            'record_time' => time()
        ];
        $this->CI->tb_sys_log->insert($field);
    }
}
