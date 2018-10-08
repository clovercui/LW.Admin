<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Method extends AuthController {

	function __construct()
	{
		parent::__construct();
		$this->load->model('method_model');
	}


	public function index()
	{
		$data['assets'] = $this->lw_assets->getPageAssets();
		$data['breadcrumb'] = [
            ['控制器管理',null]
        ];
		$indexData = $this->method_model->getIndexData();
		$data = array_merge($data, $indexData);
		$this->_tpl_page('method/index', $data);
	}

    public function lists($controllerId)
    {
        $data = $this->method_model->getListData($controllerId);
        $this->rs['html'] = $this->_view('method/list', $data, true);
        $this->rs['msg'] = '方法列表';
        $this->rs['success'] = true;
        lwReturn($this->rs);
    }

    /**
     * 添加控制器方法操作: type = 1 是控制器 type = 2 是方法
     * 初始化一个空的控制器或方法,然后编辑操作
     */
    public function add()
    {
        $type = $this->input->post('type');
        if($type == 1){
            $id = $this->method_model->methodInit();
        } else {
            $parentId = $this->input->post('parentId');
            $id = $this->method_model->methodInit($parentId);
        }
        $this->edit($id, 'add');
    }

    public function edit($id, $action = 'edit')
    {
        $post = $this->input->post();
        if(isset($post['doSubmit'])){
            // 提交
            $rs = $this->method_model->edit($id, $post);
            lwReturn($rs);
        } else {
            // 渲染视图
            $data = $this->method_model->getEditData($id);
            $data['action'] = $action;
            $data['actionName'] = $action === 'edit' ? '编辑' : '添加';
            $this->rs['success'] = true;
            $this->rs['msg'] = $data['actionName'] . '操作视图';
            $this->rs['html'] = $this->_view('method/edit', $data, true);
            lwReturn($this->rs);
        }
    }

    /**
     * 删除控制器或方法
     * type: 1 : 控制器 2: 方法
     * @param $id
     */
    public function del($id)
    {
        $type = $this->input->post('type');
        $rs = $this->method_model->del($id, $type);
        lwReturn($rs);
    }


    public function getControllerList()
    {
        $data = $this->method_model->getIndexData();
        $this->rs['controllerList'] = $data['controllerList'];
        $this->rs['success'] = true;
        $this->rs['msg'] = '控制器列表';
        lwReturn($this->rs);
    }

    /**
     * 关于 控制器方法绑定菜单操作 ----- BindMenu
     */


    /**
     * @param $methodId methodId 方法Id
     */
    public function bindMenuIndex($methodId)
    {
        $data['methodId'] = $methodId;
        $this->rs['html'] = $this->_view('method/bind_menu_index', $data, true);
        $this->rs['success'] = true;
        $this->rs['msg'] = '捆绑菜单主页';
        lwReturn($this->rs);
    }

    public function bindMenuAdd($methodId)
    {
        // 初始化一条方法菜单
        $id = $this->method_model->initBindMenu($methodId);
        $this->bindMenuEdit($id, 'add');
    }

    /**
     * 添加或编辑 方法绑定的菜单
     *
     * @param $id tb_method_menu 主键
     * @param string $action add 添加 edit 编辑
     * @return array 操作结果
     */
    public function bindMenuEdit($id, $action = 'edit')
    {
        $post = $this->input->post();
        $actionName = $action == 'edit' ? '编辑' : '添加';
        if(isset($post['doSubmit'])) {
            // ajax请求
            $rs = $this->method_model->bingMenuEdit($id, $post, $actionName);
            lwReturn($rs);
        } else {
            // 视图请求
            $data = $this->method_model->getBindMenuEditData($id);
            $data['action'] = $action;
            $data['actionName'] = $actionName;
            $this->rs['html'] = $this->_view('method/bind_menu_edit', $data, true);
            $this->rs['success'] = true;
            $this->rs['msg'] = '获取' . $actionName . '视图';
            lwReturn($this->rs);
        }
    }

    /**
     * 方法菜单列表
     * @param $methodId
     */
    public function bindMenuList($methodId)
    {
        $data['dataList'] = $this->method_model->getMethodMenuList($methodId);
        $this->rs['html'] = $this->load->view('method/bind_menu_list',$data,true);
        $this->rs['success'] = true;
        $this->rs['msg'] = '获取方法绑定的菜单列表';
        lwReturn($this->rs);
    }

    /**
     * 删除方法菜单
     *
     * @param $id
     */
    public function bindMenuDel($id)
    {
        $rs = $this->method_model->bindMenuDel($id);
        lwReturn($rs);
    }


}
