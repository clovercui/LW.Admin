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
                <h3 class="box-title">控制器管理</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row col-md-12">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <label>当前控制器</label>
                                <select class="form-control" id="search-controller">
                                    <?php foreach($controllerList as $controller):?>
                                        <option value="<?=$controller['id']?>"><?="{$controller['describe']}({$controller['name']})"?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="searchOrderType" value="1">
                    </div><!-- /.box-body -->
                </div><!-- /.row -->
            </div><!-- ./box-body -->
            <div class="box-footer">
                <div class="row col-md-12">
                    <div class="col-md-2 col-md-offset-3">
                        <button id="btn-add-controller" class="btn btn-primary btn-block" type="button">添加控制器</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-edit-controller" class="btn btn-success btn-block" type="button">编辑控制器</button>
                    </div>
                    <div class="col-md-2">
                        <button id="btn-del-controller" class="btn btn-danger btn-block" type="button">删除控制器</button>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.box-footer -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"> 方法列表 <span id="list-name"></span></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body">
                <button id="btn-add-method" class="btn btn-primary">添加方法</button>
                <hr>
                <div id="dataList">
                    <!--动态ajax加载博文-->
                </div>
            </div>
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<script>
    var searchController = $("#search-controller");
    var btnAddController = $("#btn-add-controller");
    var btnEditController = $("#btn-edit-controller");
    var btnDelController = $("#btn-del-controller");
    var btnAddMethod = $("#btn-add-method");
    $(function () {
        init();
    });

    function init() {
        controllerChanged();
        searchController.on('change', function () {
            controllerChanged();
        });
        // 绑定添加控制器操作
        btnAddController.on('click', function () {
            Utils.ajax({
                url: "<?=site_url('method/add/1')?>",
                data: {type: 1},
                success: function (data) {
                    if(data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        });
        // 绑定编辑控制器操作
        btnEditController.on('click', function () {
            var controllerId = searchController.val();
            if(controllerId === undefined) {
                Utils.noticeWarning('当前没有控制器,请先添加'); return;
            }
            Utils.ajax({
                url: "<?=site_url('method/edit')?>/" + controllerId,
                success: function (data) {
                    if(data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        });
        // 绑定删除控制器操作
        btnDelController.on('click', function () {
            var controllerId = searchController.val();
            if(controllerId === undefined) {
                Utils.noticeWarning('当前没有控制器,请先添加'); return;
            }
            Utils.confirm({
                title: "温馨提示",
                text: "你确定要删除当前控制器吗?",
                confirm: function () {
                    Utils.ajax({
                        url: "<?=site_url('method/del')?>/" + controllerId,
                        data: {type: 1},
                        success: function (data) {
                            Utils.alertSys(data);
                            if(data.success){
                                controllerUpdate();
                            }
                        }
                    });
                }
            });
        })
        btnAddMethod.on('click', function () {
            var controllerId = searchController.val();
            if(controllerId === undefined) {
                Utils.noticeWarning('当前没有控制器,请先添加'); return;
            }
            Utils.ajax({
                url: "<?=site_url('method/add/1')?>",
                data: {type: 2, parentId: controllerId},
                success: function (data) {
                    if(data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            })
        })
    }

    // 控制变化操作
    function controllerChanged(){
        $("#list-name").text(": " + searchController.find("option:selected").text());
        loadMethodList();
    }

    // 载入方法列表
    function loadMethodList() {
        var controllerId = searchController.val();
        Utils.ajax({
            url: "<?=site_url('method/lists')?>/" + controllerId,
            success: function (data) {
                if(data.success) {
                    $("#dataList").html(data.html);
                } else {
                    Utils.noticeWarning(data.msg);
                }
            }
        });
    }

    // 控制器编辑时,更新控制 select
    function controllerUpdate(controllerId) {
        Utils.ajax({
            url: "<?=site_url('method/getControllerList')?>",
            success: function (data) {
                var list = data.controllerList;
                var html = "";
                for(var i = 0; i < list.length; i ++) {
                    html += "<option value='"+list[i]["id"]+"'>"+list[i]["describe"]+"(" +list[i]["name"]+ ")</option>"
                }
                searchController.html(html);
                if(controllerId) {
                    searchController.val(controllerId);
                }
                controllerChanged();
            }
        });
    }

</script>