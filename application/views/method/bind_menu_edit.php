<div class="modal-dialog">
    <div class="modal-content">
        <form id="submitForm">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title"><?=$actionName?>绑定菜单</h4>
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
                                    <label>所属主菜单</label>
                                    <select name="menu_id" class="form-control">
                                        <?php foreach($menuList as $menu):?>
                                            <option value="<?=$menu['id']?>" <?=$info['menu_id'] == $menu['id'] ? 'selected' : ''?>><?=$menu['name']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>菜单名称<span class="required"> * </span></label>
                                    <input type="text" class="form-control" name="name" value="<?=$info['name']?>" placeholder="请输入菜单名称(必填)"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>菜单排序<span class="required"> * </span></label>
                                    <input type="text" class="form-control" name="sort" value="<?=$info['sort'] ? $info['sort'] : $sort ?>" placeholder="请输入菜单排序(必填)" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>菜单参数参数</label>
                                    <input type="text" class="form-control" name="param" value="<?=$info['param']?>" placeholder="请输入菜单参数(选填)" />
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
    var form = $('#submitForm');
    var rules = {
        name: {
            required: true,
            minlength: 2
        },
        sort: {
            required: true,
            minlength: 1
        }
    };
    Utils.formValidate(form, rules, function () {
        Utils.formAjax(form, {
            url: "<?=site_url('method/bindMenuEdit/'.$info['id'].'/'.$action)?>",
            success: function (data) {
                Utils.noticeSys(data);
                if(data.success) {
                    // 如果需要操作完自动隐藏则开启
                    $("#div-modal-sub").modal('hide');
                    loadMethodMenuList();
                }
            }
        });
    });
</script>