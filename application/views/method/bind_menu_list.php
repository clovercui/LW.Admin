<div class="row table-responsive no-padding">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>所属菜单</th>
                <th>菜单名称</th>
                <th>排序</th>
                <th>参数</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!$dataList){ echo "<td>当前没有数据</td>"; }else{$i=1; foreach($dataList as $data):?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$data['menu_name']?></td>
                    <td><?=$data['name']?></td>
                    <td><?=$data['sort']?></td>
                    <td><?=$data['param']?></td>
                    <td>
                        <button type="button" class="btn btn-edit-bind-menu btn-sm btn-success" data-id="<?=$data['id']?>"><i class="fa fa-edit"></i> 编辑</button>
                        <button type="button" class="btn btn-del-bind-menu btn-sm btn-warning" data-id="<?=$data['id']?>"><i class="fa fa-trash-o"></i> 删除</button>
                    </td>
                </tr>
                <?php $i++; endforeach;}?>
            </tbody>
        </table>
    </div>
</div><!-- /.row -->

<script>
    $(function () {
        initBind();
    })

    function initBind() {
        $(".btn-edit-bind-menu").on("click", function () {
            var id = $(this).data("id");
            Utils.ajax({
                url: "<?=site_url('method/bindMenuEdit')?>/" + id,
                data: {methodId: methodId},
                success: function (data) {
                    if(data.success){
                        $("#div-modal-sub").html(data.html);
                        $("#div-modal-sub").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        });
        $(".btn-del-bind-menu").on("click", function () {
            var id = $(this).data("id");
            Utils.confirm({
                title: "温馨提示",
                text: "你确定要删除此菜单?",
                confirm: function () {
                    Utils.ajax({
                        url: "<?=site_url('method/bindMenuDel')?>/" + id,
                        success: function (data) {
                            Utils.alertSys(data);
                            if(data.success){
                                loadMethodMenuList();
                            }
                        }
                    });
                }
            });
        });
    }
</script>