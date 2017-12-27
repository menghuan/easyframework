<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>
<div class="-main">
	<!--right-->
        <div class="right">
		<div class="right_box">
		
        <div class="tk_main2">
            <ul class="tk_tit">		
				<li><a href="<?php echo $this->url('editPass','','admin'); ?>" class="on" >更改密码</a></li>
			</ul>
            <div class="1107-add">
               <form action="<?php echo $this->url('doEditPass','','admin'); ?>" method="post" id="info_form" > 
                   <input type='hidden' name='userid' value="<?php echo $userid?>" >
            	<table border="0" cellpadding="0" cellspacing="0" class="1107-table-jbxx">
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>账号：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type="text" disabled="disabled" value='<?php echo $username?>' class="-input-text" /><span class="-btx"></span>
                        </td>
                    </tr> 
                    
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>旧密码：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type='password' name="old_password"  value=""  class="-input-text" /><span id="J_old_passwordTip" class="-btx">*</span>                           
                        </td>
                    </tr> 
					<tr>
                    	<td width="15%" class="o-table-l"><strong>新密码：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type='password' name="new_password"  value=""  class="-input-text" /><span id="J_new_passwordTip" class="-btx">*</span>                           
                        </td>
                    </tr> 
					<tr>
                    	<td width="15%" class="o-table-l"><strong>重复新密码：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type='password' name="rep_password"  value=""  class="-input-text" /><span id="J_rep_passwordTip" class="-btx">*</span>                           
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
		ele:'input[name="old_password"]',
		datatype:"*0-20",
                ajaxurl:"/index.php/admin/existpass/",
                nullmsg:"密码不正确！",
                validform_valid:"false",
                errormsg:"密码不正确！"
	},
	{
		ele:'input[name="new_password"]',
		datatype:
				/^[a-zA-Z0-9_-]{6,20}$/,
                ignore:"ignore",
                nullmsg:"请输入密码",
                errormsg:"密码应该为6-20位之间，且只包含大小字母、下划线“_”、横杠“-”"
	},
	{
		ele:'input[name="rep_password"]',
		datatype:
				"*0-20",
                ignore:"ignore",
                recheck:"new_password",
                nullmsg:"请再次输入密码！",
                errormsg:"两次密码不一致！"
	},
	
	]);
})

</script>
<?php  include $this->defaulttpldir.'/index/footer.php';?>


