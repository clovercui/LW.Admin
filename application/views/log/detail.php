<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="submitForm">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title">日志详细信息</h4>
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
                                    <label>控制器名</label>
                                    <input type="text" class="form-control" readonly value="<?=$info['controller_name'].'['.$info['controller_describe'].']'?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>方法名</label>
                                    <input type="text" class="form-control" readonly value="<?=$info['method_name'].'['.$info['method_describe'].']'?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>IP</label>
                                    <input type="text" class="form-control" readonly value="<?=$info['ip']?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>操作人</label>
                                    <input type="text" class="form-control" readonly value="<?=$info['admin_name']?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>操作时间</label>
                                    <input type="text" class="form-control" readonly value="<?=$info['method_name']?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>GET参数</label>
                                    <input type="text" class="form-control" readonly value="<?= $info['get_params'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>POST参数</label>
                                    <textarea class="form-control" cols="30" rows="3"  readonly><?=$info['post_params']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div><!-- ./box-body -->
                </div><!-- /.box -->
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">关闭</button>
            </div>
        </form>
    </div><!-- /.modal-content -->
</div>