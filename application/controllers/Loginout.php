<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loginout extends BaseController {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['assets'] = $this->lw_assets->getLoginAssets();
		$this->_tpl_login($data);
	}

	public function login()
	{
		$post = $this->input->post();
		// ajax 提交
		$this->load->model('admin_model');
		// 验证验证码
		$this->load->library('lw_verify');
		$rs = $this->lw_verify->checkVerifyCode($post['verifyCode']);
		if($rs['success'] !==  true) {
			lwReturn($rs);
		}
		$rs = $this->admin_model->getAdminInfoByLogin($post['loginname'], $post['password']);
		if($rs['success'] === true) {
			// 将用户信息录入到 session 中去.
			$adminInfo = $rs['info'];
			$this->session->set_userdata('adminId', $adminInfo['id']);
			$this->session->set_userdata('loginname', $adminInfo['loginname']);
			$this->session->set_userdata('adminName', $adminInfo['username']);
			$this->session->set_userdata('roleId', $adminInfo['role_id']);
			$this->session->set_userdata('roleName', $adminInfo['role_name']);
			$this->session->set_userdata('topRole', $adminInfo['topRole']);
		}
		lwReturn($rs);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		$this->rs['success'] = true;
		$this->rs['msg'] = '退出成功';
		lwReturn($this->rs);
	}


	public function getVerifyCode()
	{
		$this->load->library('lw_verify');
		$cap = $this->lw_verify->generateCode();
		if($cap) {
			$this->rs['success'] = true;
			$this->rs['msg'] = '获取验证码成功';
			$this->rs['html'] = $cap['image'];
		} else {
			$this->rs['msg'] = '获取验证码失败';
		}
		lwReturn($this->rs);
	}

}
