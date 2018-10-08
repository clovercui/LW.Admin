<?php
// type = 1 控制器操作
$type = $info['parent_id'] == 0 ? 1 : 2;
$typeName = $info['parent_id'] == 0 ? '控制器' : '方法';
?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="submitForm">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?=$typeName.$actionName?></h4>
            </div>
            <div class="modal-body">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">基础信息</h3>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?= $typeName ?>名称<span class="required"> * </span></label>
                                    <input name="name" type="text" class="form-control" value="<?= $info['name'] ?>"
                                           placeholder="请输入名称（必填）">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?= $typeName ?>中文名<span class="required"> * </span></label>
                                    <input name="describe" type="text" class="form-control"
                                           value="<?= $info['describe'] ?>" placeholder="请输入中文名称（必填）">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- 如果是方法,则需要选择所属控制器 -->
                            <?php if ($info['parent_id'] != 0): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>所属控制器</label>
                                        <select name="parent_id" class="form-control">
                                            <?php foreach ($controllerList as $controller): ?>
                                                <option
                                                    value="<?= $controller['id'] ?>" <?= $info['parent_id'] == $controller['id'] ? 'selected' : '' ?>>
                                                    <?= "{$controller['describe']}({$controller['name']})" ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>菜单(Y/N)</label>
                                        <select name="is_menu" class="form-control">
                                            <option value="0" <?= $info['is_menu'] == 0 ? 'selected' : '' ?>>否</option>
                                            <option value="1" <?= $info['is_menu'] == 1 ? 'selected' : '' ?>>是</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>默认(Y/N)</label>
                                    <select name="is_default" class="form-control">
                                        <option value="0" <?= $info['is_default'] == 0 ? 'selected' : '' ?>>否</option>
                                        <option value="1" <?= $info['is_default'] == 1 ? 'selected' : '' ?>>是</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>开启日志(Y/N)</label>
                                    <select name="is_log" class="form-control">
                                        <option value="0" <?= $info['is_log'] == 0 ? 'selected' : '' ?>>否</option>
                                        <option value="1" <?= $info['is_log'] == 1 ? 'selected' : '' ?>>是</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>序号</label> <br>
                                    <input name="sort" type="text" class="form-control"
                                           value="<?= $info['sort'] ? $info['sort'] : $sort ?>"
                                           placeholder="请输入中文名称（选填）">
                                </div>
                            </div>
                        </div>
                    </div><!-- ./box-body -->
                </div><!-- /.box -->
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
                <button id="submitBtn" type="submit" name="doSubmit" class="btn btn-primary">确认</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div>

<script>
    // 当前操作对象: 1: 控制器  2: 方法
    var type = <?=$type?>;
    $(function () {
        var form = $('#submitForm');
        var rules = {
            name: {
                required: true,
                minlength: 2
            },
            describe: {
                required: true,
                minlength: 2
            }
        };
        Utils.formValidate(form, rules, function () {
            Utils.formAjax(form, {
                url: "<?=site_url('method/edit/'.$info['id'].'/'.$action)?>",
                data: {type: type},
                success: function (data) {
                    Utils.noticeSys(data);
                    if(data.success) {
                        // 如果需要操作完自动隐藏则开启
                        $("#div-modal").modal('hide');
                        if(type == 1) {
                            controllerUpdate(data.id);
                        } else {
                            loadMethodList();
                        }
                    }
                }
            });
        });
    });
</script>
