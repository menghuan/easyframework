<?php
/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: index.php 1059 2011-03-01 07:25:09Z monkey $
*/
session_start();
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('error_log', '/var/log/easyframework/admin/log/'.date('Y-m-dH').'.log');
if(@$_GET['xhprof']==1)
{
    xhprof_enable();
}

define('DEBUG_ENABLE',true);//调试mysql错误栈
define('REIS_YQK',true);//是否连接题库
define('PAGESIZE', 3);
define('ROOT_URL',"http://" . $_SERVER['HTTP_HOST'] . '/index.php');
define('PUBLIC_URL',"http://" . $_SERVER['HTTP_HOST'].'/');
define('MRSOLR_PORT','http://'.$_SERVER['HTTP_HOST'] .':8070/');
define('SESSIONTIME',86400);
define('IN_UC', TRUE);
define('UC_ROOT', dirname(__FILE__).'/');
define('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
define('WXWEB_URL',"http://www.easyframework.com:8099");
define('WEB_URL',"http://www.easyframework.com/");
define('WXWEB_UP','/uploads');
define('LOGIN_URL',"www.easyframework.com/");
define('UC_API', strtolower('http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'))));
define('UC_DATADIR', UC_ROOT.'data/');
define('UC_DATAURL', UC_API.'/data');
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
$globe_exam=array();
//定义是否是ajax提交的获取 20103-10-4 18:00 王江华 
define('IS_AJAX',((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false);
define('TURLS','/index.php');

$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
unset($GLOBALS, $_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);

$_GET		= daddslashes($_GET, 1, TRUE);
$_POST		= daddslashes($_POST, 1, TRUE);
$_COOKIE	= daddslashes($_COOKIE, 1, TRUE);
$_SERVER	= daddslashes($_SERVER);
$_FILES		= daddslashes($_FILES);
$_REQUEST	= daddslashes($_REQUEST, 1, TRUE);

require_once UC_ROOT.'./release/release.php';
require_once UC_ROOT.'./command/function.php';
require_once UC_ROOT.'./command/Cache.php';
if(!@include UC_DATADIR.'config.inc.php') {
	exit('The file <b>data/config.inc.php</b> does not exist, perhaps because of UCenter has not been installed, <a href="install/index.php"><b>Please click here to install it.</b></a>.');
}


$uri=strpos($_SERVER["REQUEST_URI"],'?');
if($uri)
    $uristr=trim(substr($_SERVER["REQUEST_URI"],0, $uri ),'/');
else
    $uristr=trim($_SERVER["REQUEST_URI"],'/');  
unset($_GET);  
//url带?的话 通过$_SERVER["QUERY_STRING"] 解析
if($uri)
    $uriarray=explode('/', ltrim($_SERVER["REQUEST_URI"],'/'));
else
    $uriarray=explode('/', $uristr);
if($uriarray[0]){
    $_GET['m']=$_REQUEST['m']=$uriarray[1];
    if(strpos($uriarray[2], '?')){
        $uriarray2 = explode('?', $uriarray[2]);
        $_GET['a']=$_REQUEST['a']=$uriarray2[0];
    }else{
        $_GET['a']=$_REQUEST['a']=$uriarray[2];
    }
    $uriarraycount=count($uriarray);
    for ($i=3;$i<$uriarraycount;$i++){
        $_GET[$uriarray[$i]]=$_REQUEST[$uriarray[$i]]=$uriarray[++$i];
    }  
}

$m = getgpc('m');
$a = getgpc('a');
$m = empty($m) ? 'index' : $m;
$a = empty($a) ? 'login' : $a;
define('RELEASE_ROOT', '');
          
if(file_exists(UC_ROOT.RELEASE_ROOT.'model/base.php')) {
        
	require_once UC_ROOT.RELEASE_ROOT.'model/base.php';
} else {
	require_once UC_ROOT.'model/base.php';
}
    
if(in_array($m, array(
   'index','admin','area','user','seccodec','role','typejob','basicdata','article','category','resumetemplate','adboard','advert','jobs','certificate','company','resume','stationresume','liveactive'
   ))) {                
	if(file_exists(UC_ROOT.RELEASE_ROOT."control/$m.php")) {
            require_once  UC_ROOT.RELEASE_ROOT."control/$m.php";
	} else {
            require_once UC_ROOT."control/$m.php";
	}
        $classname = $m;     
	$control = new $classname();
        $control->init($classname);
	$method = 'action'.$a;
	if(method_exists($control, $method) && $a{0} != '_') {
		$data = $control->$method();
	} elseif(method_exists($control, '_call')) {
		$data = $control->_call('on'.$a, '');
	} else {
		exit('Action not found!');
	}
} else {
    exit('Module not found!');
}

$mtime = explode(' ', microtime());
$endtime = $mtime[1] + $mtime[0];

function daddslashes($string, $force = 0, $strip = FALSE) {
    if(!MAGIC_QUOTES_GPC || $force) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                    $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

function getgpc($k=false, $var='R') {
   
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
        if(!$k)
            return $var;
	return isset($var[$k]) ? $var[$k] : NULL;
}
class grequest{  //为了迎合yii之前的代码
        public function getParam($name,$defaultValue=null)
	{
		return isset($_REQUEST[$name]) ? $_REQUEST[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
	}
}
function init_db() {
    static $db;
    if(!$db){
		require_once UC_ROOT.'lib/db.class.php';
		$db = new ucserver_db();
        $db->connect(UC_DBHOST, UC_DBUSER, UC_DBPW, UC_DBNAME, UC_DBCHARSET, UC_DBCONNECT, UC_DBTABLEPRE);    
    }
    return $db;
}

//初始化solr服务
function init_solrs($cores = 'companys'){
    if(!$solrs){
        require_once UC_ROOT.'lib/Solrclient.class.php';
        $solrs = new Solrclient('',$cores);
    }
    return $solrs;
}
?>
