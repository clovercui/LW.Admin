<?php

class Lw_pagination
{

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('lw_string');
    }

    public function lists($sql, $param, $page, $hasWhere = false, $group = null, $order = null, $paramFilter = [], $pageSize = PAGESIZE, $action = "getListByPage", $dataName = 'dataList', $type = 1)
    {
        $pagination = $this->normalPagination($sql, $param, $page, $hasWhere, $group, $order, $paramFilter, $pageSize, $action, $type);
        //MARK: 当前页数/总行数，总页数。
        $data[$dataName] = $pagination['pageData'];
        $data['pagination'] = $pagination['pageConfig'];
        $data['pageHtml'] = $this->CI->load->view('tpl/pagination', $data, true);
        return $data;
    }

    /*
     * $sql: 原始sql语句
     * $param: sql参数
     * $page: 当前页
     * $order: 排序字段
     * $action: 页码点击事件
     * $hasWhere: 原始sql是否存在where，默认不存在
     * $type :1 代表默认方法 通过此方法 调用 a标签 onclick 时间 跳转页面，2 通过 href 跳转 actions即使 url
     * */
    public function normalPagination($sql, $param, $page, $hasWhere = false, $group = null, $order = null, $paramFilter = [], $pageSize = PAGESIZE, $action = "getListByPage", $type = 1)
    {
        $sql = $this->get_sql_by_param($sql, $param, $hasWhere, $group, $order, $paramFilter);
        /*echo $sql;exit;*/
        /*我们要的数据*/
        $totalRows = $this->CI->db->query($sql)->num_rows();
        $totalPage = ceil($totalRows / $pageSize);
        $pageData = $this->get_rows_by_page($totalRows, $sql, $page, $pageSize);
        $pageConfig = [
            'page' => $page,
            'action' => $action,
            'totalRows' => $totalRows,
            'pageSize' => $pageSize,
            'type' => $type,
        ];
        return [
            'pageData' => $pageData,
            'pageConfig' => $pageConfig
        ];
    }

    /**
     * @param $sql 原始SQL
     * @param $param 参数
     * @param bool|false $hasWhere 排序
     * @param null $group 分组
     * @param null $order 排序
     * @param array $paramFilter 过滤数组
     * @return string 完整 SQL
     */
    public function get_sql_by_param($sql, $param, $hasWhere = false, $group = null, $order = null, $paramFilter = [])
    {
        if ($param) {
            $arrParams = json_decode($param, true);
            $sqlWhere = $this->array_to_sql($arrParams, $paramFilter);
            if ($sqlWhere) {
                if ($hasWhere) {
                    $sql .= " and {$sqlWhere}";
                } else {
                    $sql .= " where {$sqlWhere}";
                }
            }
        }

        // 如果有 group 要先执行
        if ($group) {
            if (stristr($group, 'group by')) {
                $sql .= " " . $group;
            } else {
                $sql .= " group by " . $group;
            }
        }

        if ($order) {
            if (stristr($order, 'orer by')) {
                $sql .= " " . $order;
            } else {
                $sql .= " order by " . $order;
            }
        }
        return $sql;
    }

    /**
     * @param $arr 参数数组
     * @param array $paramFilter
     * @return string
     */
    function array_to_sql($arr, $paramFilter = [])
    {
        $sep = $sqlWhere = "";
        foreach ($arr as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case 'equal':
                        foreach ($val as $k => $v) {
                            // 如果不存在 过滤数组，或者当前字段在过滤数组当中，可以拼接
                            if (!$paramFilter || !in_array($k, $paramFilter)) continue;
                            $sqlWhere .= "{$sep} {$k} = '{$v}' ";
                            $sep = ' AND';
                        }
                        break;
                    case 'like':
                        foreach ($val as $k => $v) {
                            if (!$paramFilter || !in_array($k, $paramFilter)) continue;
                            $sqlWhere .= "{$sep} {$k} like '%{$v}%' ";
                            $sep = ' AND';
                        }
                        break;
                    case 'sql':
                        if (!$paramFilter || !in_array('sql', $paramFilter)) continue;
                        if (stripos(substr($val, 0, 5), 'and') !== false) {
                            $sqlWhere .= " {$val} ";
                        } else {
                            $sqlWhere .= " {$sep} {$val} ";
                        }
                        break;
                    default:
                        if (!$paramFilter || !in_array($key, $paramFilter)) continue;
                        // 默认模式：like
                        if ($val == 'null') {
                            $sqlWhere .= "{$sep} {$key} is null ";
                        } else {
                            $sqlWhere .= "{$sep} {$key} like '%{$val}%' ";
                        }
                        $sep = ' AND';
                        break;
                }
            }
        }
        return $sqlWhere;
    }

    /** 通过页数获取对应 pageSize 的数据
     * @param $totalRows ：总数据
     * @param $sql ：完整sql
     * @param $page ：当前页
     * @param $pageSize ：每页数据数
     * @return bool：返回数据
     */
    public function get_rows_by_page($totalRows, $sql, $page, $pageSize)
    {
        if ($totalRows) {
            $totalPage = ceil($totalRows / $pageSize);
            if ($page < 1 || $page == null || !is_numeric($page)) {
                $page = 1;
            }
            $page = $page >= $totalPage ? $totalPage : $page;
            $offSet = ($page - 1) * $pageSize;
            $sql .= " limit {$offSet},{$pageSize}";
            $rows = $this->CI->db->query($sql)->result_array();
            return $rows;
        } else {
            return false;
        }
    }
}
