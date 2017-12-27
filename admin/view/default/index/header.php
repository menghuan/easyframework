<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta   http-equiv= "Pragma"   content= "no-cache" /> 
<meta   http-equiv= "Cache-Control"   content= "no-cache" /> 
<meta   http-equiv= "Expires"   content= "0" /> 
<title>中公教育网校学习平台</title>
<link href="<?php  echo PUBLIC_URL; ?>public/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/swfobject.js"></script>
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/jquery-1.8.2.min.js"></script>
</head>
<body>
<div class="heard">
	<div class="top_right">
<!--            <a href="<?php echo $this->url('headerSaveMember',array('id'=>$this->session('userid')),'index');?>"  class="ghtx">更换头像</a>-->
<!--            <a href="javascript:void(0)" onclick="parent.mainFrame.location='<?php echo $this->url('headerSaveMember',array('id'=>$this->session('userid')),'index');?>';return false;"  class="xgnc">修改昵称</a>-->
			<a href="javascript:void(0)" onclick="parent.mainFrame.location='<?php echo $this->url('headerSaveMember',array('id'=>$this->session('userid')),'index');?>';return false;"  class="xgnc">修改密码</a>
            <a href="javascript:void(0)"  onclick="parent.top.location='<?php echo $this->url('Logout',null,'index');?>';return false;">退出登录</a>
            <a href="javascript:void(0)" id="lits"></a>
        </div>
	<h1 class="logo"><span>中公</span><font>网校</font></h1>
</div>
</body>
</html>