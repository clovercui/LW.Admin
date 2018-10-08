<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends AuthController {

	function __construct()
	{
		parent::__construct();
		$this->load->model('role_model');
	}


	public function index()
	{
		$data['assets'] = $this->lw_assets->getPageAssets(['jstree']);
		$data['breadcrumb'] = [
            ['角色管理',null]
        ];
		$this->_tpl_page('role/index', $data);
	}

    public function lists()
    {
        $data = $this->role_model->getListData();
        $this->rs['html'] = $this->_view('role/list', $data, true);
        $this->rs['msg'] = '列表';
        $this->rs['success'] = true;
        lwReturn($this->rs);
    }

    /**
     * 添加控制器方法操作: type = 1 是控制器 type = 2 是方法
     * 初始化一个空的控制器或方法,然后编辑操作
     */
    public function add()
    {
        $this->edit(0);
    }

    public function edit($id)
    {
        $post = $this->input->post();
        if(isset($post['doSubmit'])){
            // 提交
            $rs = $this->role_model->edit($id, $post);
            lwReturn($rs);
        } else {
            // 渲染视图
            $actionName = $id === 0 ? '添加角色' : '编辑角色';
            $data = $this->role_model->getEditData($id);
            $data['actionName'] = $actionName;
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '视图';
            $this->rs['html'] = $this->_view('role/edit', $data, true);
            lwReturn($this->rs);
        }
    }

    public function del($id) {
        $rs = $this->role_model->del($id);
        lwReturn($rs);
    }

    public function getRolePowerData($roleId)
    {
        $this->rs['success'] = true;
        $this->rs['msg'] = '权限管理数据';
        $this->rs['powerData'] = $this->role_model->getPowerData($roleId);
        lwReturn($this->rs);
    }

    public function setPower($roleId)
    {
        $post = $this->input->post();
        if(isset($post['doSubmit'])){
            $rs = $this->role_model->setPower($roleId, $post);
            lwReturn($rs);
        }else{
            $data['roleId'] = $roleId;
            $this->rs['success'] = true;
            $this->rs['msg'] = '配置权限界面';
            $this->rs['html'] = $this->load->view('role/set_power',$data,true);
            lwReturn($this->rs);
        }
    }


}
