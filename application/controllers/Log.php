<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends AuthController {

	function __construct()
	{
		parent::__construct();
		$this->load->model('log_model');
	}


    /**
     * 管理员管理--主页
     */
	public function index()
	{
		$data['assets'] = $this->lw_assets->getPageAssets(['datetime']);
		$data['breadcrumb'] = [
            ['日志管理',null]
        ];
        $indexData = $this->log_model->getIndexData();
        $data = array_merge($data, $indexData);
		$this->_tpl_page('log/index', $data);
	}

    /**
     * 管理员管理--列表
     *
     * @param $page 页数
     */
    public function lists($page)
    {
        $post = $this->input->post();
        $rs = $this->log_model->getList('list', $post, $page);
        if($rs['success'] === false) {
            lwReturn($rs);
        }
        $data = $rs['data'];
        $this->rs['html'] = $this->_view('log/list', $data, true);
        $this->rs['msg'] = '列表';
        $this->rs['success'] = true;
        lwReturn($this->rs);
    }

    public function excel()
    {
        $post = $this->input->post();
        $rs = $this->log_model->getList('excel', $post);
        lwReturn($rs);
    }


    public function detail($id)
    {
        $data = $this->log_model->getInfoData($id);
        $this->rs['success'] = true;
        $this->rs['msg'] = '日志详情';
        $this->rs['html'] = $this->load->view('log/detail', $data, true);
        lwReturn($this->rs);
    }
}
