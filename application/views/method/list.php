<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>方法名</th>
        <th>所属控制器</th>
        <th>参数</th>
        <th>菜单(Y/N)</th>
        <th>默认(Y/N)</th>
        <th>日志(Y/N)</th>
        <th>排序</th>
        <th width="250px">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!$dataList) {
        echo "<td>当前没有数据</td>";
    } else {
        $i = 1;
        foreach ($dataList as $data): ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= "{$data['name']}({$data['describe']})" ?></td>
                <td><?= "{$controllerInfo['name']}({$controllerInfo['describe']})" ?></td>
                <td><?= $data['params'] ? $data['params'] : '无' ?></td>
                <td><?= $data['is_menu'] == 1 ? "是" : "否" ?></td>
                <td><?= $data['is_default'] == 1 ? "是" : "否" ?></td>
                <td><?= $data['is_log'] == 1 ? "是" : "否" ?></td>
                <td><?= $data['sort'] ?></td>
                <td>
                    <?php if($data['is_menu']==1): ?>
                        <button type="button" class="btn btn-bind-menu btn-sm btn-primary" data-id="<?=$data['id']?>"><i class="fa fa-gear"></i> 配置菜单</button>
                    <?php endif;?>
                    <button type="button" class="btn btn-edit-method btn-sm btn-success" data-id="<?=$data['id']?>"><i class="fa fa-edit"></i> 编辑</button>
                    <button type="button" class="btn btn-del-method btn-sm btn-warning" data-id="<?=$data['id']?>"><i class="fa fa-trash-o"></i> 删除</button>
            </tr>
            <?php $i++; endforeach;
    } ?>
    </tbody>
</table>

<script>
    $(function () {
        // 绑定方法编辑操作
        $(".btn-edit-method").on('click', function () {
            var id = $(this).data('id');
            Utils.ajax({
                url: "<?=site_url('method/edit')?>/" + id + "/edit",
                success: function (data) {
                    if (data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        });
        // 绑定方法删除操作
        $(".btn-del-method").on('click', function () {
            var id = $(this).data('id');
            Utils.confirm({
                title: "温馨提示",
                text: "你确定要删除此方法吗",
                confirm: function () {
                    Utils.ajax({
                        url: "<?=site_url('method/del')?>/" + id,
                        data: {type: 2},
                        success: function (data) {
                            Utils.alertSys(data);
                            if(data.success){
                                loadMethodList();
                            }
                        }
                    });
                }
            });
        });
        // 绑定捆绑菜单操作
        $(".btn-bind-menu").on('click', function () {
            var id = $(this).data('id');
            Utils.ajax({
                url: "<?=site_url('method/bindMenuIndex')?>/" + id,
                success: function (data) {
                    if (data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            })
        })
    });
</script>


