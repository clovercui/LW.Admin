<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="submitForm">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?= $actionName ?></h4>
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
                                    <label>登录名<span class="required"> * </span></label>
                                    <input name="loginname" type="text" class="form-control"
                                           value="<?= $info['loginname'] ?>"
                                           placeholder="请输入登录名（必填）">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>用户名<span class="required"> * </span></label>
                                    <input name="username" type="text" class="form-control"
                                           value="<?= $info['username'] ?>" placeholder="请输入用户名（必填）">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>密码</label>
                                    <input name="password" type="password" class="form-control"
                                           value="<?= $info['password'] ?>"
                                           placeholder="请输入登录密码（必填）">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>角色</label>
                                    <select name="role_id" class="form-control">
                                        <?php foreach ($roleList as $role): ?>
                                            <option
                                                value="<?= $role['id'] ?>" <?= $role['id'] == $info['role_id'] ? 'selected' : '' ?>><?= $role['name'] ?></option>
                                        <?php endforeach; ?>
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
            loginname: {
                required: true,
                minlength: 2
            },
            username: {
                required: true,
                minlength: 2
            },
            password: {
                required: true,
                minlength: 6
            }
        };
        Utils.formValidate(form, rules, function () {
            Utils.formAjax(form, {
                url: "<?=site_url('admin/edit/'.$info['id'])?>",
                success: function (data) {
                    Utils.noticeSys(data);
                    if (data.success) {
                        // 如果需要操作完自动隐藏则开启
                        $("#div-modal").modal('hide');
                        getListByPage(1);
                    }
                }
            })
        })
    })
</script>
