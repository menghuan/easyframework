<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>

<div class="right">
<div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list','','admin'); ?>" class="on">后台理员管理</a></li>
            <li><a href="<?php echo $this->url('add','','admin'); ?>">添加管理员</a></li>
        </ul>
    
        <div class="tk_main1">
        <div class="tk_search">
            <form action='<?php echo $this->url('list','','admin'); ?>'  name='form'  id='form' method="GET" >      
                <p>账号：<input type="text" name="username" value="<?php echo $search['username']?>"/></p>
                <p>用户角色 
                    <select name="role_id" class="-in-select" >
                        <option value='0'>--请选择--</option>
                        <?php foreach($RoleCaches as $val){ dump($val);?>
                        <option value='<?php echo $val['rid']?>' <?php if($search['role_id']==$val['rid']) echo "selected"; ?> ><?php echo $val['name']?></option>
                        <?php }?>                        
                    </select>
                </p>
                <p>地区
                    <select name="areaid" class="-in-select" >
                        <option value='0'>--请选择--</option>
                        <?php foreach($areaCaches as $val){?>
                        <option value='<?php echo $val['areaid']?>' <?php if($search['areaid']==$val['areaid']) echo "selected"; ?> ><?php echo $val['name']?></option>
                        <?php }?>                        
                    </select>
                </p>
                                
                <p>状态：
                    <select name="status" class="-in-select" >
                        <option value='0' <?php if($search['status']==0) echo "selected"; ?> >正常</option>
                        <option value='1' <?php if($search['status']==1) echo "selected"; ?> >禁用</option>
                    </select>
                </p>				
                <input type="submit" class="search_btn" value="搜索" />
                <input type="reset" class="zero_btn" value="重置" />
            </form>
        </div>

        <table width="100%" border="0" class="table_tk_tit">
              <tr>               
                <td width="5%"><input name="" class="J_checkall"  type="checkbox" value="" />&nbsp;&nbsp;ID</td>
                <td width="10%">账号</td>
                <td width="10%">姓名</td>
                <td width="10%">手机</td>
                <td width="10%">Email</td>
                <td width="5%">地区</td>
                <td width="10%">用户角色</td>
                <td width="10%">注册时间</td>
                <td width="5%">状态</td>
                <td width="15%">操作</td>
              </tr>            
         </table>
            
         <table width="100%" border="0" class="table_tk">  
                <?php if(!empty($list)){ foreach($list as $key=>$val){?>
                <tr  id="tr_<?php echo $val['id'];?>">
                      <td align="left" width="5%"><input name="" class="J_checkitem"  type="checkbox" value="<?php echo $val['id'];?>" />&nbsp;<?php echo $val['id'];?></td>
                      <td align="left" width="10%"><?php echo $val['username'];?></td>
                      <td align="left" width="10%"><?php echo $val['realname'];?></td>
                      <td align="left" width="10%"><?php echo $val['phone'] ? $val['phone'] : '暂无';?></td>
                      <td align="left" width="10%"><?php echo $val['email'] ? $val['email'] : '暂无';?></td>					  
                      <td align="middle" width="5%"><?php if(!empty($val['areaid']) && !empty($areaCaches[$val['areaid']]['name'])){ echo $areaCaches[$val['areaid']]['name']; } ?></td>              
                      <td align="middle" width="10%"><?php if(!empty($val['role_id']) && !empty($RoleCaches[$val['role_id']]['name'])){ echo $RoleCaches[$val['role_id']]['name']; } ?></td>
                      <td align="middle" width="13%"><?php echo date("Y年m月d日",$val['times']); ?></td>
                      <td align="middle" width="5%"><?php echo $val['status'] == 0 ? "<font color='green'>正常</font>" : "<font color='red'>禁用</font>";?></td>
                      <td align="reight" width="15%">
                            <a href="<?php echo $this->url('edit',array('id'=>$val['id']),'admin'); ?>" >
                            编辑</a> |
                            <a href="javascript:;" class="J_confirmurl" data-uri="<?php echo $this->url('Delete',array('id'=>$val['id']),'admin'); ?>"  data-id="<?php echo $val['id'];?>" data-acttype="ajax" data-type="1" data-msg="确定要删除改管理员吗？">
                            删除</a> <br/>
                      </td>
                </tr>
                <?php } }else{ ?>
                <tr><td colspan="20">暂无管理员信息</td></tr>
                <?php } ?>
          </table>
		 <div class="page">
			<?php echo $pages; ?>
		 </div>
    </div>    
    </div>
    </div>
</div>
<script language="javascript">
function confirmurl(url,message)
{
    if(confirm(message)) location.href = url;;
}
</script>
<?php  include $this->defaulttpldir.'/index/footer.php';?>

