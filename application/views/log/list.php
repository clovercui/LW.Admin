<div class="row table-responsive no-padding">
    <div class="col-md-12">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>操作人</th>
                <th>IP</th>
                <th>控制器</th>
                <th>方法</th>
                <th>GET参数</th>
                <th>POST参数</th>
                <th>记录日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$dataList) {
                echo "<td>当前没有数据</td>";
            } else {
                $i = 1;
                foreach ($dataList as $data): ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $data['admin_name'] ?></td>
                        <td><?= $data['ip'] ?></td>
                        <td><?= $data['controller_describe'] ?></td>
                        <td><?= $data['method_describe'] ?></td>
                        <td style="width:100px;overflow: hidden;"><?= $data['get_params'] ?></td>
                        <td><?= json_decode($data['post_params'], true) ? '有' : '无' ?></td>
                        <td><?= date('Y-m-d H:i:s', $data['record_time']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-info btn-log-info" data-id="<?=$data['id']?>">详情</button>
                        </td>
                    </tr>
                    <?php $i++; endforeach;
            } ?>
            </tbody>
        </table>
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="text-center">
        <?php if (isset($pageHtml)) {
            echo $pageHtml;
        } ?>
    </div>
</div><!-- /.row -->

<script>
    $(function () {
        $(".btn-log-info").on('click', function () {
            var id = $(this).data('id');
            Utils.ajax({
                url: '<?=site_url('log/detail')?>/' + id,
                success: function (data) {
                    if (data.success) {
                        $("#div-modal").html(data.html);
                        $("#div-modal").modal();
                    } else {
                        Utils.noticeWarning(data.msg);
                    }
                }
            });
        })
    })
</script>

