<body>
<div class="right_con">
	<div class="zg_jw_dq">
    	<p class="zg_jw_left"><a href="#">用户 ></a><a href="#">修改信息</a></p>
    </div>
<form method="post" action="<?php echo Yii::app()->createUrl('Index/DoHeaderSaveMember'); ?>" >
<input type='hidden' name='id' value="<?php echo $data['userid']?>" >
    <table cellpadding="0" cellspacing="0" border="0" class="zg_jw_qh">
    	<tr>
        	<td width="90" align="right" height="40">密&nbsp;&nbsp;&nbsp;码：</td>
                <td><input type='text' name="post[password]" id='password' class='zg_jw_stx' ><a href='#' onclick='CreatePassword()' >&nbsp;生成密码</a><span class="zg_jw_red"> *不修改请留空 </span></td>
        </tr>
        <tr>
        	<td align="right" height="40">真实姓名：</td>
                <td><input type='text' name="post[realname]" value='<?php echo $data['realname']?>' class='zg_jw_stx' ></td>
        </tr>
        <tr>
        	<td align="right" height="40">联系方式：</td>
                <td><input type='text' name="post[phone]" value='<?php echo $data['phone']?>' class='zg_jw_stx' ></td>
        </tr>
        <tr>
        	<td align="right" height="40"></td><td><input type="submit" value="" class="zg_jw_btn" /></td>
        </tr>
    </table>
</form>
</div>

<div id="menuContent" class="menuContent" style="display:none; position: absolute;">
	<ul id="treeDemo" class="ztree" style="margin-top:0; width:160px;"></ul>
</div>

</body>

<script language="javascript">
 function CreatePassword()
	{
	    $.post("<?php echo Yii::app()->createUrl('member/CreatePassword')?>", {},function (data,textStatus){
			if(data.status==1){
				  $('#password').val(data.data);
				  $.dialog.tips(data.info,1,'success.gif');
			}else{
				  $.dialog.tips(data.info,1);
			}
		},'json');
	}
</script>


</html>

