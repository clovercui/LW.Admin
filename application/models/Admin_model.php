<?php

/**
 * 管理员 MODEL
 */

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('lw_db', ['tb_name' => TB_ADMIN], 'tb_admin');
    }

    public function getIndexData() {
        $this->load->model('role_model');
        $data['roleList'] = $this->role_model->getRoleList();
        return $data;
    }


    public function getListData($page, $post)
    {
        $this->load->library('lw_pagination');
        $param = $post['param'];
        $sql = "SELECT
                    a.*, b.name as role_name
                FROM
                    " . TB_ADMIN . " a
                LEFT JOIN " . TB_ROLE . " b on a.role_id = b.id
                WHERE a.status > " . DEL_STATUS;
        $hasWhere = true;
        $group = null;
        $order = "a.id desc";
        $paramFilter = ['a.loginname', 'a.username'];
        $data = $this->lw_pagination->lists($sql, $param, $page, $hasWhere, $group, $order, $paramFilter, $pageSize = 10);
        return $data;
    }

    public function getEditData($id)
    {
        if ($id == 0) {
            $data['info'] = [
                'id' => 0,
                'loginname' => '',
                'username' => '',
                'password' => '',
                'role_id' => 0
            ];
        } else {
            $data['info'] = $this->tb_admin->get_one(['id' => $id]);
        }
        $this->load->model('role_model');
        $data['roleList'] = $this->role_model->getRoleList($this->session->roleId);
        return $data;
    }

    public function edit($id, $post)
    {
        $field = lwCheckValue($post, ['loginname', 'username', 'password', 'role_id']);
        if ($field === false) {
            $this->rs['msg'] = '参数缺失';
            return $this->rs;
        }
        // 检测登录名唯一性
        $rowExist = $this->tb_admin->check_exist("loginname = '{$field['loginname']}' and status > " . DEL_STATUS, $id);
        if ($rowExist) {
            $this->rs['msg'] = '当前登录名已经存在,请重新输入';
            return $this->rs;
        }
        $this->load->library('lw_regex');
        if ($this->lw_regex->checkPassword($field['password']) === false) {
            $this->rs['msg'] = '密码必须是大于6位数字字母的组合';
            return $this->rs;
        }
        $field['record_time'] = time();
        $actionName = $id == 0 ? '添加' : '编辑';
        if ($id == 0) {
            $field['status'] = 1;
            $result = $id = $this->tb_admin->insert($field);
        } else {
            $adminInfo = $this->tb_admin->get_one(['id' => $id]);
            // 密码检测
            if ($field['password'] === $adminInfo['password']) {
                // 没有修改过密码,则不处理
                unset($field['password']);
            } else {
                // 做 安全码 与 密码的组合, 非明文存储
                $field['password'] = md5($adminInfo['security_code'] . $field['password']);
            }
            $result = $this->tb_admin->update($field, ['id' => $id]);
        }
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '成功';
            $this->rs['id'] = $id;
        } else {
            $this->rs['msg'] = '管理员操作失败';
        }
        return $this->rs;
    }

    /**
     * 设置 管理员 状态
     * $id TB_ADMIN 主键
     * $status 状态值
     * $actionName 操作名称
     */
    public function setStatus($id, $status, $actionName = '操作') {
        $field = ['status' => $status];
        $where = ['id' => $id];
        $result = $this->tb_admin->update($field, $where);
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '成功';
        } else {
            $this->rs['msg'] = $actionName . '失败';
        }
        return $this->rs;
    }


    /**
     * 登录获取管理员信息
     *
     * @param $loginname
     * @param $password
     * @return array
     */
    public function getAdminInfoByLogin($loginname, $password)
    {
        $row = $this->db->select(TB_ADMIN.'.*,' . TB_ROLE . '.parent_id, ' . TB_ROLE . '.name as role_name')
            ->from(TB_ADMIN)
            ->join(TB_ROLE, TB_ADMIN . '.role_id = ' . TB_ROLE . '.id', 'left')
            ->where([TB_ADMIN . '.loginname' => $loginname])
            ->get()
            ->row_array();
        if (!$row) {
            $this->rs['msg'] = '管理员登录名不存在';
            return $this->rs;
        }
        $securityCode = $row['security_code'];
        $inputPwd = md5($securityCode . $password);
        if ($inputPwd != $row['password']) {
            $this->rs['msg'] = '管理员密码错误';
            return $this->rs;
        }
        if ($row['status'] == 0) {
            $this->rs['msg'] = '当前管理员已锁定, 请联系管理员';
            return $this->rs;
        }
        $row['topRole'] = $row['parent_id'] == 0 ? true : false;
        $this->rs['success'] = true;
        $this->rs['msg'] = '管理员登录成功';
        $this->rs['info'] = $row;
        return $this->rs;
    }

}