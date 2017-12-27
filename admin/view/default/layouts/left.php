<!--left-->
<div class="left">
    <div class="lbar">
        <?php $leftperm = $this->session('perm'); ?>
        <?php $action = getgpc();?>
	<ul class="left_ul">
           <li <?php  if($action['m']=='index'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index','','index'); ?>' >首页</a></li>
	</ul>
        <?php if($leftperm['user']){ ?>
        <p class="lbarhover">用户管理</p>
        <ul class="left_ul">   
            <?php if ($leftperm['user']) { ?><li <?php  if($action['m']=='user'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index','','user'); ?>' >用户管理</a></li><?php } ?>
	</ul>
        <?php } ?>
        <?php if ($leftperm['admin'] || $leftperm['role']) { ?>
        <p class="lbarhover">后台权限</p>
        <ul class="left_ul">
            <?php if ($leftperm['admin']) { ?><li <?php  if($action['m']=='admin' && $action['a']=='list'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('list','','admin'); ?>' >管理员管理</a></li><?php } ?>
            <?php if ($leftperm['role']) { ?><li <?php  if($action['m']=='role'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('list','','role'); ?>' >角色管理</a></li><?php } ?>
	</ul>
        <?php } ?>
        <?php if ($leftperm['typejob'] || $leftperm['area'] || $leftperm['basicdata']) { ?>
        <p class="lbarhover">设置</p>
        <ul class="left_ul">
            <?php if ($leftperm['typejob']) { ?><li <?php  if($action['m']=='typejob' && $action['a']=='list'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index','','typejob'); ?>' >职位类别</a></li><?php } ?>
            <?php if ($leftperm['area']) { ?><li <?php  if($action['m']=='area' && $action['a']=='list'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index','','area'); ?>' >地区管理</a></li><?php } ?>
            <?php if ($leftperm['basicdata']) { ?><li <?php  if($action['m']=='basicdata' && $action['a']=='index'){ ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index','','basicdata'); ?>' >基础数据</a></li><?php } ?>
	</ul>
        <?php } ?>
        <?php if ($leftperm['article']) { ?>
        <p class="lbarhover">内容</p>
        <ul class="left_ul">
            <?php if ($leftperm['article']) { ?><li <?php if ($action['m'] == 'article') { ?> class="-active" <?php } ?>><span>&gt;</span><a  href='<?php echo $this->url('index', '', 'article'); ?>' >文章管理</a></li><?php } ?>
            <?php if ($leftperm['category']) { ?><li <?php if ($action['m'] == 'category') { ?> class="-active" <?php } ?>><span>&gt;</span><a  href='<?php echo $this->url('index', '', 'category'); ?>' >栏目管理</a></li><?php } ?>
        </ul>
        <?php } ?>
        <?php if ($leftperm['adboard']) { ?>
        <p class="lbarhover">广告</p>
        <ul class="left_ul">
            <?php if ($leftperm['adboard']) { ?><li <?php if ($action['m'] == 'adboard') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('list', '', 'adboard'); ?>' >广告位管理</a></li><?php } ?>
            <?php if ($leftperm['advert']) { ?><li <?php if ($action['m'] == 'advert') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('list', '', 'advert'); ?>' >广告管理</a></li><?php } ?>
            <?php if ($leftperm['liveactive']) { ?><li <?php if ($action['m'] == 'liveactive') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('list', '', 'liveactive'); ?>' >直播互动</a></li><?php } ?>
        </ul>
        <?php } ?>
        <?php if ($leftperm['resume']) { ?>
        <p class="lbarhover">简历管理</p>
        <ul class="left_ul">
            <?php if ($leftperm['resume']) { ?><li <?php if ($action['m'] == 'resume') { ?> class="-active" <?php } ?>><span>&gt;</span><a  href='<?php echo $this->url('list', '', 'resume'); ?>' >简历管理</a></li><?php } ?>
            <?php if ($leftperm['resumetemplate']) { ?><li <?php if ($action['m'] == 'resumetemplate') { ?> class="-active" <?php } ?>><span>&gt;</span><a  href='<?php echo $this->url('list', '', 'resumetemplate'); ?>' >简历模板管理</a></li><?php } ?>
            <?php if ($leftperm['stationresume']) { ?><li <?php if ($action['m'] == 'stationresume' && $action['a'] == 'list') { ?> class="-active" <?php } ?>><span>&gt;</span><a  href='<?php echo $this->url('list', '', 'stationresume'); ?>' >HR下载简历文件夹管理</a></li><?php } ?>
            <?php if ($leftperm['stationresume']) { ?><li <?php if ($action['m'] == 'stationresume' && $action['a'] == 'paylist') { ?> class="-active" <?php } ?>><span>&gt;</span><a  href='<?php echo $this->url('paylist', '', 'stationresume'); ?>' >用户开通服务支付记录</a></li><?php } ?>
        </ul>
        <?php } ?>
        <?php if ($leftperm['jobs']) { ?>
        <p class="lbarhover">职位管理</p>
        <ul class="left_ul">
            <?php if ($leftperm['jobs']) { ?><li <?php if ($action['m'] == 'jobs') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index', '', 'jobs'); ?>' >职位管理</a></li><?php } ?>
        </ul>
        <?php } ?>
	<?php if ($leftperm['certificate']) { ?>
        <p class="lbarhover">公司</p>
        <ul class="left_ul">
            <?php if ($leftperm['certificate']) { ?><li <?php if ($action['m'] == 'certificate') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('index', '', 'certificate'); ?>' >认证管理</a></li><?php } ?>
            <?php if ($leftperm['company']) { ?>
                <li <?php if ($action['m'] == 'company' && $action['a'] == 'list') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('list', '', 'company'); ?>' >已注册公司列表</a></li>
                <li <?php if ($action['a'] == 'resumedeliverys') { ?> class="-active" <?php } ?>><span>&gt;</span><a href='<?php echo $this->url('resumedeliverys', '', 'company'); ?>' >未绑定公司简历记录</a></li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>
</div>