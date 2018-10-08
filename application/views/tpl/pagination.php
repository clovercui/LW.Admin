<!--
这是一个分页模板：
主要有三种方式：
1.直接调用模板视图:
    $data['pagination'] = array(
            'page' => 8,
            'action' => 'ajaxPage',
            'totalRows' => 20,
            'totalPage' => 20,
            'type' =>1 默认为 1，通过 onclick 方式跳转；2：通过 href 的分页 a标签
        );
    $this->load->view('templates/pagination',$data);
2.在视图页通过 include "./application/views/templates/pagination.php"; 调用
3.获取视图输出信息，echo 到 视图页面 $pageHtml = $this->load->view('templates/pagination',$data,true); echo $pageHtml
-->
<?php if(isset($pagination)): ?>
    <?php
    $page = $pagination['page'];
    $totalRows = $pagination['totalRows'];
    $pageSize = $pagination['pageSize'];
    $totalPage = ceil($totalRows / $pageSize);
    $action =  $pagination['action'];
    $paginationType = $pagination['type'];
    $page = ($page > $totalPage)? $totalPage : $page;
    $pageConfig = array(
        'page' => $page,
        'action' => $action,
        'totalPage' => $totalPage,
        'type'=> $paginationType
    );
    if($totalRows == 0){
        return;
    }
    ?>
    <div class="text-center">
        <!-- 电脑页面展示效果-->
        <ul class="pagination pagination-centered" style="text-align: center">
            <li><a style='cursor:pointer'>共<?=$totalPage?>页，<?=$totalRows?>条记录</a></li>
            <?php
            $allNum = 7;//分页有多少个数码页（一定要是奇数）
            $midNum = ($allNum+1)/2;
            $difNum = $allNum - $midNum;
            ?>
            <?php setAttr(1,$pageConfig); ?>
            <?php setAttr(2,$pageConfig); ?>
            <!--这里方式当前页码与总页数的显示关系-->
            <?php if($totalPage<=$allNum): ?>
                <?php for($i=1;$i<=$totalPage;$i++):?>
                    <?php setAttr(3,$pageConfig,$i); ?>
                <?php endfor;?>
            <?php endif;?>
            <?php if($totalPage > $allNum): ?>
                <?php if($page <= $midNum):?>
                    <?php for($i=1;$i<=$allNum;$i++):?>
                        <?php setAttr(3,$pageConfig,$i); ?>
                    <?php endfor;?>
                <?php else:?>
                    <!--最末页判断-->
                    <?php $pg=(($page + $difNum) >= $totalPage)?$totalPage:($page + $difNum); ?>
                    <?php for($i=$page - $difNum;$i <= $pg;$i++):?>
                        <?php setAttr(3,$pageConfig,$i); ?>
                    <?php endfor;?>
                <?php endif;?>
            <?php endif;?>
            <!--关系结束-->
            <?php setAttr(4,$pageConfig); ?>
            <?php setAttr(5,$pageConfig); ?>
        </ul>
    </div>
<?php endif; ?>
<?php
function setAttr($type,$pageConfig,$i = 0)
{
    $page = $pageConfig['page'];
    $action = $pageConfig['action'];
    $totalPage = $pageConfig['totalPage'];
    $paginationType = $pageConfig['type'];
    $htmlStr = "";
    if($paginationType == 1){
        switch ($type){
            case 1: //首页
                $htmlStr = $page <= 1? "<li class='disabled'><a style='cursor:pointer'>首页</a></li>" : "<li><a style='cursor:pointer' onclick='{$action}(1)'>首页</a></li>";
                break;
            case 2: //上一页
                $htmlStr = $page <= 1? "<li class='disabled'><a style='cursor:pointer'>上一页</a></li>" : "<li><a style='cursor:pointer' onclick='{$action}( ({$page} - 1) )'>上一页</a></li>";
                break;
            case 3: //数字页
                $htmlStr = $i == $page? "<li class='active'><a style='cursor:pointer'>{$i}</a></li>" : "<li><a style='cursor:pointer' onclick='{$action}({$i})'>{$i}</a></li>";
                break;
            case 4: //下一页
                $htmlStr = $page >= $totalPage? "<li class='disabled'><a style='cursor:pointer'>下一页</a></li>" : "<li><a style='cursor:pointer' onclick='{$action}(({$page} + 1))'>下一页</a></li>";
                break;
            case 5: //尾页
                $htmlStr = $page >= $totalPage? "<li class='disabled'><a style='cursor:pointer'>尾页</a></li>" : "<li><a style='cursor:pointer' onclick='{$action}({$totalPage})'>尾页</a></li>";
                break;
        }
    }else{
        switch ($type){
            case 1: //首页
                $currPage = 1;
                $url = pageUrl($action, $currPage);
                $htmlStr = $page <= 1? "<li class='disabled'><a style='cursor:pointer'>首页</a></li>" : "<li><a style='cursor:pointer' href='{$url}'>首页</a></li>";
                break;
            case 2: //上一页
                $currPage = $page-1;
                $url = pageUrl($action, $currPage);
                $htmlStr = $page <= 1? "<li class='disabled'><a style='cursor:pointer'>上一页</a></li>" : "<li><a style='cursor:pointer' href='{$url}' >上一页</a></li>";
                break;
            case 3: //数字页
                $currPage = $i;
                $url = pageUrl($action, $currPage);
                $htmlStr = $i == $page? "<li class='active'><a style='cursor:pointer'>{$i}</a></li>" : "<li><a style='cursor:pointer' href='{$url}' >{$i}</a></li>";
                break;
            case 4: //下一页
                $currPage = $page+1;
                $url = pageUrl($action, $currPage);
                $htmlStr = $page >= $totalPage? "<li class='disabled'><a style='cursor:pointer'>下一页</a></li>" : "<li><a style='cursor:pointer' href='{$url}'>下一页</a></li>";
                break;
            case 5: //尾页
                $currPage = $totalPage;
                $url = pageUrl($action, $currPage);
                $htmlStr = $page >= $totalPage? "<li class='disabled'><a style='cursor:pointer'>尾页</a></li>" : "<li><a style='cursor:pointer' href='{$url}'>尾页</a></li>";
                break;
        }
    }
    echo $htmlStr;
}

function pageUrl($action, $page) {
    if(strstr($action, '?')) {
        return site_url($action.'&page='.$page);
    }
    return site_url($action.'&page='.$page);
//    if(!$sort) {
//        return site_url($action.'?page='.$page);
//    }
//    return site_url($action.'?sort='.$sort.'&page='.$page);
}

?>