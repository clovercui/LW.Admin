<?php
$assets = isset($assets) ? $assets : array();
$assets['cssList'] = isset($assets['cssList']) ? $assets['cssList'] : array();
$assets['jsList'] = isset($assets['jsList']) ? $assets['jsList'] : array();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <base href="<?= base_url(); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= WEB_NAME ?></title>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="assets/css/skins/all-skins.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <!-- 开始页面级样式 -->
    <?php foreach ($assets['cssList'] as $css): ?>
        <link href="<?= $css ?>" rel="stylesheet" type="text/css">
    <?php endforeach; ?>
    <!--http://aimodu.org:7777/admin/index_iframe.html?q=audio&search=#-->
    <style type="text/css">
        html {
            overflow: hidden;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="./plugins/ie9/html5shiv.min.js"></script>
    <script src="./plugins/ie9/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<input type="hidden" id="input-baseUrl" value="<?= base_url() ?>">
<input type="hidden" id="input-siteUrl" value="<?= site_url() ?>">
<div class="login-box">
    <div class="login-logo">
        <a href="<?= site_url('loginout/login') ?>"><b><?= WEB_NAME ?></b>系统</a>
    </div>
    <div class="login-box-body">
        <br>
        <form id="loginForm">
            <div class="form-group has-feedback">
                <input id="loginname" name="loginname" type="text" class="form-control" placeholder="登录名">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input id="password" name="password" type="password" class="form-control" placeholder="密 码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-7">
                    <div class="form-group has-feedback">
                        <input class="form-control" id="verifyCode" name="verifyCode" type="text" placeholder="验证码">
                    </div>
                </div>
                <div class="col-xs-5" id="div-verifyCode"></div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button id="submitBtn" type="submit" name="doSubmit" class="btn btn-primary btn-block">登陆</button>
                </div><!-- /.col -->
            </div>
        </form>

        <div class="social-auth-links text-center hide">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-info">注册</a>
        </div><!-- /.social-auth-links -->

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<script src="assets/js/utils.js"></script>
<?php foreach ($assets['jsList'] as $js): ?>
    <script src="<?= $js ?>"></script>
<?php endforeach; ?>

<script>
    var backUrl;
    $(function () {
        $("#div-verifyCode").on('click', function () {
            getVerifyCode();
        });
        getVerifyCode();
        loginFormInit();
        checkBack();
    });

    function checkBack() {
        var currentBackUrl = Utils.getUrlParam('backUrl');
        console.log(currentBackUrl);
        if(currentBackUrl !== null) {
            backUrl = currentBackUrl;
            Utils.noticeWarning('登录状态失效,请重新登录');
        }
    }

    function warning() {
        Utils.confirm({
            title: '标题',
            text: '内容',
            confirm: function () {
                Utils.noticeSuccess("你确认了");
            },
            cancel: function () {
                Utils.noticeWarning("你取消了");
            }
        });
    }

    function loginFormInit() {
        var loginForm = $('#loginForm');
        var rules = {
            loginname: {
                minlength: 2,
                required: true
            },
            password: {
                minlength: 2,
                required: true
            },
            verifyCode: {
                minlength: 4,
                required: true
            }

        };
        Utils.loginValidate(loginForm, rules, function () {
            cancelClick("submitBtn");
            loginForm.ajaxSubmit({
                url: "<?=site_url('loginout/login')?>",
                type: 'post',
                dataType: 'json',
                success: function(data){
                    if(data.success){
                        Utils.noticeSuccess(data.msg);
                        backUrl = backUrl || "<?=site_url('home/index')?>";
                        window.location.href = backUrl;
                    } else {
                        Utils.noticeWarning(data.msg);
                        getVerifyCode();
                    }
                }
            });
        })
    }

    function getVerifyCode() {
        $.ajax({
            url: '<?=site_url('loginout/getVerifyCode')?>',
            type: 'post',
            dataType: 'json',
            success: function (data) {
                $("#div-verifyCode").html(data.html);
            }
        });
    }
</script>

</body>
</html>