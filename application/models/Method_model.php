<?php
/**
 * 控制器方法 MODEL
 * 
 * 系统数据模型，主要是一些逻辑上比较复杂的实现后台系统功能的数据模型
 */

class Method_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('lw_db', ['tb_name' => TB_METHOD], 'tb_method');
        $this->load->library('lw_db', ['tb_name' => TB_METHOD_MENU], 'tb_method_menu');
        $this->load->library('lw_db', ['tb_name' => TB_ROLE_POWER], 'tb_role_power');
    }

    public function getIndexData()
    {
        $data['controllerList'] = $this->db->select('*')
            ->from(TB_METHOD)
            ->where('parent_id', 0)
            ->where('status > ' . DEL_STATUS)
            ->get()
            ->result_array();
        return $data;
    }


    public function getListData($controllerId)
    {
        $data['controllerInfo'] = $this->tb_method->get_one(['id' => $controllerId]);
        $data['dataList'] = $this->db->select('*')
            ->from(TB_METHOD)
            ->where('parent_id', $controllerId)
            ->where('status > ' . DEL_STATUS)
            ->get()
            ->result_array();
        return $data;
    }

    /**
     * 初始化一个空的控制器或方法
     *
     * @param int $parentId 0:控制器初始化 非零: 控制器方法初始化
     *
     * @return int 控制器ID
     */
    public function methodInit($parentId = 0)
    {
        $field = [
            'parent_id' => $parentId,
            'status' => DEL_STATUS
        ];
        $controllerId = $this->tb_method->insert($field);
        return $controllerId;
    }

    public function getEditData($methodId)
    {
        $info = $this->tb_method->get_one(['id' => $methodId]);
        // 如果是方法编辑,还需要获取控制器列表
        if ($info['parent_id'] != 0) {
            $data['controllerList'] = $this->db->select('*')
                ->from(TB_METHOD)
                ->where('parent_id', 0)
                ->where('status > ' . DEL_STATUS)
                ->get()
                ->result_array();
        }
        $data['info'] = $info;
        // 获取下一个排序号
        $row = $this->db->select('count(id) as total')
            ->from(TB_METHOD)
            ->where('parent_id', $info['parent_id'])
            ->where('status > ' . DEL_STATUS)
            ->get()
            ->row_array();
        $data['sort'] = $row['total'] + 1;
        return $data;
    }

    /**
     * 编辑控制器和方法
     *
     * @param $id 操作id
     * @param $data 提交数据
     * @return array 返回结果
     */
    public function edit($id, $data)
    {
        if (!isset($data['type']) || !in_array($data['type'], [1, 2])) {
            $this->rs['msg'] = '参数有误';
            return $this->rs;
        }
        $type = $data['type'];
        switch ($type) {
            case 1:
                $typeName = '控制器';
                $field = lwCheckValue($data, ['name', 'describe', 'is_default', 'sort', 'is_log']);
                if ($field === false) {
                    $this->rs['msg'] = '参数有误';
                    return $this->rs;
                }
                // 唯一行测试
                $rowExist = $this->tb_method->check_exist(['name' => strtolower($field['name']), 'parent_id' => 0], $id);
                if ($rowExist) {
                    $this->rs['msg'] = '控制器名称已经存在';
                    return $this->rs;
                }
                break;
            case 2:
                $typeName = '方法';
                $field = lwCheckValue($data, ['parent_id', 'name', 'describe', 'is_menu', 'is_default', 'sort', 'is_log']);
                if ($field === false) {
                    $this->rs['msg'] = '参数有误';
                    return $this->rs;
                }
                $rowExist = $this->tb_method->check_exist(['name' => strtolower($field['name']), 'parent_id' => $field['parent_id']], $id);
                if ($rowExist) {
                    $this->rs['msg'] = '同一控制器下方法名称已经存在';
                    return $this->rs;
                }
                break;
        }
        // 控制器和方法 全都记录成小写
        $field['name'] = strtolower($field['name']);
        $field['status'] = 1;
        $result = $this->tb_method->update($field, ['id' => $id]);
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['id'] = $id;
            $this->rs['msg'] = $typeName . '操作成功';
        } else {
            $this->rs['msg'] = $typeName . '操作成功';
        }
        return $this->rs;
    }


    /**
     * 删除控制器或方法——物理删除
     *
     * @param $id 主键ID
     * @param $type 1: 控制器 2: 方法
     * @return array 结果
     */
    public function del($id, $type)
    {
        $actionName = $type == 1 ? '删除控制器' : '删除方法';
        $result = $this->tb_method->delete(['id' => $id]);
        if ($result) {
            if($type == 1) {
                // 如果 type = 1 还要删除 该控制下所有方法以及对应的所有 tb_role_power 记录
                $this->tb_method->delete(['parent_id' => $id]);
                $methodList = $this->tb_method->get_all('*',['parent_id' => $id]);
                if($methodList) {
                    foreach($methodList as $method){
                        $this->tb_role_power->delete(['method_id' => $method['id']]);
                    }
                }
            } else {
                // 如果 type = 2 删除 tb_role_power 对应的记录
                $this->tb_role_power->delete(['method_id' => $id]);
            }
            $this->rs['success'] = true;
            $this->rs['msg'] = $actionName . '成功';
        } else {
            $this->rs['msg'] = $actionName . '失败';
        }
        return $this->rs;
    }


    public function getMethodMenuList($methodId)
    {
        $sql = "SELECT
                    mm.*, m.`name` AS menu_name
                FROM
                    ".TB_METHOD_MENU." mm
                LEFT JOIN ".TB_MENU." m ON mm.menu_id = m.id
                WHERE
                    method_id = {$methodId}
                AND mm.status = 1";
        return $this->db->query($sql)->result_array();
    }

    public function initBindMenu($methodId)
    {
        $field = [
            'method_id' => $methodId,
            'status' => DEL_STATUS
        ];
        $id = $this->tb_method_menu->insert($field);
        return $id;
    }

    /**
     * 获取编辑方法菜单的数据
     *
     * @param $id TB_METHOD_MENU 主键
     * @return mixed 返回数据
     */
    public function getBindMenuEditData($id)
    {
        $this->load->model('menu_model');
        $data['menuList'] = $this->menu_model->getMenuList();
        $data['info'] = $this->tb_method_menu->get_one(['id' => $id]);
        $methodId = $data['info']['method_id'];
        $row = $this->db->select('count(id) as total')
            ->from(TB_METHOD_MENU)
            ->where('method_id', $methodId)
            ->where('status', 1)
            ->get()
            ->row_array();
        $data['sort'] = $row['total'] + 1;
        return $data;
    }

    /**
     * 添加或编辑方法菜单操作
     *
     * @param $id TB_METHOD_MENU 主键
     * @param $post 提交数据
     * @param $actionName 操作名: 添加或编辑
     * @return array 返回结果
     */
    public function bingMenuEdit($id, $post, $actionName)
    {
        $field = lwCheckValue($post, ['menu_id', 'name', 'sort', 'param']);
        if ($field === false) {
            $this->rs['msg'] = '参数缺失';
            return $this->rs;
        }
        $field['status'] = 1;
        $result = $this->tb_method_menu->update($field, ['id' => $id]);
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = '方法菜单' . $actionName . '成功';
        } else {
            $this->rs['msg'] = '方法菜单' . $actionName . '失败';
        }
        return $this->rs;
    }


    /**
     * 删除方法菜单
     *
     * @param $id TB_METHOD_MENU 主键
     * @return array 操作结果
     */
    public function bindMenuDel($id)
    {
        $result = $this->tb_method_menu->delete(['id' => $id]);
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = '删除方法菜单成功';
        } else {
            $this->rs['msg'] = '删除方法菜单失败';
        }
        return $this->rs;
    }


    /**
     * 获取 控制器与方法的级联数据
     *
     * @return array 结果
     */
    public function getControllerMethodList()
    {
        $controllerMethodList = [];
        $controllerList = $this->tb_method->get_all('*', ['parent_id' => 0, 'status' => 1]);
        foreach($controllerList as $controller) {
            $controllerMethodList[$controller['id']] = $this->tb_method->get_all('*', ['parent_id' => $controller['id'], 'status' => 1]);
        }
        return [
            'controllerList' =>  $controllerList,
            'controllerMethodList' => $controllerMethodList
        ];
    }
}