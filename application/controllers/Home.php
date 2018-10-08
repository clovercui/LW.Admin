<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends AuthController {

	function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		$this->load->model('menu_model');
		$data['assets'] = $this->lw_assets->getHomeAssets();
		$data['menu'] = json_encode($this->menu_model->getSiteMenu());
		$this->_tpl_home($data);
	}

	public function testAjax()
	{
		lwReturn($this->rs);
	}

	public function welcome()
	{
		$this->_tpl_page('home/welcome');
	}

}
