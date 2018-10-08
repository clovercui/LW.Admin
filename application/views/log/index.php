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
                <div class="row col-md-12">
                    <div class="form-body">
                        <div class="col-md-3">
                            <label>控制器</label>
                            <select id="search-controller" class="form-control" onchange="controllerSelectChanged()">
                                <option value="-1">不限</option>
                                <?php foreach($controllerList as $controller):?>
                                    <option value="<?=$controller['id']?>"><?=$controller['describe']?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>方法</label>
                            <select id="search-method" class="form-control">
                                <option value="-1">不限</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>开始时间</label>
                            <input id="search-start-date" type="text" class="form-control form-date date-day" readonly value="<?=date('Y-m-d')?>">
                        </div>
                        <div class="col-md-3">
                            <label>结束时间</label>
                            <div class="input-group">
                                <input id="search-end-date" type="text" class="form-control form-date date-day" readonly value="<?=date('Y-m-d')?>">
                                <span class="input-group-addon"><a onclick="Utils.clearDatetimePicker(this)" href="javascript:;"><span class="glyphicon glyphicon-remove"></span></a></span>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.row -->
            </div><!-- ./box-body -->
            <div class="box-footer">
                <div class="row col-md-12">
                    <div class="col-md-2 col-md-offset-4">
                        <button id="btn-search" class="btn btn-primary btn-block" type="button"><i class="fa fa-search"></i> 搜索</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-excel" class="btn btn-success btn-block" type="button"><i class="fa fa-search"></i> 导出</button>
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
    var btnExcel = $("#btn-excel");
    var controllerMethodList = <?=json_encode($controllerMethodList)?>;
    $(function () {
        Utils.dateInit();
        initBind();
        getListByPage(1);
        controllerSelectChanged();
    });

    // DOM 元素绑定事件
    function initBind() {
        btnSearch.on("click", function () {
            getListByPage(1);
        });
        btnExcel.on("click", function () {
            var param = getParam();
            Utils.ajax({
                url: "<?=site_url('log/excel')?>",
                data: {param: JSON.stringify(param)},
                success: function (data) {
                    if(data.success){
                        window.open(data.excelPath);
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        })
    }

    // 获取列表数据
    function getListByPage(page) {
        var param = getParam();
        var startDate = $("#search-start-date").val().trim();
        var endDate = $("#search-end-date").val().trim();
        Utils.ajax({
            url: "<?=site_url('log/lists')?>/" + page,
            data: {param: JSON.stringify(param), startDate: startDate, endDate: endDate},
            success: function (data) {
                if(data.success){
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
        var paramEqual = {};
        var controller = $("#search-controller").val().trim();
        if (controller !== "-1") {
            paramEqual['a.controller_id'] = controller;
        }
        var method = $("#search-method").val().trim();
        if(method !== "-1") {
            paramEqual['a.method_id'] = method;
        }
        param['like'] = paramLike;
        param['equal'] = paramEqual;
        return param;
    }

    function controllerSelectChanged() {
        Utils.getSelectChildHtml('search-controller','search-method', controllerMethodList, 'id', 'describe');
    }

</script>