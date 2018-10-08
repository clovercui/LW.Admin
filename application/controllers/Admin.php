<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends AuthController {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
	}


    /**
     * 管理员管理--主页
     */
	public function index()
	{
		$data['assets'] = $this->lw_assets->getPageAssets();
        $data['breadcrumb'] = [
            ['控制器管理',null]
        ];
        $indexData = $this->admin_model->getIndexData();
        $data = array_merge($data, $indexData);
		$this->_tpl_page('admin/index', $data);
	}

    /**
     * 管理员管理--列表
     *
     * @param $page 页数
     */
    public function lists($page)
    {
        $post = $this->input->post();
        $data = $this->admin_model->getListData($page, $post);
        $this->rs['html'] = $this->_view('admin/list', $data, true);
        $this->rs['msg'] = '列表';
        $this->rs['success'] = true;
        lwReturn($this->rs);
    }

    /**
     * 管理员管理--添加操作
     * 添加控制器方法操作: type = 1 是控制器 type = 2 是方法
     * 初始化一个空的控制器或方法,然后编辑操作
     */
    public function add()
    {
        $this->edit(0);
    }

    /**
     * 管理员管理--编辑操作
     * @param $id TB_ADMIN 主键
     */
    public function edit($id)
    {
        $post = $this->input->post();
        if(isset($post['doSubmit'])){
            // 提交
            $rs = $this->admin_model->edit($id, $post);
            lwReturn($rs);
        } else {
            // 渲染视图
            $actionName = $id === 0 ? '管理员编辑' : '管理员添加';
            $data = $this->admin_model->getEditData($id);
            $data['actionName'] = $actionName;
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '操作';
            $this->rs['html'] = $this->_view('admin/edit', $data, true);
            lwReturn($this->rs);
        }
    }

    /**
     * 管理员管理--删除
     * @param $id TB_ADMIN 主键
     */
    public function del($id) {
        $rs = $this->admin_model->setStatus($id, DEL_STATUS, '删除');
        lwReturn($rs);
    }

}
