<?php
/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 2018/6/7
 * Time: 上午11:05
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">列表</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <button class="btn btn-primary" id="btn-add">添加角色</button>
                <hr>
                <div id="dataList" class="row col-md-12">
                    <!--动态ajax加载博文-->
                </div>
            </div>
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<script>
    var btnAdd = $("#btn-add");
    $(function () {
        initBind();
        getList();
    });

    // DOM 元素绑定事件
    function initBind() {
        btnAdd.on('click', function () {
            Utils.ajax({
                url: "<?=site_url('role/add')?>",
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
    }

    // 获取列表数据
    function getList() {
        Utils.ajax({
            url: "<?=site_url('role/lists')?>",
            success: function (data) {
                if (data.success) {
                    $("#dataList").html(data.html);
                } else {
                    Utils.noticeWarning(data.msg);
                }
            }
        });
    }

</script>