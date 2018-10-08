<?php
/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 2018/6/7
 * Time: 下午3:32
 */


?>
<div class="row table-responsive no-padding">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>登录名</th>
                <th>姓名</th>
                <th>角色</th>
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
                        <td><?= $data['loginname'] ?></td>
                        <td><?= $data['username'] ?></td>
                        <td><?= $data['role_name'] ?></td>
                        <td>
                            <button data-id="<?=$data['id']?>" class="btn btn-primary btn-sm btn-edit"><i class="fa fa-edit"></i> 编辑</button>
                            <button data-id="<?=$data['id']?>" class="btn btn-danger btn-sm btn-del"><i class="fa fa-trash-o"></i> 删除</button>
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
                url: "<?=site_url('admin/edit')?>/" + id,
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
                text: "你确定要删除此管理员吗",
                confirm: function () {
                    Utils.ajax({
                        url: "<?=site_url('admin/del')?>/" + id,
                        success: function (data) {
                            Utils.noticeSys(data);
                            if(data.success){
                                getListByPage(1);
                            }
                        }
                    });
                }
            });
        });
    })
</script>

