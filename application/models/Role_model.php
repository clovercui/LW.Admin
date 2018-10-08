<?php

/**
 * Created by PhpStorm.
 * User=> liuwei
 * Date=> 2018/6/5
 * Time=> 下午4=>08
 */
class Role_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('lw_db', ['tb_name' => TB_ROLE], 'tb_role');
        $this->load->library('lw_db', ['tb_name' => TB_ROLE_POWER], 'tb_role_power');
        $this->load->library('lw_db', ['tb_name' => TB_METHOD], 'tb_method');
    }


    public function getListData()
    {
        $data['dataList'] = $this->getRoleList($this->session->roleId);
        return $data;
    }


    public function getEditData($id)
    {
        if ($id == 0) {
            $data['info'] = [
                'id' => 0,
                'name' => '',
                'parent_id' => 0
            ];
        } else {
            $data['info'] = $this->tb_role->get_one(['id' => $id]);
        }
        $data['roleList'] = $this->getRoleList($this->session->roleId);
        return $data;
    }

    public function edit($id, $post)
    {
        $field = lwCheckValue($post, ['name', 'parent_id']);
        if ($field === false) {
            $this->rs['msg'] = '参数缺失';
            return $this->rs;
        }
        $rowExist = $this->tb_role->check_exist(['name' => $field['name']], $id);
        if ($rowExist) {
            $this->rs['msg'] = '角色名称已经存在, 请重新输入';
            return $this->rs;
        }
        if ($id == 0) {
            $field['status'] = 1;
            $result = $this->tb_role->insert($field);
        } else {
            $result = $this->tb_role->update($field, ['id' => $id]);
        }
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '成功';
        } else {
            $this->rs['msg'] = $actionName . '失败';
        }
        return $this->rs;
    }

    public function getMenuList()
    {
        $menuList = $this->db->select('*')
            ->from(TB_MENU)
            ->where('status', 1)
            ->get()
            ->result_array();
        return $menuList;
    }

    public function del($id)
    {
        $this->load->library('lw_db', ['tb_name' => TB_ADMIN], 'tb_admin');
        // 如果当前角色在用,则不能删除
        $rowExist = $this->tb_admin->get_one(['role_id' => $id]);
        if ($rowExist) {
            $this->rs['msg'] = '当前角色正在被使用,角色删除失败';
            return $this->rs;
        }
        $result = $this->tb_role->delete(['id' => $id]);
        if ($result) {
            // 删除 表 tb_role_power 中对应的 role_id 记录
            $this->tb_role_power->delete(['role_id' => $id]);
            $this->rs['success'] = true;
            $this->rs['msg'] = '删除角色成功';
        } else {
            $this->rs['msg'] = '删除角色失败';
        }
        return $this->rs;
    }

    public function getRoleList($roleId = 0)
    {
        $roleId = $roleId == 0 ? $this->session->roleId : $roleId;
        $rowsRole = $this->tb_role->get_all('*', ['status' => 1]);
        $roleList = [];
        $this->roleChildList($rowsRole, $roleId, $roleList);
        return $roleList;
    }

    // 专门为 管理员做递归获取其下所有子角色
    public function roleChildList($rowsRole, $roleId, &$roleList,$depth = 1) {
        foreach($rowsRole as $role) {
            if($role['parent_id'] == $roleId) {
                $role['depth'] = $depth;
                $roleList[] = $role;
                $this->roleChildList($rowsRole, $role['id'], $roleList, $depth+1);
            }
        }
    }



    /**
     * 获取当前角色下所有的权限，便于给次级角色配置权限又不会越级配置，如果是默认角色既不会展示
     *
     * @param $roleId 角色ID
     * @return array 返回二维数组
     */
    public function getPowerData($roleId)
    {
        $powerData = [];
        $rowsController = $this->tb_method->get_all('*', ['parent_id' => 0, 'is_default' => 0], 'sort', 'asc');
        $rowRole = $this->tb_role->get_one(['id' => $roleId]);
        $rowParentRole = $this->tb_role->get_one(['id' => $rowRole['parent_id']]);
        foreach ($rowsController as $controller) {
            $powerController = ['id' => $controller['id'], 'text' => $controller['describe'] . '(' . $controller['name'] . ')'];
            if ($rowParentRole['parent_id'] == 0) {
                $sql = "SELECT
                            m.*, rp.id AS power_id
                        FROM
                            " . TB_METHOD . " m
                        LEFT JOIN " . TB_ROLE_POWER . " rp ON m.id = rp.method_id AND rp.role_id = {$roleId}
                        where m.parent_id = {$controller['id']} AND m.is_default = 0
                        AND m.status = 1
                        ORDER BY sort asc";
            } else {
                // 只列出当前角色的权限列表
                $parentRoleId = $rowParentRole['id'];
                $sql = "SELECT
                            m.*,rp2.id as power_id
                        FROM
                            " . TB_METHOD . " m
                        LEFT JOIN " . TB_ROLE_POWER . " rp1 on rp1.method_id = m.id
                        LEFT JOIN " . TB_ROLE_POWER . " rp2 on rp2.method_id = m.id AND rp2.role_id = {$roleId}
                        WHERE m.parent_id = {$controller['id']}
                        AND rp1.role_id = {$parentRoleId}
                        AND m.is_default = 0
                        AND m.status = 1";
            }
            $rowsMethod = $this->db->query($sql)->result_array();
            if ($rowsMethod) {
                $powerController['children'] = [];
                foreach ($rowsMethod as $method) {
                    $powerMethod = ['id' => $method['id'], 'text' => $method['describe'] . '(' . $method['name'] . ')'];
                    if ($method['power_id']) {
                        $powerMethod['state'] = ['selected' => true];
                    }
                    array_push($powerController['children'], $powerMethod);
                }
            }
            array_push($powerData, $powerController);
        }
        $data = [];
        for ($i = 0; $i < sizeof($powerData); $i++) {
            if (isset($powerData[$i]['children'])) {
                array_push($data, $powerData[$i]);
            }
        }
        return $data;
    }

    public function setPower($roleId, $post)
    {
        $post = lwCheckValue($post, ['rolePowerData']);
        if($post === false) {
            $this->rs['msg'] = '参数缺失';
            return $this->rs;
        }
        $rolePowerData = $post['rolePowerData'];
        $this->tb_role_power->delete(['role_id'=>$roleId]);
        foreach($rolePowerData as $power){
            // $rowMethod = $this->tb_method->get_one("id = {$power} AND parent_id <> 0");
            $rowMethod = $this->tb_method->get_one(['id' => $power, 'parent_id <>' => 0]);
            if($rowMethod) {
                $this->tb_role_power->insert(['role_id'=>$roleId,'method_id'=>$power]);
            }
        }
        $this->rs['success'] = true;
        $this->rs['msg'] = '管理员权限配置成功';
        return $this->rs;
    }
}