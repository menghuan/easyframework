<?php include $this->defaulttpldir . '/layouts/header.php'; ?>
<?php include $this->defaulttpldir . '/layouts/left.php'; ?>
<style type="text/css">
    .upload_btn{float: left;color: #333;background: #f1f0f0;border: 1px solid #c4c4c4;border-radius: 2px;text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);display: inline-block;cursor: pointer;width: 60px;height: 30px;line-height: 30px;text-align: center;margin-left: 10px;}
    .attachment_icon {margin-left: 8px;position: relative;}
    .attachment_tip {border: 1px #CCC solid;position: absolute;left: 16px;top: 16px;display: none;padding: 1px;z-index: 9;}
</style>
<div class="right">
    <div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list', '', 'advert'); ?>" >广告管理</a></li>
            <li><a href="<?php echo $this->url('add', '', 'advert'); ?>" class="on"><?php echo (empty($info)) ? "添加" : "编辑";?>广告</a></li>
        </ul>
        <div class="tk_main2">
            <p class="1107-tab-add"><span class="hover">广告信息</span></p>
            <div class="1107-add">
                <form action="<?php echo $this->url('save', '', 'advert'); ?>" method="post" id="info_form">
                    <input type="hidden" id="id" name="id" value="<?php echo $info["id"];?>">
                    <table border="0" cellpadding="0" cellspacing="0" class="1107-table-jbxx">
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告名称：</strong></td>
                            <td>
                                <input type="text" name="name" id="name" value="<?php echo $info["name"];?>" class="-input-text" /><span id="J_nameTip" class="-btx">*</span>
                            </td>
                        </tr> 
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告链接：</strong></td>
                            <td>
                                <input type="text" name="url" id="url" value="<?php echo $info["url"];?>" class="-input-text" /><span id="J_urlTip" class="-btx">*</span>
                            </td>
                        </tr> 
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告位：</strong></td>
                            <td>
                                <select name="boardid" id="boardid" style="margin-left: 10px;height:30px;">
                                    <?php if(empty($adboard)) { ?>
                                    <option value="0">暂无广告位，请先添加广告位</option>
                                    <?php } ?>
                                    <?php foreach ($adboardlist as $alk => $alv) { ?>
                                    <option value="<?php echo $alv['id'];?>"><?php echo $alv['name']."（".$alv['width']."*".$alv['height']."）";?></option>
                                    <?php } ?>
                                </select>
                                <span  class="-btx">*</span>
                                <input type="hidden" id="hid_bid" value="<?php echo (!empty($info["board_id"])) ? $info["board_id"] : 0;?>"
                            </td>
                        </tr> 
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告类型：</strong></td>
                            <td>
                                <select name="type" id="type" style="margin-left: 10px;height:30px;">
                                    <option value="1">图片</option>
                                    <option value="2">文字</option>
                                </select>
                                <input type="hidden" id="hid_tid" value="<?php echo (!empty($info["type"])) ? $info["type"] : 0;?>"
                            </td>
                        </tr> 
                        <tr id="ad_image" class="bill_media" style="display: table-row;">
                            <td width="15%" class="o-table-l"><strong>广告图片 :</strong></td>
                            <td>
                                <input type="text" name="content" id="J_img" class="-input-text" size="30" style="float:left;" value="<?php echo $info["content"];?>" readonly="readonly">
                                <div id="J_upload_img" class="upload_btn" style="position: relative; overflow: hidden; direction: ltr;"><span>上传</span></div>
                                <?php if(!empty($info)){ ?>
                                <span class="attachment_icon J_attachment_icon" file-type="image" file-rel="<?php echo WEB_URL.$info["content"];?>"><div class="attachment_tip" style="display: none;"><img src="<?php echo WEB_URL.$info["content"];?>"></div><img src="<?php echo PUBLIC_URL;?>public/images/image_s.gif"></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告描述 :</strong></td>
                            <td>
                                <input type="text" name="desc" id="desc" value="<?php echo $info["desc"];?>" class="-input-text" />
                            </td>
                        </tr>
                        <tr>
                            <td width="15%" class="o-table-l"><strong>排序 :</strong></td>
                            <td>
                                <input type="text" name="ordid" id="ordid" value="<?php echo ($info["ordid"]!="") ? $info["ordid"] : 0 ;?>" class="-input-text" onkeydown="onlyNum();" maxlength="5"/>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%" class="o-table-l"><strong>是否启用 :</strong></td>
                            <td>
                                <div style="margin-left: 10px;">
                                    <label><input type="radio" name="status" value="1" <?php echo (empty($info) ? "checked='checked'" : ($info['status']==1) ? "checked='checked'" : "");?>> 是</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="status" value="0" <?php echo (empty($info) ? "" : ($info['status']==0) ? "checked='checked'" : "");?>> 否</label>
                                </div>
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
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/Validform_v5.3.2_min.js"></script>
<script language="javascript">
//语言项目
var lang = new Object();
lang.connecting_please_wait = "请稍后...";lang.confirm_title = "提示消息";lang.move = "移动";lang.dialog_title = "消息";lang.dialog_ok = "确定";lang.dialog_cancel = "取消";lang.please_input = "请输入";lang.please_select = "请选择";lang.not_select = "不选择";lang.all = "所有";lang.input_right = "输入正确";lang.plsease_select_rows = "请选择要操作的项目！";lang.plsease_select_mes_tpl = "请选择短信内容模版！";lang.plsease_select_send_pri = "请选择短信优先级！";lang.upload = "上传";lang.uploading = "上传中";lang.upload_type_error = "不允许上传的文件类型！";lang.upload_size_error = "文件大小不能超过{sizeLimit}！";lang.upload_minsize_error = "文件大小不能小于{minSizeLimit}！";lang.upload_empty_error = "文件为空，请重新选择！";lang.upload_nofile_error = "没有选择要上传的文件！";lang.upload_onLeave = "正在上传文件，离开此页将取消上传！";
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
            ele:'input[name="name"]',
            datatype:"*2-18",
            nullmsg:"请输入广告名称",
            errormsg:"账号的范围在2到18个字符之间"
        },
        {
            ele:'input[name="url"]',
            datatype:"url",
            nullmsg:"请输入广告链接",
            errormsg:"请输入正确的链接地址"
        },
        {
            ele:'input[name="boardid"]',
            datatype:"",
            nullmsg:"请选择广告位位置",
            errormsg:"请选择广告位位置"
        }
    ]);
    //上传图片
    var img_uploader = new qq.FileUploaderBasic({
        allowedExtensions: ['jpg','gif','jpeg','png','bmp','pdg'],
        button: document.getElementById('J_upload_img'),
        multiple: false,
        action: "<?php echo WXWEB_URL.TURLS; ?>/advert/uploadfile",
        inputName: 'img',
        forceMultipart: true, //用$_FILES
        messages: {
            typeError: lang.upload_type_error,
            sizeError: lang.upload_size_error,
            minSizeError: lang.upload_minsize_error,
            emptyError: lang.upload_empty_error,
            noFilesError: lang.upload_nofile_error,
            onLeave: lang.upload_onLeave
        },
        showMessage: function(message){
            $.slglobal.tip({content:message, icon:'error'});
        },
        onSubmit: function(id, fileName){
            $('#J_upload_img').addClass('btn_disabled').find('span').text(lang.uploading);
        },
        onComplete: function(id, fileName, result){
            $('#J_upload_img').removeClass('btn_disabled').find('span').text(lang.upload);
            if(result.status == '1'){
                $('#J_img').val(result.data.path);
                $('.attachment_tip img').attr("src","http://www.easyframework.com/"+result.data.path);
            } else {
                $.slglobal.tip({content:result.msg, icon:'error'});
            }
        }
    });
    $("#type").on("change",function(){
       if($("#type").val()==2){
           $("#J_img").attr("name","");
           $("#ad_image").hide();
       }else{
           $("#J_img").attr("name","content");
           $("#ad_image").show();
       }
    });
    var hidbid = $("#hid_bid").val();
    if(hidbid!=0){
        $("#boardid").val(hidbid);
    }
    var hidtid = $("#hid_tid").val();
    if(hidtid!=0){
        $("#type").val(hidtid);
        if(hidtid==2){
            $("#ad_image").hide();
        }
    }
});
function onlyNum(){
 if(!(event.keyCode==46)&&!(event.keyCode==8)&&!(event.keyCode==37)&&!(event.keyCode==39))
  if(!((event.keyCode>=48&&event.keyCode<=57)||(event.keyCode>=96&&event.keyCode<=105)))
    event.returnValue=false;
}
</script>
<?php include $this->defaulttpldir . '/index/footer.php'; ?>
