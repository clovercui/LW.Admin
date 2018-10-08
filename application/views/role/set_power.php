<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                    aria-hidden="true">×</span></button>
            <h4 class="modal-title">配置权限</h4>
        </div>
        <div class="modal-body">
            <div id="div-power-tree"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button id="btn-set-power" name="button" type="submit" data-role-id="<?= $roleId ?>"
                    class="btn btn-primary">保存
            </button>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script>
    var roleId = <?=$roleId?>;
    var idArr; // 权限树中被选中的叶子节点
    $(function () {
        loadPowerData();
    })
    function loadPowerData() {
        Utils.ajax({
            url: "<?=site_url('role/getRolePowerData')?>/" + roleId,
            success: function (data) {
                if (data.success) {
                    creatPowerTree(data.powerData);
                } else {
                    Utils.noticeWarning(data.msg);
                }
            },
            error: function () {

            },
            complete: function () {
            }
        });
    }

    function creatPowerTree(powerData) {
        var tree = $("#div-power-tree");
        tree.jstree({
            'plugins': ["wholerow", "checkbox", "types"],
            'core': {
                "themes": {
                    "responsive": true
                },
                'data': powerData
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder icon-state-warning icon-lg"
                },
                "file": {
                    "icon": "fa fa-file icon-state-warning icon-lg"
                }
            }
        });
        tree.on('changed.jstree', function (e, data) {
            idArr = [];
            for (var i = 0; i < data.selected.length; i++) {
                var node = data.instance.get_node(data.selected[i]);
                if (data.instance.is_leaf(node)) {
                    idArr.push(node.id);
                }
            }
        });
    }

    $("#btn-set-power").on('click', function () {
        var roleId = $(this).data('role-id');
        Utils.confirm({
            title: "温馨提示",
            text: "你确定要修改管理员权限吗?",
            confirm: function () {
                Utils.ajax({
                    url: "<?=site_url('role/setPower')?>/" + roleId,
                    data: {rolePowerData: idArr, doSubmit: true},
                    success: function (data) {
                        if(data.success) {
                            $("#div-modal").modal('hide');
                        }
                        Utils.alertSys(data);
                    }
                });
            }
        });
    });
</script>