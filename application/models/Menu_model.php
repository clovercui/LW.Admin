<?php

/**
 * 菜单 MODEL
 */
class Menu_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('lw_db', ['tb_name' => TB_MENU], 'tb_menu');
    }

    // 菜单数组示例
    // public function get_menu()
    // {
    //     $menuList = [];
    //     $menuList[] = [
    //         'id' => 1,
    //         'text' => '菜单',
    //         'icon' => '',
    //         'isHeader' => true,
    //     ];
    //     $menuList[] = [
    //         'id' => 10,
    //         'text' => "系统管理",
    //         'icon' => "fa fa-list",
    //         'children' => [
    //             [
    //                 'id' => 11,
    //                 'text' => "管理员管理",
    //                 'icon' => "fa fa-circle-o",
    //                 'url' => site_url('admin/index'),
    //                 'targetType' => "iframe-tab",
    //                 'urlType' => 'absolute'
    //             ]
    //         ]
    //     ];
    //     return $menuList;
    // }

    /** 获取网站菜单
     * @return 返回菜单数组
     */
    public function getSiteMenu()
    {
        $roleId = $this->session->roleId;
        $topRole = $this->session->topRole;
        $menuList = [];
        $menuList[] = [
            'id' => 0,
            'text' => '菜单',
            'icon' => '',
            'isHeader' => true,
        ];
        $rowsMenu = $this->tb_menu->get_all('*', ['status' => 1], 'sort');
        $defaultMenus = [];
        foreach ($rowsMenu as $key => $menu) {
            if ($topRole) {
                // 最高权限角色
                $sql = "SELECT
                            a.*,
                            b.`name` AS controller_name,
                            c.id AS menu_id,
                            c.menu_id as parent_menu_id,
                            c.`name` AS menu_name
                        FROM
                            ".TB_METHOD." a
                        LEFT JOIN ".TB_METHOD." b on a.parent_id = b.id
                        LEFT JOIN ".TB_METHOD_MENU." c on a.id = c.method_id
                        WHERE a.is_menu = 1
                        AND c.menu_id = {$menu['id']}";
            } else {
                // 其他权限
                $sql = "SELECT
                            a.*, b.`name`,
                            b.controller_name,
                            b.params,
                            c.id AS menu_id,
                            c.menu_id as parent_menu_id,
                            c.`name` AS menu_name
                        FROM
                            ".TB_ROLE_POWER." a
                        LEFT JOIN (
                            SELECT
                                e.*, f.`name` AS controller_name,
                                f.`describe` AS controller_describe
                            FROM
                                ".TB_METHOD." e
                            LEFT JOIN ".TB_METHOD." f ON e.parent_id = f.id
                        ) b ON a.method_id = b.id
                        LEFT JOIN ".TB_METHOD_MENU." c ON b.id = c.method_id
                        WHERE
                            b.is_menu = 1
                        AND a.role_id = {$roleId} and c.menu_id = {$menu['id']}";
                $sqlDefault = "SELECT
                            a.*,
                            b.`name` AS controller_name,
                            c.id AS menu_id,
                            c.menu_id as parent_menu_id,
                            c.`name` AS menu_name
                        FROM
                            ".TB_METHOD." a
                        LEFT JOIN ".TB_METHOD." b on a.parent_id = b.id
                        LEFT JOIN ".TB_METHOD_MENU." c on a.id = c.method_id
                        WHERE a.is_menu = 1
                        AND c.menu_id = {$menu['id']} AND a.is_default = 1";
                $defaultMenus = $this->db->query($sqlDefault)->result_array();
            }
            $rowsChildMenu = $this->db->query($sql)->result_array();
            $rowsChildMenu = array_merge($rowsChildMenu, $defaultMenus);
            if($rowsChildMenu) {
                $menuItem = [
                    'id' => $menu['id'],
                    'text' => $menu['name'],
                    'icon' => "fa " . $menu['icon'],
                    'children' => []
                ];
                foreach($rowsChildMenu as $childMenu){
                    $menuItem['children'][] = [
                        'id' => $childMenu['menu_id'],
                        'text' => $childMenu['menu_name'],
                        'icon' => "fa fa-circle-o",
                        'url' => site_url($childMenu['controller_name'].'/'.$childMenu['name']),
                        'targetType' => "iframe-tab",
                        'urlType' => 'absolute'
                    ];
                }
                $menuList[] = $menuItem;
            }
        }
        return $menuList;
    }

    public function getListData($page)
    {
        $this->load->library('lw_pagination');
        $param = $this->input->post('param');
        $sql = "SELECT
                    a.*
                FROM
                    " . TB_MENU . " a
                WHERE a.status = 1";
        $hasWhere = true;
        $group = null;
        $order = "a.sort ASC";
        $paramFilter = ['a.name'];
        $data = $this->lw_pagination->lists($sql, $param, $page, $hasWhere, $group, $order, $paramFilter, $pageSize = 10);
        return $data;
    }


    public function getEditData($id)
    {
        if ($id == 0) {
            $data['info'] = [
                'id' => 0,
                'name' => '',
                'icon' => 'fa-list'
            ];
            $row = $this->db->select('count(id) as total')
                ->from(TB_MENU)
                ->where('status', 1)
                ->get()
                ->row_array();
            $data['info']['sort'] = $row['total'] + 1;
        } else {
            $data['info'] = $this->tb_menu->get_one(['id' => $id]);
        }
        return $data;
    }

    public function edit($id, $post)
    {
        $field = lwCheckValue($post, ['name', 'icon', 'sort']);
        if ($field === false) {
            $this->rs['msg'] = '参数缺失';
            return $this->rs;
        }
        $rowExist = $this->tb_menu->check_exist(['name' => $field['name']], $id);
        if ($rowExist) {
            $this->rs['msg'] = '菜单名称已经存在, 请重新输入';
            return $this->rs;
        }
        $actionName = $id == 0 ? '添加菜单' : '编辑菜单';
        if ($id == 0) {
            $field['status'] = 1;
            $field['record_time'] = time();
            $result = $this->tb_menu->insert($field);
        } else {
            $result = $this->tb_menu->update($field, ['id' => $id]);
        }
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = '菜单' . $actionName . '成功';
        } else {
            $this->rs['msg'] = '菜单' . $actionName . '失败';
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
        $result = $this->tb_menu->delete(['id' => $id]);
        if ($result) {
            $this->rs['success'] = true;
            $this->rs['msg'] = '删除菜单成功';
        } else {
            $this->rs['msg'] = '删除菜单失败';
        }
        return $this->rs;
    }

}