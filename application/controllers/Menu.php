<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends AuthController {

	function __construct()
	{
		parent::__construct();
		$this->load->model('menu_model');
	}


	public function index()
	{
		$data['assets'] = $this->lw_assets->getPageAssets();
		$data['breadcrumb'] = [
            ['菜单管理',null]
        ];
		$this->_tpl_page('menu/index', $data);
	}

    public function lists($page)
    {
        $data = $this->menu_model->getListData($page);
        $this->rs['html'] = $this->_view('menu/list', $data, true);
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
            $rs = $this->menu_model->edit($id, $post);
            lwReturn($rs);
        } else {
            // 渲染视图
            $actionName = $id === 0 ? '添加菜单' : '编辑菜单';
            $data = $this->menu_model->getEditData($id);
            $data['actionName'] = $actionName;
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '操作视图';
            $this->rs['html'] = $this->_view('menu/edit', $data, true);
            lwReturn($this->rs);
        }
    }

    public function del($id) {
        $rs = $this->menu_model->del($id);
        lwReturn($rs);
    }

}
