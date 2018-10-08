<div class="row table-responsive no-padding">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>角色名</th>
                <th>层级</th>
                <th width="250px">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>0</td>
                <td><?=$this->session->roleName?></td>
                <td>当前角色</td>
                <td><button type="button" class="btn btn-sm">无权操作</button></td>
            </tr>
            <?php if (!$dataList) {
                echo "<td>当前没有数据</td>";
            } else {
                $i = 1;
                foreach ($dataList as $data): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data['name'] ?></td>
                        <td><?= $data['depth'] == 0 ? '当前角色' : '下属角色（第' . $data['depth'] . '层)' ?></td>
                        <td>
                            <?php if ($data['depth'] == 0): ?>
                                <button class="btn btn-sm">无权操作</button>
                            <?php else: ?>
                                <button data-id="<?= $data['id'] ?>" class="btn btn-primary btn-sm btn-edit">
                                    <i class="fa fa-edit"></i> 编辑
                                </button>
                                <button data-id="<?= $data['id'] ?>" class="btn btn-success btn-sm btn-set-power">
                                    <i class="fa fa-cog"></i> 配权
                                </button>
                                <button data-id="<?= $data['id'] ?>" class="btn btn-danger btn-sm btn-del">
                                    <i class="fa fa-trash-o"></i> 删除
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $i++; endforeach;
            } ?>
            </tbody>
        </table>
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="text-center">
        <?php if (isset($pageHtml)) {
            echo $pageHtml;
        } ?>
    </div>
</div><!-- /.row -->

<script>
    $(function () {
        // 编辑操作绑定
        $(".btn-edit").on('click', function () {
            var id = $(this).data('id');
            Utils.ajax({
                url: "<?=site_url('role/edit')?>/" + id,
                success: function (data) {
                    if (data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            })
        });

        // 删除操作绑定
        $(".btn-del").on('click', function () {
            var id = $(this).data('id');
            Utils.confirm({
                title: "温馨提示",
                text: "你确定要删除此角色吗",
                confirm: function () {
                    Utils.ajax({
                        url: "<?=site_url('role/del')?>/" + id,
                        success: function (data) {
                            Utils.alertSys(data);
                            if (data.success) {
                                getList();
                            }
                        }
                    });
                }
            });
        });
        $(".btn-set-power").on('click', function () {
            var roleId = $(this).data('id');
            Utils.ajax({
                url: "<?=site_url('role/setPower')?>/" + roleId,
                success: function (data) {
                    if(data.success){
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        });
    })
</script>

