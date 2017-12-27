<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>

<div class="right">
<div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list','','role'); ?>" class="on">角色管理</a></li>
            <li><a href="<?php echo $this->url('add','','role'); ?>">添加角色</a></li>
        </ul>
    
        <div class="tk_main1">

        <table width="100%" border="0" class="table_tk_tit">
              <tr>               
                <td width="10%"><input name="" class="J_checkall"  type="checkbox" value="" />&nbsp;&nbsp;ID</td>
                <td width="10%">角色</td>
                <td width="5%">状态</td>
                <td width="12%">操作</td>
              </tr>            
         </table>
            
         <table width="100%" border="0" class="table_tk">  
                <?php if(!empty($list)){ foreach($list as $key=>$val){?>
                <tr  id="tr_<?php echo $val['id'];?>">
                      <td align="left" width="10%"><input name="" class="J_checkitem"  type="checkbox" value="<?php echo $val['id'];?>" />&nbsp;<?php echo $val['id'];?></td>
                      <td align="left" width="10%"><?php echo $val['name'];?></td>
                      <td align="left" width="5%"><?php echo $val['status'] == 0 ? "<font color='green'>正常</font>" : "<font color='red'>禁用</font>";?></td>
                      <td align="left" width="12%">
                            <a href="<?php echo $this->url('edit',array('id'=>$val['id']),'role'); ?>" >
                            编辑</a> |
                            <a href="javascript:;" class="J_confirmurl" data-uri="<?php echo $this->url('Delete',array('id'=>$val['id'],'rid'=>$val['rid']),'role'); ?>"  data-id="<?php echo $val['id'];?>" data-acttype="ajax" data-type="1" data-msg="确定要删除改用户吗？">
                            删除</a> <br/>
                      </td>
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="20">暂无角色</td></tr>
                <?php } ?>
          </table>
		 <div class="page">
			<?php echo $pags; ?>
		 </div>
    </div>    
    </div>
    </div>
</div>
<script language="javascript">
// 禁用和启用
function ban(pid,status){    
    if(pid == '') return false;
    if(status==1){
            var title="禁用操作";
            var msg="您确定要禁用吗？";
            var td="<font color='red' >已关闭</font>";
            var href="<a href='javascript:ban("+pid+",0);'>启用</a>";
    }else{
            var title="启用操作";
            var msg="您确定要启用吗？";
            var td="<font color='Green' >使用中</font>";
            var href="<a href='javascript:ban("+pid+",1);'>禁用</a>";
    }

    $.dialog({
        id:'confirm',
        title:title,
        width:360,
        padding:'10px 20px',
        lock:true,
        content:msg,
        ok:function(){
            $.post("<?php echo $this->url('Disabled','','role'); ?>",{roleid:pid,status:status},function(data){
                    if( data.status == 1 ){
                           $.slglobal.tip({content:'操作成功'});
                           $('#ban'+pid).html(href);
                           $('#tdban'+pid).html(td);
                    }else{
                           $.slglobal.tip({content:'操作失败...', icon:'error'});
                    }
           },'json');
        },
        cancel:function(){}
    });
}
</script>
<?php  include $this->defaulttpldir.'/index/footer.php';?>
