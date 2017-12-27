/**
 * **********************后台操作JS************************
 * ajax 状态显示
 * confirmurl 操作询问
 * showdialog 弹窗表单
 * attachment_icon 附件预览效果
 * preview 预览图片大图
 * cate_select 多级菜单动态加载
 * author wjh
 */
;$(function($){

	//确认操作
	$('.J_confirmurl').live('click', function(){
		var self = $(this),
                uri = self.attr('data-uri'),
                acttype = self.attr('data-acttype'),
                title = (self.attr('data-title') != undefined) ? self.attr('data-title') : '提示消息',
                msg = self.attr('data-msg'),
                did = self.attr('data-id'),
                dtype = self.attr('data-type'),
                trlen = $(".right_tab tr:visible").length - 1; //获取显示的tr个数 然后减去第一行标题
		$.dialog({
                    title:title,
                    content:msg,
                    padding:'10px 20px',
                    lock:true,
                    ok:function(){
                        if(acttype == 'ajax'){
                            $.getJSON(uri, function(result){
                                if(result.status == 1){
                                    $.slglobal.tip({content:result.msg});
                                    if(dtype == 1){ //删除操作
                                        $("#tr_"+did).fadeOut('slow');
                                    }else if(dtype == 2){ //评价课程操作
                                        if(result.data == 1){ //审核不通过
                                            $("#verify_"+did).html("<font color='red'>驳回</font>"); 
                                        }else if(result.data == 2){ //审核通过
                                            $("#verify_"+did).html("<font color='green'>通过</font>");  
                                        }else{
                                            $("#verify_"+did).html("<font color='blue'>未审核</font>");  
                                        }
                                    }else if(dtype == 3){
                                        $("#tr_"+did).remove();
                                        moves();
                                    }else if(dtype == 4){
                                        $("#tr_"+did+" td").eq(3).html(result.data.names);
                                    }else if(dtype == 5){
                                        $("#tr_"+did+" td").eq(result.data.num).html(result.data.name);
                                    }else{
                                        window.location.reload();
                                    }
                                    if(trlen - 1 == 0){ //当页面数据被处理完毕的时候重新刷新页面
                                        window.location.reload();
                                    }
                                }else{
                                    $.slglobal.tip({content:result.msg, icon:'error'},5000);
                                }
                            });
                        }else{
                           location.href = uri;
                        }
                    },
                    cancel:function(){}
		});
	});
	
	//弹窗表单
	$('.J_showdialog').live('click', function(){
		var self = $(this),
                dtitle = self.attr('data-title'),
                did = self.attr('data-id'),
                sid=self.attr('data-sid');
                uid=self.attr('data-uid');
                duri = self.attr('data-uri'),
                dwidth = parseInt(self.attr('data-width')),
                dheight = parseInt(self.attr('data-height')),
                acttype = self.attr('data-acttype'),
                dtype = self.attr('data-type'),
                dpadding = (self.attr('data-padding') != undefined) ? self.attr('data-padding') : '';
		$.dialog({id:did}).close();
		$.dialog({
                    id:did,
                    title:dtitle,
                    width:dwidth ? dwidth : 'auto',
                    height:dheight ? dheight : 'auto',
                    padding:dpadding,
                    lock:true,
                    ok:function(){
                        var info_form = this.dom.content.find('#info_form');
                        if(info_form[0] != undefined){
                            if(acttype == "ajax"){
                                var info_form_action = this.dom.content.find('#info_form').attr('action'); //获取form的action
                                $.ajax({
                                    type : 'POST',
                                    cache : false,
                                    dataType : 'JSON',
                                    url  : info_form_action,
                                    data : $('#info_form').serialize(), //form表单序列化
                                    success:function(result){ //用ajax方法发送信息到当前Action中的main方法                                    
                                        if(result.status == 1){
                                            $.slglobal.tip({content:result.msg, icon:'success' ,time:2000});
                                            if(dtype == 1){ //提升管理员
                                                $("#isma_"+did).html("<font color='green'>管理员</font>"); 
                                                $("#ismap_"+did).html("<font color='green'>"+result.data+"</font>");
                                            }else if(dtype == 2){
                                                $("#isma_"+did).html("<font color='green'>超级管理员</font>"); 
                                                $("#ismap_"+did).html("<font color='green'>"+result.data+"</font>");                                                
                                            }else if(dtype == 5){
                                                $("#tr_"+did+" td").eq(result.data.num).html(result.data.name);
                                                if(result.data.num1){
                                                    $("#tr_"+did+" td").eq(result.data.num1).html(result.data.area);
                                                }                                                
                                            }
                                   
                                            $.dialog({id:did}).close();
                  
                                        }else{
                                            $.dialog({id:did}).close();
                                            $.slglobal.tip({content:result.msg, icon:'error' ,time:3000});
                                        }
                                    }
                                });
                            }else{
                                info_form.submit();
                            }
                            return false;
                        }
                    },
                    cancel:function(){}
		});
		$.getJSON(duri, function(result){
                    if(result.status == 1){
                        $.dialog.get(did).content(result.data);
                        return false;
                    }else{
                        $.dialog({id:did}).close();
                        $.slglobal.tip({content:result.msg, icon:'error'});
                    }
		});
		return false;
	});
	
	//附件预览
	$('.J_attachment_icon').live('mouseover', function(){
		var ftype = $(this).attr('file-type');
		var rel = $(this).attr('file-rel');
		switch(ftype){
                    case 'image':
                        if(!$(this).find('.attachment_tip')[0]){
                                $('<div class="attachment_tip"><img src="'+rel+'" /></div>').prependTo($(this)).fadeIn();
                        }else{
                                $(this).find('.attachment_tip').fadeIn();
                        }
                        break;
		}
	}).live('mouseout', function(){
		$('.attachment_tip').hide();
	});
	
	$('.J_attachment_icons').live('mouseover', function(){
		var ftype = $(this).attr('file-type');
		var rel = $(this).attr('file-rel');
		switch(ftype){
                    case 'image':
                        if(!$(this).find('.attachment_tip')[0]){
                                $('<div class="attachment_tip" style="width:160px; height:80px;"><img width="160" height="80" src="'+rel+'" /></div>').prependTo($(this)).fadeIn();
                        }else{
                                $(this).find('.attachment_tip').fadeIn();
                        }
                        break;
		}
	}).live('mouseout', function(){
		$('.attachment_tip').hide();
	});
        
        
        //全部操作
        $('a[data-tdtype="all_action"]').live('click', function() {
                var btn = this;
                var ids = '';
                var uri = $(btn).attr('data-uri'),
                msg = $(btn).attr('data-msg'),
                acttype = $(btn).attr('data-acttype'),
                title = ($(btn).attr('data-title') != undefined) ? $(this).attr('data-title') : '提示信息';
                if(msg != undefined){
                    $.dialog({
                        id:'confirm',
                        title:title,
                        width:360,
                        padding:'10px 20px',
                        lock:true,
                        content:msg,
                        ok:function(){
                                action();
                        },
                        cancel:function(){}
                    });
                }else{
                    action();
                }
                function action(){
                        if(acttype == 'ajax_form'){
                            var did = $(btn).attr('data-id'),
                            dwidth = parseInt($(btn).attr('data-width')),
                            dheight = parseInt($(btn).attr('data-height'));
                            $.dialog({
                                id:did,
                                title:title,
                                width:dwidth ? dwidth : 'auto',
                                height:dheight ? dheight : 'auto',
                                padding:'',
                                lock:true,
                                ok:function(){
                                    var info_form = this.dom.content.find('#info_form');
                                    if(info_form[0] != undefined){
                                        info_form.submit();
                                        return false;
                                    }
                                },
                                cancel:function(){}
                            });
                            $.getJSON(uri, function(result){
                                if(result.status == 1){
                                    $.dialog.get(did).content(result.data);
                                }else{
                                    $.dialog({id:did}).close();
                                    $.slglobal.tip({content:result.msg, icon:'error'});
                                }
                            });
                        }else if(acttype == 'ajax'){
                            $.getJSON(uri, function(result){
                                if(result.status == 1){
                                    $.slglobal.tip({content:result.msg});
                                    window.location.reload();
                                }else{
                                    $.slglobal.tip({content:result.msg, icon:'error'});
                                }
                            });
                        }else{
                            location.href = uri;
                        }
                }
        });
        
        
        //批量操作
       $('input[data-tdtype="batch_action"]').live('click', function() {
            var btn = this;
            if($('.J_checkitem:checked').length == 0){
                $.slglobal.tip({content:'请先选择后再操作', icon:'alert'});
                return false;
            }
            var ids = '';
            $('.J_checkitem:checked').each(function(){
                    ids += $(this).val() + ',';
            });
            ids = ids.substr(0, (ids.length - 1));
            var uri = $(btn).attr('data-uri') + '/' + $(btn).attr('data-name') + '/' + ids,
            msg = $(btn).attr('data-msg'),
            acttype = $(btn).attr('data-acttype'),
            title = ($(btn).attr('data-title') != undefined) ? $(this).attr('data-title') : '提示信息';
            if(msg != undefined){
                $.dialog({
                    id:'confirm',
                    title:title,
                    width:300,
                    padding:'10px 20px',
                    lock:true,
                    content:msg,
                    ok:function(){
                       action();
                    },
                    cancel:function(){}
                });
            }else{
                action();
            }
            function action(){
                    if(acttype == 'ajax_form'){
                            var did = $(btn).attr('data-id'),
                            dwidth = parseInt($(btn).attr('data-width')),
                            dheight = parseInt($(btn).attr('data-height'));
                            $.dialog({
                                id:did,
                                title:title,
                                width:dwidth ? dwidth : 'auto',
                                height:dheight ? dheight : 'auto',
                                padding:'',
                                lock:true,
                                ok:function(){
                                    var info_form = this.dom.content.find('#info_form');
                                    if(info_form[0] != undefined){
                                        info_form.submit();
                                        return false;
                                    }
                                },
                                cancel:function(){}
                            });
                            $.getJSON(uri, function(result){
                                if(result.status == 1){
                                    $.dialog.get(did).content(result.data);
                                }else{
                                    $.dialog({id:did}).close();
                                    $.slglobal.tip({content:result.msg, icon:'error'});
                                }
                            });
                    }else if(acttype == 'ajax'){
                            $.getJSON(uri, function(result){
                                if(result.status == 1){
                                    $.slglobal.tip({content:result.msg});
                                    window.location.reload();
                                }else{
                                    $.slglobal.tip({content:result.msg, icon:'error'});
                                }
                            });
                    }else{
                            location.href = uri;
                    }
            }
    });
});