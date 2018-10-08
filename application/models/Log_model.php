<?php

/**
 * 日志 MODEL
 */
class Log_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('lw_db', ['tb_name' => TB_SYS_LOG], 'tb_sys_log');
    }

    public function getIndexData()
    {
        $this->load->model('method_model');
        $data = $this->method_model->getControllerMethodList();
        return $data;
    }

    public function getList($type, $post, $page = null)
    {
        $this->load->library('lw_pagination');
        $post = lwCheckValue($post, ['param', 'startDate', 'endDate']);
        if ($post === false) {
            $this->rs['msg'] = '参数缺失';
            return $this->rs;
        }
        $param = $post['param'];
        $startDate = $post['startDate'];
        $startTime = strtotime($startDate.' 00:00:00');
        $endTime = $post['endDate'] ? strtotime($post['endDate'].' 23:59:59') : strtotime($startDate.' 23:59:59');
        $where = "record_time BETWEEN {$startTime} AND {$endTime}";
        $sql = "SELECT
                    a.*
                FROM
                    " . TB_SYS_LOG . " a
                WHERE {$where}";
        $hasWhere = true;
        $group = null;
        $order = "a.id desc";
        $paramFilter = ['a.controller_id', 'a.method_id'];
        switch ($type) {
            case 'list':
                $data = $this->lw_pagination->lists($sql, $param, $page, $hasWhere, $group, $order, $paramFilter, $pageSize = 10);
                $this->rs['success'] = true;
                $this->rs['data'] = $data;
                break;
            case 'excel':
                $this->load->helper('excel');
                // 设置变体
                $title = ['操作人', 'IP', '控制器', '方法', 'GET参数', 'POST参数', '记录日期'];
                $width = [];
                for ($i = 0; $i < sizeof($title); $i++) {
                    $width[$i] = 30;
                }
                // 获取数据
                $sql = $this->lw_pagination->get_sql_by_param($sql, $param, $hasWhere, $group, $order, $paramFilter);
                $list = [];
                $query = $this->db->query($sql);
                while ($row = $query->result_id->fetch_assoc()) {
                    $info = [];
                    array_push($info, $row['admin_name']);
                    array_push($info, $row['ip']);
                    array_push($info, $row['controller_describe']);
                    array_push($info, $row['method_describe']);
                    array_push($info, $row['get_params']);
                    array_push($info, $row['post_params']);
                    array_push($info, date('Y-m-d H:i:s', $row['record_time']));
                    array_push($list, $info);
                }
                //判断路径 并创建
                $date = date('Y-d', time());
                $uniStr = $this->lw_string->getUniName();
                $path = "outputExcel/log/" . $date;
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $fn = $path . "/" . $uniStr . ".xls";
                getExcel($title, $width, $list, "$fn");
                $this->rs['success'] = true;
                $this->rs['msg'] = 'excel导出';
                $this->rs['excelPath'] = base_url($fn);;
                break;
            default:
                $this->rs['msg'] = '';
                break;
        }
        return $this->rs;
    }

    public function getInfoData($id)
    {
        $data['info'] = $this->getLogInfo($id);
        return $data;
    }

    public function getLogInfo($id) {
        $info = $this->db->select('*')->from(TB_SYS_LOG)->where(['id' => $id])->get()->row_array();
        return $info;
    }
}