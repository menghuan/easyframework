/**
 * @name 后台批改显示学员信息操作相关
 * @author wjh
 */
;(function($){
    $.slglobal.user_info = {
		//相关设置
        settings: {
            card_user_btn: '.J_user_card',
            card_user_info_btn: '.J_user_info_card',
            layer_user_html: '<div id="J_card_user_layer" class="user_card"><div id="J_card_user_info"></div><div class="J_card_arrow card_arrow"></div></div>',
            loading_user_html: '<div class="card_info"><p class="card_loading">正在获取学员相关信息。。。</p></div><div class="card_toolbar"></div>'
        },
		//初始化
        init: function(options){
            options && $.extend($.slglobal.user_info.settings, options);
            var s = $.slglobal.user_info.settings;
            //学员信息名片
            $.slglobal.user_info.user_card();
	    //学员信息名片
            $.slglobal.user_info.user_info_card();
        },
                
        //显示学员名片
        user_card: function(){
            var s = $.slglobal.user_info.settings,
                h = null,
                n = null;
            $(s.card_user_btn).live({
                mouseover: function(){
                    clearTimeout(h);
                    clearTimeout(n);
                    //计算显示位置
                    var p = $(this).offset(),
                        l = p.left,
                        d = $(this).width(),
                        q = d / 2 - 8,
                        w = $(window).width();
                        l + 300 > w && (l = l - 300 + d, q = 300 - d / 2 - 8),
                        uid = $(this).attr('data-uid');
                    if(!uid) return !1; //缺少属性
                    //显示加载
                    !$('#J_card_user_layer')[0] && $('body').append(s.layer_user_html);
                    $('#J_card_user_info').html(s.loading_user_html);
                    $('#J_card_user_layer').css({
                        top: p.top - 145 + "px",
                        left: l + "px"
                    });
                    $("#J_card_user_layer .J_card_arrow").css("margin-left", q + "px");
                    h = setTimeout(function(){
                        clearTimeout(h);
                        $("#J_card_user_layer").show();
                    }, 200);
                    $("#J_card_user_layer").hover(
                        function() {
                            clearTimeout(h);
                            $("#J_card_user_layer").show();
                        },
                        function() {
                            $("#J_card_user_layer").hide();
                        }
                    );
                    //获取内容
		    if($('body').data(uid) != void(0) && $('body').data(uid) != ''){
                        $("#J_card_user_info").html($('body').data(uid));
                    }else{
                        n = setTimeout(function(){
                            $.getJSON('index.php?g=admin&m=correct&a=get_user_card', {uid:uid}, function(result){
                                if(result.status == 1){
                                    $("#J_card_user_info").html(result.data);
                                    $("body").data(uid, result.data);
                                    clearTimeout(h);
                                }else{
                                    clearTimeout(h);
                                    clearTimeout(n);
                                    $.slglobal.tip({content:result.msg,  icon:'error'});
                                }
                            });
                        }, 500);
                    }
                },
                mouseout: function(){
                    clearTimeout(h);
                    clearTimeout(n);
                    h = setTimeout(function() {
                        $("#J_card_user_layer").hide();
                    }, 500);
                }
            });
        },
		//显示学员名片
        user_info_card: function(){
            var s = $.slglobal.user_info.settings,
                h = null,
                n = null;
            $(s.card_user_info_btn).live({
                mouseover: function(){
                    clearTimeout(h);
                    clearTimeout(n);
                    //计算显示位置
                    var p = $(this).offset(),
                        l = p.left,
                        d = $(this).width(),
                        q = d / 2 - 8,
                        w = $(window).width();
                        l + 300 > w && (l = l - 300 + d, q = 300 - d / 2 - 8),
                        uid = $(this).attr('data-uid'),
                        utitle = $(this).attr('data-title');
                    if(!uid) return !1; //缺少属性
                    //显示加载
                    !$('#J_card_user_layer')[0] && $('body').append(s.layer_user_html);
                    $('#J_card_user_info').html(s.loading_user_html);
                    $('#J_card_user_layer').css({
                        top: p.top - 145 + "px",
                        left: l + "px"
                    });
                    $("#J_card_user_layer .J_card_arrow").css("margin-left", q + "px");
                    h = setTimeout(function(){
                        clearTimeout(h);
                        $("#J_card_user_layer").show();
                    }, 200);
                    $("#J_card_user_layer").hover(
                        function() {
                            clearTimeout(h);
                            $("#J_card_user_layer").show();
                        },
                        function() {
                            $("#J_card_user_layer").hide();
                        }
                    );
                    //获取内容
                    if($('body').data(uid) != void(0) && $('body').data(uid) != ''){
                        $("#J_card_user_info").html($('body').data(uid));
                    }else{
                        n = setTimeout(function(){
                            if(utitle){
                                $("#J_card_user_info").html(utitle);
                                $("body").data(uid, utitle);
                                clearTimeout(h);
                            }else{
                                clearTimeout(h);
                                clearTimeout(n);
                                $.slglobal.tip({content:"暂无信息",  icon:'error'});
                            }
                        }, 500);
                    }
                },
                mouseout: function(){
                    clearTimeout(h);
                    clearTimeout(n);
                    h = setTimeout(function() {
                        $("#J_card_user_layer").hide();
                    }, 500);
                }
            });
        }
    };
    $.slglobal.user_info.init(); //学员
})(jQuery);