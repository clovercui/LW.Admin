<?php

/**
 * Class Ln_db 通用类，使用于常用的增删改查操作
 */
class Lw_db
{
    protected $CI;
    private $TB_NAME;

    public function __construct($params)
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->TB_NAME = $params['tb_name'];
    }

    /** 获取多条数据
     * @param null $field :字段 默认 "*"
     * @param null $keys : where 关键字
     * @param null $order ：排序
     * @param  $orderType ：排序类型
     * @param null $limit ：返回数据起点，或返回条数
     * @param null $offet ：返回条数
     * @return mixed 多维数组数据
     */
    public function get_all($field = null, $keys = null, $order = null, $orderType = 'asc', $limit = null, $offet = null)
    {
        ($field) ? $this->CI->db->select($field) : $this->CI->db->select("*");
        $this->CI->db->from($this->TB_NAME);
        ($keys) ? $this->CI->db->where($keys) : "";
        ($order) ? $this->CI->db->order_by($order, $orderType) : "";
        $this->CI->db->limit($limit, $offet);
        return $this->CI->db->get()->result_array();
    }

    /** 获取单条数据
     * @param $keys : where 关键字
     * @return mixed：以为数组数据：字段为键 值为值
     */
    public function get_one($keys)
    {
        return $this->CI->db->get_where($this->TB_NAME, $keys)->row_array();
    }

    /** 插入单条数据
     * @param $info ：插入数据（数组）键-> 字段名，值-> 插入值
     * @return mixed：返回插入id
     */
    public function insert($info)
    {
        $this->CI->db->insert($this->TB_NAME, $info);
        return $this->CI->db->insert_id();
    }

    /** 更新数据
     * @param $info ：更新数据（数组）键-> 字段名，值-> 插入值
     * @param $keys ：where 关键字
     * @return mixed：返回更新成功与否
     */
    public function update($info, $keys)
    {
        return $this->CI->db->update($this->TB_NAME, $info, $keys);
    }

    /** 删除数据
     * @param $keys : where 关键字
     * @return mixed：返回删除成功与否
     */
    public function delete($keys)
    {
        return $this->CI->db->delete($this->TB_NAME, $keys);
    }

    /**
     * 判断 记录 是否存在
     * @param $where
     * @param null $id
     * @return bool|mixed
     */
    public function check_exist($where, $id = null)
    {
        if(!$where) {
            exit('参数错误');
        }
        $row = $this->get_one($where);
        if($id) {
            // 如果不存在，就通过
            if(!$row){
                return false;
            }
            if($row && $row['id'] != $id) {
                return $row;
            } else {
                return false;
            }
        }
        // 添加情况
        return $row ? $row : false;
    }
}