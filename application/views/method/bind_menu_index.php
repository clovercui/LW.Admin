<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                    aria-hidden="true">×</span></button>
            <h4 class="modal-title">绑定菜单</h4>
        </div>
        <div class="modal-body">
            <button class="btn btn-primary btn-am" id="btn-add-bind-menu" data-method-id="<?= $methodId ?>">添加</button>
            <hr>
            <!--展示绑定菜单列表-->
            <div id="div-bind-menu-list"></div>
        </div>
        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
        </div>
    </div>

    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script>
    var methodId = <?=$methodId?>;
    $(function () {
        loadMethodMenuList();
        $("#btn-add-bind-menu").on("click", function () {
            Utils.ajax({
                url: "<?=site_url('method/bindMenuAdd')?>/" + methodId,
                success: function (data) {
                    if (data.success) {
                        if (data.success) {
                            $("#div-modal-sub").html(data.html);
                            $("#div-modal-sub").modal();
                        } else {
                            Utils.noticeWarning(data.msg);
                        }
                    }
                }
            });
        })
    });


    // 获取菜单列表
    function loadMethodMenuList() {
        Utils.ajax({
            url: "<?=site_url('method/bindMenuList')?>/" + methodId,
            success: function (data) {
                if (data.success) {
                    $("#div-bind-menu-list").html(data.html);
                }
            }
        });
    }
</script>