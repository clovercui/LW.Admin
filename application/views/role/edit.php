<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="submitForm">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?=$actionName?></h4>
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
                                    <label>角色名称<span class="required"> * </span></label>
                                    <input name="name" type="text" class="form-control" value="<?= $info['name'] ?>"
                                           placeholder="请输入菜单名称（必填）">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>归属角色<span class="required"> * </span></label>
                                    <select name="parent_id" class="form-control">
                                        <?php foreach($roleList as $role):?>
                                            <option value="<?=$role['id']?>" <?= $role['id'] == $info['parent_id'] ? 'selected' : '' ?>><?=$role['name']?></option>
                                        <?php endforeach;?>
                                    </select>
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
    $(function () {
        var form = $('#submitForm');
        var rules = {
            name: {
                required: true,
                minlength: 2
            }
        };
        Utils.formValidate(form, rules, function () {
            Utils.formAjax(form, {
                url: "<?=site_url('role/edit/'.$info['id'])?>",
                success: function (data) {
                    Utils.noticeSys(data);
                    if(data.success) {
                        // 如果需要操作完自动隐藏则开启
                        $("#div-modal").modal('hide');
                        getList();
                    }
                }
            })
        })
    })
</script>
