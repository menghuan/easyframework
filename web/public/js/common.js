/**
 * [common func]
 * @author mingjian
 * @time 2017-04-27
 */
function isEmpty(value){
    if(value == null || value == "" || value == "undefined" || value == undefined || value == "null"){
        return true;
    }else{
        value = (value+"").replace(/\s/g,'');
        if(value == ""){
           return true;
        }
        return false;
    }
}

function isPhone(str){
	if(str == '' || str.length != 11){
		return false;
	}
	var reg = /^1[3578][0123456789]\d{8}$/;
	if (!reg.test(str)) {
		return false;
	} else {
		return true;
	}
}

function isEmail(str){
	if(str == ''){
		return false;
	}
	var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if (!reg.test(str)) {
		return false;
	} else {
		return true;
	}
}