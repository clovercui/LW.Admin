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
            <div class="box-header with-border">
                <h3 class="box-title">筛选与添加</h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="form-body">
                        <div class="col-md-6 col-md-offset-3">
                            <label>菜单名称</label>
                            <input type="text" id="search-name" class="form-control" placeholder="请输入菜单名">
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.row -->
            </div><!-- ./box-body -->
            <div class="box-footer">
                <div class="row col-md-12">
                    <div class="col-md-2 col-md-offset-4">
                        <button id="btn-add" class="btn btn-primary btn-block" type="button"><i class="fa fa-plus"></i>
                            添加菜单
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-search" class="btn btn-success btn-block" type="button"><i
                                class="fa fa-search"></i> 搜索
                        </button>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.box-footer -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">列表</h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div id="dataList" class="row col-md-12">
                    <!--动态ajax加载博文-->
                </div>
            </div>
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<script>
    var btnSearch = $("#btn-search");
    var btnAdd = $("#btn-add");
    $(function () {
        initBind();
        getListByPage(1);
    });

    // DOM 元素绑定事件
    function initBind() {
        btnSearch.on('click', function () {
            getListByPage(1);
        })
        btnAdd.on('click', function () {
            Utils.ajax({
                url: "<?=site_url('menu/add')?>",
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
    function getListByPage(page) {
        var param = getParam();
        Utils.ajax({
            url: "<?=site_url('menu/lists')?>/" + page,
            data: {param: JSON.stringify(param)},
            success: function (data) {
                if (data.success) {
                    $("#dataList").html(data.html);
                } else {
                    Utils.noticeWarning(data.msg);
                }
            }
        });
    }

    // 获取列表参数
    function getParam() {
        var param = {};
        var paramLike = {};
        var name = $("#search-name").val().trim();
        if (name !== "") {
            paramLike['a.name'] = name;
        }
        param['like'] = paramLike;
        return param;
    }

</script>