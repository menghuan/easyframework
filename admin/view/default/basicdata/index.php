<?php  include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>
<div class="right">
	<div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" class="on">公司性质</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >公司规模</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >行业领域</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >工作经验</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >学历</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >工作性质</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >到岗时间</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >期望月薪范围</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >技能掌握程度</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >发展阶段</a></li>
            <li><a href="<?php echo $this->url('index',array('name'=>'company_nature'),'basicdata'); ?>" >公司标签</a></li>
            <li><input type="button" id="Generate" class="search_btn" value="生成缓存"/></li>
        </ul>
	<form id="info_form" action="{:u('setting/edit')}" method="post">
            <table width="100%" class="table_form">
                <tr>
                  <td>
                        <table width="100%" cellpadding="2" cellspacing="1" class="table_form" id="item_type">
                            <tbody class="add_item_type">
                                <?php
                                   if(!empty($list)){
                                       foreach($list as $k=>$v){
                                ?>
                                <tr>
                                        <th width="200">
                                        <a href="javascript:void(0);" class="blue"  <?php if($k == 0){ ?>onclick="add_type(this);"<?php }else{ ?>onclick="del_type(this);"<?php } ?>><img <?php if($k == 0){ ?>src="<?php echo PUBLIC_URL; ?>public/images/tv-expandable.gif"<?php }else{ ?>src="<?php echo PUBLIC_URL; ?>public/images/tv-collapsable.gif"<?php } ?>/></a>
                                        </th>
                                        <td><input type="text" name="setting[company_nature][]" class="-input-text" value="<?php echo $v;?>"></td>
                                    </tr>
                                <?php
                                       }
                                   }else{
                                ?>
                                <tr>
                                    <th width="200">
                                    &nbsp;&nbsp;<a href="javascript:void(0);" class="blue" onclick="add_type(this);"><img src="<?php echo PUBLIC_URL; ?>public/images/tv-expandable.gif" /></a>
                                    </th>
                                    <td><input type="text" name="setting[company_nature][]" class="-input-text"></td>
                                </tr>
                                   <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" class="-c-submit" value="提交"/>
                    </td>
                </tr>
            </table>
	</form>
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
//设置操作
function add_type()
{
    $("#hidden_type .add_item_type").clone().insertAfter($("#item_type .add_item_type:last"));
}
function del_type(obj)
{
	$(obj).parent().parent().remove();
}
</script>

<table id="hidden_type" style="display:none;">
<tbody class="add_item_type">
<tr>
    <th width="200">
    <a href="javascript:void(0);" class="blue" onclick="del_type(this);"><img src="<?php echo PUBLIC_URL; ?>public/images/tv-collapsable.gif" /></a>
    </th>
    <td><input type="text" name="setting[company_nature][]" class="-input-text" size="30"></td>
</tr>
</tbody>
</table>
