<?php

/**
 * 统一 json 返回结果
 * @param $rs = ['success' => false, 'msg' => ''];
 */
function lwReturn($rs)
{
    if(!is_array($rs)) {
        die('返回函数：参数缺失');
    }
    echo json_encode($rs);exit;
}

/**
 * 判断当前数据,所有 key 是否存在
 * @param $data 数据源
 * @param $keyList 校验列表
 * @return array 过滤后的新数据
 */
function lwCheckValue($data, $keyList) {
    $newData = [];
    foreach($keyList as $key) {
        if(!isset($data[$key])) return false;
        $newData[$key] = $data[$key];
    }
    return $newData;
}

function checkIdReturn($id){
    $arr = [
        'isLogin' => false,
        'msg'=> '参数错误',
    ];
    $id = intval($id);
    if($id <= 0){
        lwReturn(false, $arr);
    } else {
        return $id;
    }
}


?>
