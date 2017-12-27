<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>
<div class="-main">
	<!--right-->
        <div class="right">
		<div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list','','role'); ?>" >角色管理</a></li>
            <li><a href="<?php echo $this->url('add','','role'); ?>" class="on">添加角色</a></li>
        </ul>
        <div class="tk_main2">
            <p class="1107-tab-add"><span class="hover">基本信息</span></p>
            <div class="1107-add">
               <form action="<?php echo $this->url('DoAdd','','role'); ?>" method="post" id="info_form" > 
            	<table border="0" cellpadding="0" cellspacing="0" class="1107-table-jbxx">
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>角色名称：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type="text" name="name"  value='' class="-input-text" /><span  class="-btx">*</span>
                        </td>
                    </tr> 
                    
                    <tr>
                    	<td width="15%" class="o-table-l"><strong>角色描述：</strong></td>
                        <td>
                            <input style="display:none">
                            <input type='text' name="description"  value=""  class="-input-text" />                     
                        </td>
                    </tr>   
                   <tr>
                    	<td class="o-table-l"><strong>权限</strong></td>
                        <td>
                            <div class="-js-kc">
                                <?php if($perm){ 
                                    foreach($perm as $k=>$v){?>
                                <p class="pFl"><label><input type="checkbox" name="perm[]"  value="<?php echo $k; ?>" /><?php echo $v; ?></label></p>
                                <?php }} ?>
                            </div>
                            <p class="-tips_all"><label><input type="checkbox" name="allselcet" />全选</label></p>
                            <p class="-tips">不选择则代表没有权限</p>
                        </td>
                    </tr>                    
                    <tr>
                    	<td class="o-table-l"><strong>状态：</strong></td>
                        <td>
                            <select class="-in-select" name='status'>
                            	<option value='0'  >正常</option>
                                <option value='1'  >禁用</option>
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
<script type="text/javascript">  
    
          var checkall=document.getElementsByName("perm[]");
          $('input[name="allselcet"]').click(function(){
              var t;
              if(this.checked){
                   t = true;
              }else{
                   t = false;
              }
              for(var $i=0;$i<checkall.length;$i++){  
                    checkall[$i].checked=t;  
              } 
          });          
</script>  
<?php  include $this->defaulttpldir.'/index/footer.php';?>

