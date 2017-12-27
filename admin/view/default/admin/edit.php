<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>
<div class="-main">
	<!--right-->
        <div class="right">
		<div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list','','admin'); ?>" >后台管理员管理</a></li>
            <li><a href="<?php echo $this->url('add','','admin'); ?>" class="on">添加管理员</a></li>
        </ul>
        <div class="tk_main2">
            <p class="1107-tab-add"><span class="hover">基本信息</span></p>
            <div class="1107-add">
               <form action="<?php echo $this->url('doEdit','','admin'); ?>" method="post" id="info_form" > 
                   <input type='hidden' name='id' value="<?php echo $data['id']?>" >
            	<table border="0" cellpadding="0" cellspacing="0" class="1107-table-jbxx">
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>账号：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type="text" disabled="disabled" value='<?php echo $data['username']?>' class="-input-text" /><span class="-btx"></span>
                        </td>
                    </tr>
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>密码：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type='password' name="password"  value=""  class="-input-text" /><span id="J_passwordTip" class="-btx">*密码为空，则不修改</span>                           
                        </td>
                    </tr>
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>真实姓名：</strong></td>
                        <td>
                            <input type="text"  name="realname"  value="<?php echo $data['realname']?>" class="-input-text" />
                        </td>
                    </tr>
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>邮箱：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type="text" name="email"  value='<?php echo $data['email']?>' class="-input-text" /><span id="J_emailTip" class="-btx"></span>
                        </td>
                    </tr>
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>手机：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type="text"  name="phone"  value="<?php echo $data['phone']?>" class="-input-text" /><span id="J_phoneTip" class="-btx"></span>
                        </td>
                    </tr>
                    <tr>
                    	<td class="o-table-l"><strong>用户角色：</strong></td>
                        <td>
                            <select name="role_id" class="-in-select">
                                <option value="0">--请选择--</option>
                                <?php foreach($RoleCache as $val){?>
                                    <option value='<?php echo $val['rid']?>' <?php if($val['rid'] == $data['role_id']){ ?> selected <?php } ?> ><?php echo $val['name']?></option>
                                <?php }?>
                            </select>                             
                        </td>
                    </tr>
                    <tr>
                    	<td class="o-table-l"><strong>状态：</strong></td>
                        <td>
                            <select class="-in-select" name='status'>
                            	<option value='0' <?php if(0 == $data['status']){ ?> selected <?php } ?> >正常</option>
                                <option value='1' <?php if(1 == $data['status']){ ?> selected <?php } ?> >禁用</option>
                            </select>
                        </td>
                    </tr>                                          
                </table>
                <p class="-z-submit">
                    <input type="submit" class="-c-submit" value="提交" />
                </p>                    
              </form>
            </div>
        </div>
    </div>
    </div>
    <!--//right_over-->
</div>
</div>
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/Validform_v5.3.2_min.js"></script>
<script language="javascript">
$(function(){
    var demo = $("#info_form").Validform({
		showAllError:true,
                tiptype:function(msg,o,cssctl){
                    if(o.type==2){
                        $('#J_'+o.obj.context.name+'Tip').attr('class','').html('');
                    }else if(o.type==3){
                        if(o.obj.context.value==''){
                            $('#J_'+o.obj.context.name+'Tip').attr('class','-btx').html(msg);
                        }else{
                            $('#J_'+o.obj.context.name+'Tip').attr('class','-btx').html(msg);
                        }
                    } 
                },
		ajaxPost:false
	});
	
	demo.addRule([       
	{
		ele:'input[name="password"]',
		datatype:"*6-20",
                ignore:"ignore",
                nullmsg:"请输入密码",
                errormsg:"密码应该为6-20位之间"
	},
        {
		ele:'input[name="email"]',
		datatype:"e",
                ignore:"ignore",
                nullmsg:"请输入邮箱地址",
                errormsg:"邮箱格式不正确"
	},
        {
		ele:'input[name="phone"]',
		datatype:"m",
                ignore:"ignore",
                nullmsg:"请输入手机号",
                errormsg:"手机号不正确"
	}
	]);
})

</script>
<?php  include $this->defaulttpldir.'/index/footer.php';?>


