<?php include $this->defaulttpldir . '/layouts/header.php'; ?>
<?php include $this->defaulttpldir . '/layouts/left.php'; ?>
<style type="text/css">
    .attachment_icon {margin-left: 8px;position: relative;}
    .attachment_tip {border: 1px #CCC solid;position: absolute;left: 16px;top: 16px;display: none;padding: 1px;z-index: 9;}
</style>
<div class="right">
    <div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list', '', 'advert'); ?>" class="on">广告管理</a></li>
            <li><a href="<?php echo $this->url('add', '', 'advert'); ?>">添加广告</a></li>
            <li><input type="button" id="Generate" class="search_btn" value="生成缓存"/></li>
        </ul>
        <table width="100%" border="0" class="table_tk_tit">
            <tr>           
                <td width="3%">&nbsp;&nbsp;ID</td>
                <td width="5%">广告名称</td>
                <td width="13%">广告链接</td>
                <td width="3%">广告类型</td>
                <td width="5%">广告位</td>
                <td width="5%">描述</td>
                <td width="5%">添加时间</td>
                <td width="2%">排序</td>
                <td width="5%">状态</td>
                <td width="5%">管理操作</td>
            </tr>            
        </table>
        <table width="100%" border="0" class="table_tk">
            <?php if (!empty($list)) { ?>
                <?php foreach ($list as $key => $val) { ?>
                <tr>
                    <td align="left" width="3%">&nbsp;<?php echo $val['id'];?></td>
                    <td align="left" width="5%"><?php echo $val["name"];?></td>
                    <td align="left" width="5%"><?php echo $val["url"];?></td>
                    <td align="left" width="3%">
                        <?php if($val["type"]==1) { ?>
                        图片<span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo WEB_URL.$val["content"];?>"><div class="attachment_tip" style="display: none;"><img src="<?php echo WEB_URL.$val["content"];?>"></div><img src="<?php echo PUBLIC_URL;?>public/images/image_s.gif"></span>
                        <?php }else{ ?>
                        文字
                        <?php } ?>
                    </td>
                    <td align="left" width="5%"><?php echo $adboard[$val["board_id"]]["name"];?></td>
                    <td align="left" width="5%"><?php echo $val["desc"];?></td>
                    <td align="left" width="5%"><?php echo date("Y-m-d",$val["add_time"]);?></td>
                    <td align="left" width="2%"><?php echo $val["ordid"];?></td>
                    <td align="left" width="5%"><?php echo ($val["status"]=="0") ? "<font color='red'>禁用</font>" : "<font color='green'>启用</font>";?></td>
                    <td align="left" width="5%">
                        <a href="<?php echo $this->url('edit',array('id'=>$val['id']),'advert'); ?>" >编辑</a> 
                    </td>
                </tr> 
                <?php } ?>
            <?php }else{ ?>
            <tr><td colspan="20">暂无广告信息</td></tr>
            <?php } ?>
        </table>
        <?php if (!empty($list)) { ?>
        <div class="tk_main1">
            <div class="page">
                <?php echo $pags; ?>
            </div>
        </div>   
        <?php } ?>
    </div>
</div>
<?php include $this->defaulttpldir . '/layouts/footer.php'; ?>
<script type="text/javascript">
    $(".del_btn").live("click",function(){
        var isc = "";
        $("input[class='J_checkitem']").each(function(){ //遍历table里的全部checkbox
            if($(this).attr("checked")) //如果被选中
                isc += $(this).val() + ","; //获取被选中的值
        });
        if(isc==""){
            $.slglobal.tip({content:"请选择要操作的项目", icon:'error'});
            return false;
        }
        $.dialog({
            title:"提示消息",
            content:"您确定要删除所选记录?",
            padding:'10px 20px',
            lock:true,
            ok:function(){
                var url = '<?php echo $this->url('batchdelete','','advert'); ?>';
                $.ajax({
                    url:url,
                    data:{ids:isc},
                    dataType: 'json',
                    success: function(result) {
                        if(result.status==1){
                            window.location.reload();
                        }else{
                            $.slglobal.tip({content:result.msg, icon:'error'});
                        }
                    }
                });
            },
            cancel:function(){}
        });
    });
    
    $('#Generate').live('click',function(){
        var url = '<?php echo $this->url('Generate','','advert'); ?>';
        $.ajax({
            url:url, 
            dataType: 'json',
            success: function(text) {
                alert(text.msg);
            }
        });
    });
</script>