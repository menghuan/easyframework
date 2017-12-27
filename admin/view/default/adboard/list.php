<?php include $this->defaulttpldir . '/layouts/header.php'; ?>
<?php include $this->defaulttpldir . '/layouts/left.php'; ?>
<div class="right">
    <div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list', '', 'adboard'); ?>" class="on">广告位管理</a></li>
            <li><a href="<?php echo $this->url('add', '', 'adboard'); ?>">添加广告位</a></li>
        </ul>
        <div class="tk_main1">
            <table width="100%" border="0" class="table_tk_tit">
                <tr>               
                    <td width="5%">&nbsp;&nbsp;ID</td>
                    <td width="5%">广告位名称</td>
                    <td width="5%">广告位尺寸</td>
                    <td width="5%">广告位说明</td>
                    <td width="5%">状态</td>
                    <td width="5%">管理操作</td>
                </tr>            
            </table>
            <table width="100%" border="0" class="table_tk">
                <?php if (!empty($list)) { ?>
                    <?php foreach ($list as $key => $val) { ?>
                    <tr>
                        <td align="left" width="5%">&nbsp;<?php echo $val['id'];?></td>
                        <td align="left" width="5%"><?php echo $val["name"];?></td>
                        <td align="left" width="5%"><?php echo $val["width"]."*".$val["height"];?></td>
                        <td align="left" width="5%"><?php echo $val["description"];?></td>
                        <td align="left" width="5%"><?php echo ($val["status"]=="0") ? "<font color='red'>禁用</font>" : "<font color='green'>启用</font>";?></td>
                        <td align="left" width="5%">
                            <a href="<?php echo $this->url('edit',array('id'=>$val['id']),'adboard'); ?>" >编辑</a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php }else{ ?>
                <tr><td colspan="20">暂无广告位信息</td></tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php include $this->defaulttpldir . '/index/footer.php'; ?>
