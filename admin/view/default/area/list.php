<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>
<div class="right">
	<div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('index','','user'); ?>" class="on">地区管理</a></li>
            <li><a href="javascript:;" class="J_showdialog" data-acttype="ajax" data-uri="<?php echo $this->url('addarea',array('id'=>$val['areaid']),'area'); ?>" data-type="2" data-title="添加子地区" data-id="add" data-width="700" data-height="250">添加地区</a></li>
        </ul>
    
        <div class="tk_main1">
        <div class="tk_search">
            <input type="button" id="Generate" class="search_btn" value="生成缓存"/>
        </div>
            
         <table width="100%" border="0" class="table_tk">
             <tr>
                <td align='center' style="background: #6da1c9;"><!-- <input name="" class="J_checkall"  type="checkbox" value="" /> -->&nbsp;&nbsp;ID</td>
                <td align='left' style="background: #6da1c9;">地区名称</td>
                <td align='center' style="background: #6da1c9;">父地区ID</td>
                <td align='center' style="background: #6da1c9;">区域划分</td>
                <td align='center' style="background: #6da1c9;">状态</td>
                <td align='center' style="background: #6da1c9;">排序</td>
                <td align='center' style="background: #6da1c9;">操作</td>
              </tr>
                <?php if(!empty($list)){ 
                    echo $list;
                }else{ ?>
                <tr><td colspan="20">暂无地区信息</td></tr>
                <?php } ?>
          </table>
           <div class="tk_xuanall"><!-- <input name="" class="J_checkall"  type="checkbox" value="" />全选/取消<input type="submit" class="del_btn" value="删除" /> --></div>
    </div>    
    </div>
    </div>
</div>
<?php  include $this->defaulttpldir.'/index/footer.php';?>
<script>
$('#Generate').live('click',function(){
    var url = '<?php echo $this->url('Generate','','area'); ?>';
    $.ajax({
        url:url, 
        dataType: 'json',
        success: function(text) {
            alert(text.msg);
        }
    });
});
</script>
