<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中公·网校学习平台后台管理</title>
<link type="text/css" rel="stylesheet" href="<?php  echo PUBLIC_URL; ?>public/css/login_style.css" />
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/jquery.min.js"></script>
</head>

<body >
<form action="<?php echo $this->url('dologins','','index'); ?>"  method="post"  id="login_form">
<div class="login">
	<div class="login1">easyframework后台管理</div>
    <div class="login2">
    	<ul>
            <li><input name="passcode" type="text" class="input315" onblur="if (value=='') {value='请输入口令码'}" onfocus="if(value=='请输入口令码') {value=''}" value="请输入口令码" /></li>
            <li><span id='msgcode' ></span></li>
            <li><input name="username" type="text" class="input315" onblur="if (value=='') {value='请输入用户名'}" onfocus="if(value=='请输入用户名') {value=''}" value="请输入用户名" /></li>
            <li><span id='msg' ></span></li>
            <li><input name="password" type="password" class="input315" placeholder="请输入密码" /></li>
            <li><span></span></li>
            <li>
            <!--<a  href="javascript:;" onclick="changfy();" class="rest_kq">看不清？换一张</a>-->
            <input type="text" name="secode" value="验证码" class="input230" onblur="if (value=='') {value='验证码'}" onfocus="if(value=='验证码') {value=''}"  />
            <a href="javascript:;" onclick="changfy();"><img src="<?php echo $this->url('index','','seccodec') ?>" alt="验证码" align="absmiddle" id="verifyimg_dl"/></a>
            </li>
            <li ><input type="submit" value="登录" name="submit" class="button_login"/></li>
        </ul>
    </div>
</div>
</form>
</body>
</html>
<script>
//登录注册显示隐藏
function changfy(){
    var url = "/index.php/seccodec/"+Math.random();
    $('#verifyimg_dl').attr('src',url);
}
</script>

