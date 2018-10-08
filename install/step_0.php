<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $html_title;?></title>
<link href="css/install.css" rel="stylesheet" type="text/css">
<link href="css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
</head>
<body>
<?php echo $html_header;?>
<div class="main">
  <div class="text-box" id="text-box">
    <div class="license">
      <h1>系统安装协议</h1>
      <p>感谢您选择本系统。本系统是自助研发的后台管理平台。官方网址为 http://www.lwadmin.cn。</p>
      <p>用户须知：本系统是面向发开人员的通用后台管理系统,本系统遵循开源协议，用户可以按照自己的要求自行二次开发使用。</p>
      <p>LW.Admin 依赖于其他开源组建,希望使用者尊重版权，不得以源码进行销售、修改版权归属等不良行为。</p>
      <p>LW.Admin只允许您在遵守本协议各项条款的情况下复制、下载、安装、使用或者以其他方式受益于本软件的功能或者知识产权。</p>
      <h3>I. 协议许可的权利</h3>
      <ol>
        <li>LW.Admin 系统版权归 LW工作室所有</li>
        <li>您可以在完全遵守本许可协议的基础上，不必支付任何费用便可使用。</li>
      </ol>
      <p></p>
      <h3>II. 有限担保和免责声明</h3>
      <ol>
        <li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
        <li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
        <li>LW工作室不对使用本软件构建的平台中的会员、商品或文章信息承担责任，全部责任由您自行承担。</li>
        <li>LW工作室对提供的软件和服务之及时性、安全性、准确性不作担保，由于不可抗力因素、LW工作室无法控制的因素（包括黑客攻击、停断电等）等造成软件使用和服务中止或终止，而给您造成损失的，您同意放弃追究LW工作室责任的全部权利。</li>
        <li>LW工作室特别提请您注意，LW工作室为了保障公司业务发展和调整的自主权，LW工作室拥有随时经或未经事先通知而修改服务内容、中止或终止部分或全部软件使用和服务的权利，修改会公布于LW工作室网站相关页面上，一经公布视为通知。LW工作室行使修改或中止、终止部分或全部软件使用和服务的权利而造成损失的，LW工作室不需对您或任何第三方负责。</li>
      </ol>
      <p></p>
      <p align="right">LW工作室</p>
    </div>
  </div>
  <div class="btn-box"><a href="index.php?step=1" class="btn btn-primary">同意协议进入安装</a><a href="javascript:window.close()" class="btn">不同意</a></div>
</div>
<?php echo $html_footer;?>
<script type="text/javascript">
$(document).ready(function(){
    //自定义滚定条
    $('#text-box').perfectScrollbar();
});
</script>
</body>
</html>
