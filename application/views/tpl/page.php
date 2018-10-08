<?php
$assets = isset($assets) ? $assets : array();
$assets['cssList'] = isset($assets['cssList']) ? $assets['cssList'] : array();
$assets['jsList'] = isset($assets['jsList']) ? $assets['jsList'] : array();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= base_url(); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <!-- 开始页面级样式 -->
    <?php foreach ($assets['cssList'] as $css): ?>
        <link href="<?= $css ?>" rel="stylesheet" type="text/css">
    <?php endforeach; ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.2.3 -->
    <script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #f9f9f9;">
<input type="hidden" id="input-baseUrl" value="<?= base_url() ?>">
<input type="hidden" id="input-siteUrl" value="<?= site_url() ?>">

<section class="content-header">
    <!-- 面包屑 -->
    <?php if (isset($breadcrumb)): ?>
        <ol class="breadcrumb">
            <?php for ($i = 0; $i < sizeof($breadcrumb); $i++): ?>
                <li>
                    <a href="<?= $breadcrumb[$i][1] ? $breadcrumb[$i][1] : "javascript:;" ?>"><?php echo (sizeof($breadcrumb) > $i) ? "<i class='fa fa-dashboard'>" : ""; ?></i><?= $breadcrumb[$i][0] ?></a>
                </li>
            <?php endfor; ?>
        </ol>
    <?php endif; ?>
</section>
<br>
<!-- Main content -->
<section class="content">
    <?= $tpl ?>
</section>
<!-- /.content -->
<div class="modal fade" id="div-modal" tabindex="100" aria-hidden="true"></div>
<div class="modal fade" id="div-modal-sub" tabindex="1000" aria-hidden="true"></div><!--第二层使用-->
<!-- 开始页面级插件-->
<?php foreach ($assets['jsList'] as $js): ?>
    <script src="<?= $js ?>" type="text/javascript"></script>
<?php endforeach; ?>
<!-- 结束页面级插件-->
<!-- AdminLTE App -->
<script src="assets/js/app.js"></script>
<script src="assets/js/utils.js"></script>
</body>
</html>
