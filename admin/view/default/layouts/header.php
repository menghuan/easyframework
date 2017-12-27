<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>easyframework后台管理</title>
        <!-- <base href="<?php echo WXWEB_URL; ?>"> -->
        <link href="<?php echo PUBLIC_URL; ?>public/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/slglobal.js"></script>
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/formvalidator.js"></script>
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/DatePicker/WdatePicker.js"></script>
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/admin.js"></script>
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/java.js"></script>
        <script type="text/javascript" src="<?php echo PUBLIC_URL; ?>public/js/fileuploader.js"></script>
        <script>
            //初始化弹窗
            (function(d) {
                d['okValue'] = '确定';
                d['cancelValue'] = '取消';
                d['title'] = '消息';
            })($.dialog.defaults);


            //全选反选
            $('.J_checkall').live('click', function() {
                $('.J_checkitem').attr('checked', this.checked);
                $('.J_checkall').attr('checked', this.checked);
            });
            var root = '<?php echo ROOT_URL; ?>';
            var areaid = "<?php echo $this->session('areaid'); ?>";
			var source_block ="<?php echo $_GET['a']; ?>";
        </script>
    </head>
    <body>
        <!--top-->
        <div class="top">
            <p class="dl">
                <?php $hraderperm = $this->session('perm'); ?>	
                <?php if($hraderperm['home']){ ?>				
                <a href="javascript:void(0)"  onclick="parent.top.location = '<?php echo $this->url('create_index', null, 'home'); ?>';
                return false;">生成首页</a>
                <?php } ?>
                <a href="javascript:void(0)"  onclick="parent.top.location = '<?php echo $this->url('Logout', null, 'index'); ?>';
                return false;">退出</a>
                <span>欢迎您：<?php
                    if ($this->session('username')) {
                        echo  $this->session('username');
                    }
                    ?></span></p>
            <div class="logo">easyframework·后台管理</div>
        </div>
        <div class="-main">


