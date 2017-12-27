<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示信息</title>
<link type="text/css" rel="stylesheet" href=<?php echo $css;?> />
</head>

<body>
<div class="right_con">
            <div class="zg_jw_ts">
              <div class="zg_ts"><img src=<?php echo $img;?> width="46" height="46" alt="对" align="absmiddle" /><?php echo $message;?></div>
              <p>页面将在<span class="zg_jw_red">3秒</span>后自动跳转，如果不想等待 <a href="<?php echo $redirect;?>">请点击这里跳转</a></p>
            </div>
			<script type="text/javascript">
				function redirect(url, time) {
					setTimeout("window.location='" + url + "'", time * 1000);
				}
				redirect('<?php echo $redirect;?>', 3);
			</script>
</div>
</body>
</html>
