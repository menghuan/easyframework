/*
 * @author wjh
 * @time 2014-07-20
 * @version 1.0
 */
var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
var is_safari = (userAgent.indexOf('webkit') != -1 || userAgent.indexOf('safari') != -1);
var attachexts = new Array();
var extensions = 'pdf,docx,doc,ppt,pptx,xls,xlsx,rar,zip,dps,txt';
var aid = 0;

function $$$(id) {
    return document.getElementById(id);
}

//获取后缀名
function getExt(path) {
    return path.lastIndexOf('.') == -1 ? '' : path.substr(path.lastIndexOf('.') + 1, path.length).toLowerCase();
}

//删除一个
function delAttach(id) {
	$$$('attachbody').removeChild($$$('attach_' + id).parentNode.parentNode);
	if($$$('attachbody').innerHTML == '') {
            addAttach();
	}
}

//添加一个
function addAttach() {
	newnode = $$$('attachbodyhidden').rows[0].cloneNode(true);
	var id = aid;
	var tags;
	tags = newnode.getElementsByTagName('input');
	for(i in tags) {
            if(tags[i].name == 'attach[]') {
                tags[i].id = 'attach_' + id;
                tags[i].name = 'attach[]';
                tags[i].onchange = function() {insertAttach(id)};
                tags[i].unselectable = 'on';
            }
	}
	tags = newnode.getElementsByTagName('span');
	for(i in tags) {
            if(tags[i].id == 'localfile') {
                tags[i].id = 'localfile_' + id;
            }
	}
	aid++;
            $$$('attachbody').appendChild(newnode);
        
}

addAttach();

//插入一个 先拼凑内容再插入到页面
function insertAttach(id) {
	var path = $$$('attach_' + id).value;
	var ext = getExt(path);
	var re = new RegExp("(^|\\s|,)" + ext + "($|\\s|,)", "ig");
	var localfile = $$$('attach_' + id).value.substr($$$('attach_' + id).value.replace(/\\/g, '/').lastIndexOf('/') + 1);

	if(path == '') {
            return;
	}
	if(extensions != '' && (re.exec(extensions) == null || ext == '')) {
            alert('对不起，不支持上传此类扩展名的文件');
            return;
	}
	attachexts[id] = inArray(ext, ['gif', 'jpg', 'jpeg', 'png' , 'wav' , 'pdf' , 'docx' , 'doc' , 'ppt' , 'pptx' , 'xls' , 'xlsx' , 'rar' , 'zip' , 'dps' , 'txt']) ? 2 : 1;
	var inhtml = '<div class="borderbox"><table cellspacing="0" cellpadding="0" border="0"><tr>';
	if(is_ie || userAgent.indexOf('firefox') >= 1) {
		var picPath = getPath($$$('attach_' + id));
		var imgCache = new Image();
		imgCache.src = picPath;
		inhtml += '<td><img src="' + picPath +'" width="60" height="80">&nbsp;</td>';
	}
	localfile += '&nbsp;<span id="showmsg' + id + '"><a href="javascript:;" onclick="delAttach(' + id + ')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">[删除]</font></a></span>';
	inhtml += '<td><font color="blue">' + localfile +'</font><br/>';
	inhtml += '</td></tr></table></div>';
	
	$$$('localfile_' + id).innerHTML = inhtml;
	$$$('attach_' + id).style.display = 'none';
        
	addAttach();
}

function getPath(obj){
    
    if (obj) {
        if (is_ie) {
            obj.select();
            obj.blur();
            // IE下取得附件的本地路径
            if(document.selection){
                return document.selection.createRange().text;
            }else{
                return false;
            }
        } else if(is_moz) {
            
            if (obj.files) {
                // Firefox下取得的是附件的数据
                return window.URL.createObjectURL(obj.files[0]);//obj.files.item(0).getAsDataURL();
            }
            return obj.value;
        }
        return obj.value;
    }
}
function inArray(needle, haystack) {
    if(typeof needle == 'string') {
        for(var i in haystack) {
            if(haystack[i] == needle) {
                return true;
            }
        }
    }
    return false;
}
