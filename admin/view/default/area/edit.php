<div class="-main">
	<!--right-->
        <div class="right">
        <div class="right_box">
        <div class="tk_main2">
            <p class="1107-tab-add"><span class="hover">编辑地区</span></p>
            <div class="1107-add">
                <form action="<?php echo $this->url('doedit','','area'); ?>" method="post" id="info_form" > 
            	<table border="0" cellpadding="0" cellspacing="0" class="1107-table-jbxx">              
                    <tr>
                    	<td width="20%" class="o-table-l"><strong>父地区：</strong></td>
                        <td>
                            <select class="-in-select" name='parentid' style="width:352px">
                                <?php if(!$sid){ ?>
                                    <option value="0">---请选择---</option>
                                <?php } ?>
                                <?php echo $select_menus; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td class="o-table-l"><strong>地区名称：</strong></td>
                        <td>
                            <input type="text" id="name"  name="name"  value="<?php echo $areainfo['name']; ?>" class="-input-text" />
                        </td>
                    </tr>
                    <tr>
                    	<td class="o-table-l"><strong>排序：</strong></td>
                        <td>
                            <input type="text" name="sort"  value="<?php echo $areainfo['sort']; ?>" class="-input-text" />
                        </td>
                    </tr>
                    <tr>
                    	<td class="o-table-l"><strong>地区状态：</strong></td>
                        <td>
                            <select class="-in-select" name='disabled'>
                                <option value='0' <?php if(!$areainfo['disabled']){ ?>selected="selected"<?php } ?>>--正常--</option>
                                <option value='1' <?php if($areainfo['disabled']==1){ ?>selected="selected"<?php } ?>>--前台首页推荐--</option>
                                <option value='2' <?php if($areainfo['disabled']==2){ ?>selected="selected"<?php } ?>>--禁用--</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td class="o-table-l"><strong>区域划分：</strong></td>
                        <td>
                            <select class="-in-select" name='code'>
                                <option value='0' <?php if($areainfo['code']==0){ ?>selected="selected"<?php } ?>>--请选择区域划分--</option>
                                <option value='1' <?php if($areainfo['code']==1){ ?>selected="selected"<?php } ?>>--求职最热门地区--</option>
                                <option value='2' <?php if($areainfo['code']==2){ ?>selected="selected"<?php } ?>>--华东、华中地区--</option>
                                <option value='3' <?php if($areainfo['code']==3){ ?>selected="selected"<?php } ?>>--东北、华北地区--</option>
                                <option value='4' <?php if($areainfo['code']==4){ ?>selected="selected"<?php } ?>>--西南、东南地区--</option>
                                <option value='5' <?php if($areainfo['code']==5){ ?>selected="selected"<?php } ?>>--西部、西北地区--</option>
                            </select>
                        </td>
                    </tr>
                    <input type="hidden" name="areaid" value="<?php echo $areainfo['areaid']; ?>" />
                </table>                   
              </form>
            </div>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $.formValidator.initConfig({formid:"info_form",autotip:true});
    $("#name").formValidator({onshow:"请输入栏目名",onfocus:"请输入栏目名"}).inputValidator({min:1,onerror:"请输入栏目名"});
    $('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(result.status == 1){
            $.dialog.get(result.dialog).close();
            $.slglobal.tip({content:result.msg});
            window.location.reload();
        } else {
            $.slglobal.tip({content:result.msg, icon:'alert'});
        }
    }
})
</script>