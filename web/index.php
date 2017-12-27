<?php
/*
    [UCenter] (C)2001-2099 Comsenz Inc.
    This is NOT a freeware, use is subject to license terms
    $Id: index.php 1059 2015-03-01 07:25:09Z$
*/
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ERROR);
//error_reporting(1);//输出错误信息
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('display_errors',1);
define('UC_ROOT', dirname(__FILE__).'/');
ini_set('error_log', './log/sys_error_' . date('Y-m-dH') . '.txt');
if($_GET['xhprof']==1)
{
    xhprof_enable();
}
ini_set('default_socket_timeout', -1);
define('DEBUG_ENABLE',false);//调试mysql错误栈
define('PAGESIZE', 20);//定义一页显示数据个数
//路径可以修改为自动获取
define( 'ROOT_PATH', realpath(dirname(__FILE__)));
define('ROOT_URL',"http://" . $_SERVER['HTTP_HOST']);
define('PIC_URL',"http://www.esayframwork.com:8028/");
define('LOGIN_URL',"http://www.esayframwork.com:8028/");
define('PUBLIC_URL',"http://www.esayframwork.com:8028/");
define('WXWEB_UP','uploads/');
define('MRSOLR_PORT','http://'.$_SERVER['HTTP_HOST'] .':8070/');
define('SESSIONTIME',8640000);
define('IN_UC', TRUE);
define('UC_ROOT', dirname(__FILE__).'/');
define('UC_API', strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'))));
define('UC_DATADIR', UC_ROOT.'data/');
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
//定义是否是ajax提交的获取 2014-10-4 18:00 王江华
define('IS_AJAX',((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false);
define('TURLS','/');
//头像路径
define( 'ROOT_UPLOAD', PUBLIC_URL.'uploads');
require_once UC_ROOT.'./command/function.php';
$lifeTime = 24 * 360000;
session_set_cookie_params($lifeTime);
set_magic_quotes_runtime(0);
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
$handler = new RedisSessionHandler();
session_set_save_handler(
    array($handler, 'open'), //在运行session_start()时执行
    array($handler, 'close'),//在脚本执行完成或调用session_write_close() 或 session_destroy()时被执行,即在所有session操作完后被执行
    array($handler, 'read'),//在运行session_start()时执行,因为在session_start时,会去read当前session数据
    array($handler, 'write'),//此方法在脚本结束和使用session_write_close()强制提交SESSION数据时执行
    array($handler, 'destroy'),//在运行session_destroy()时执行
    array($handler, 'gc')  //执行概率由session.gc_probability 和 session.gc_divisor的值决定,时机是在open,read之后,session_start会相继执行open,read和gc
);
session_start(); //这也是必须的，打开session，必须在session_set_save_handler后面执行
register_shutdown_function('session_write_close');

unset($GLOBALS, $_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);


$_GET		= daddslashes($_GET, 1, TRUE);
$_POST		= daddslashes($_POST, 1, TRUE);
$_COOKIE	= daddslashes($_COOKIE, 1, TRUE);
$_SERVER	= daddslashes($_SERVER);
$_FILES		= daddslashes($_FILES);
$_REQUEST	= daddslashes($_REQUEST, 1, TRUE);

require_once UC_ROOT.'./release/release.php';
require_once UC_ROOT.'./command/Cache.php';
if(!@include UC_DATADIR.'config.inc.php') {
	exit('The file <b>data/config.inc.php</b> does not exist, perhaps because of UCenter has not been installed, <a href="install/index.php"><b>Please click here to install it.</b></a>.');
}

//echo $_SERVER["REQUEST_URI"];
$uri=strpos($_SERVER["QUERY_STRING"],'?');
if($uri)
    $uristr = trim(substr($_SERVER["QUERY_STRING"],0, $uri ),'/');
else
    $uristr = str_replace('_route_=','',trim($_SERVER["QUERY_STRING"],'/'));
//url带?的话 通过$_SERVER["QUERY_STRING"] 解析
if($uri)
    $uriarray=explode('/', ltrim ($_SERVER["QUERY_STRING"],'/'));
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
if(strpos($a,'&')){
    $uriarray3 = explode('&', $a);
    $a = $uriarray3[0];
}
define('RELEASE_ROOT', '');
if(file_exists(UC_ROOT.RELEASE_ROOT.'model/base.php')) {
	require_once UC_ROOT.RELEASE_ROOT.'model/base.php';
        require_once UC_ROOT.RELEASE_ROOT.'model/basem.php';
} else {
	require_once UC_ROOT.'model/base.php';
        require_once UC_ROOT.'model/basem.php';
}
$errorhtml = '<link href="/public/css/style.css" rel="stylesheet" type="text/css" /><div class="zg_404"><div class="zg404">出错了，该链接不存在或你没有访问该链接的权限！<a href="http://www.easyframework.com">回首页</a></div></div>';
if(in_array($m, array(
    'home','user','foreuser','resume','jobsearch','jobs','templates','userforcompany','delivery','company','zixun','zhibo','certificate','stationresume','evaluate'
   ))) {
	if(file_exists(UC_ROOT.RELEASE_ROOT."control/$m.php")) {
        require_once  UC_ROOT.RELEASE_ROOT."control/$m.php";
	} else {
	    require_once  UC_ROOT."control/$m.php";
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
            exit($errorhtml);
	}
} else {
    header('Content-type:text/html;charset=utf-8');
    exit($errorhtml);
}
$mtime = explode(' ', microtime());
$endtime = $mtime[1] + $mtime[0];

function daddslashes($string, $force = 0, $strip = FALSE) {
    return $string;
    if(!MAGIC_QUOTES_GPC || $force) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
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

class grequest{//为了迎合yii之前的代码
    public function getParam($name,$defaultValue=null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
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

function init_cache() {
    static $cache;
    if(!$cache){
        $cache = getRedis();
    }
    return $cache;
}


//初始化solr服务
function init_solrs($cores = 'companys'){
    if(!$solrs){
        require_once UC_ROOT.'lib/Solrclient.class.php';
        $solrs = new Solrclient('',$cores);
    }
    return $solrs;
}

//redis操作封装
class RedisSessionHandler
{
    function open($savePath, $sessionName)
    {
        return true;
    }

    function close()
    {
        return true;
    }
    function read($id)
    {
         $r = init_cache();
         $r->select(9);
         return $r->get('sess_'.$id);
    }
    function write($id,$data)
    {
         $r = init_cache();
         $r->select(9);
         return $r->setex('sess_'.$id,SESSIONTIME,$data);
    }

    function destroy($id)
    {
	 $r = init_cache();
         $r->select(9);
         return $r->del('sess_'.$id);
    }

    function gc($maxlifetime)
    {
        return true;
    }

}

function mosaic($arr,$urlarr){
    if(count($arr)>1){
        $arrs = implode("_",array($arr[1],$arr[2]));
        if(in_array($arrs,$urlarr)){
            return $arrs;
        }else{
            return $arr[1];
        }
    }else{
        return false;
    }
}

if($_GET['xhprof']==1)
{
    $xhprof_data = xhprof_disable();
    include_once "xhprof/xhprof_lib/utils/xhprof_lib.php";
    include_once "xhprof/xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default();

    //save the run under a namespace "xhprof_foo"
    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
    echo "<script>window.open('/xhprof/xhprof_html/index.php?run=".$run_id."&source=xhprof_foo')</script>";
}

?>
