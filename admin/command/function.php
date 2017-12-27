<?php 
/**
  * A useful one that I use in development is the following
  * which dumps the target with syntax highlighting on by default
  */
 // 浏览器友好的变量输出
function dump($var, $echo=true, $label=null, $strict=true) {
            $label = ($label === null) ? '' : rtrim($label) . ' ';
            if (!$strict) {
                if (ini_get('html_errors')) {
                    $output = print_r($var, true);
                    $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
                } else {
                    $output = $label . print_r($var, true);
                }
            } else {
                ob_start();
                var_dump($var);
                $output = ob_get_clean();
                if (!extension_loaded('xdebug')) {
                    $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
                    $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
                }
            }
            if ($echo) {
                echo($output);
                return null;
            }else
                return $output;
}


/**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     * 定义是否是ajax提交的获取 20103-09-14 18:00 wjh 
     */
function ajaxReturn($data,$type='') {
        if(func_num_args()>2) {  // 兼容3.0之前用法
            $args           =   func_get_args();
            array_shift($args);
            $info           =   array();
            $info['data']   =   $data;
            $info['info']   =   array_shift($args);
            $info['status'] =   array_shift($args);
            $data           =   $info;
            $type           =   $args?array_shift($args):'';
        }
        if(empty($type)) $type  =  'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';
                exit($handler.'('.json_encode($data).');');  
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);            
            default     :
                // 用于扩展其他返回格式数据
                exit();
        }
}


/**
     * AJAX返回数据标准  公共方法
     *
     * @param int $status
     * @param string $msg
     * @param mixed $data
     * @param string $dialog 弹窗使用 指定是哪个弹窗 比如 编辑edit 添加add等
     * 定义是否是ajax提交的获取 20103-09-14 18:00 wjh 
     */
function ajaxReturns($status=1, $msg='', $data='', $dialog='') {
        ajaxReturn(array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog,
        ));
}


/**
    +----------------------------------------------------------
    * 字符串截取，支持中文和其它编码
    +----------------------------------------------------------
    * @static
    * @access public
    +----------------------------------------------------------
    * @param string $str 需要转换的字符串
    * @param string $start 开始位置
    * @param string $length 截取长度
    * @param string $charset 编码格式
    * @param string $suffix 截断显示字符
    +----------------------------------------------------------
    * @return string
    +----------------------------------------------------------
    */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
        if(function_exists("mb_substr"))
                return mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
                return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) return $slice."…";
        return $slice;
}


/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
        }

        //
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
}

/**
 * 获取客户端浏览器
 * @return string
 */
function getBrower(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {  
                $browser = 'Maxthon'; 
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {  
                $browser = 'IE12.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {  
                $browser = 'IE11.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {  
                $browser = 'IE10.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {  
                $browser = 'IE9.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {  
                $browser = 'IE8.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {  
                $browser = 'IE7.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {  
                $browser = 'IE6.0';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {  
                $browser = 'NetCaptor';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {  
                $browser = 'Netscape';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {  
                $browser = 'Lynx';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {  
                $browser = 'Opera';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {  
                $browser = 'Google';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {  
                $browser = 'Firefox';  
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {  
                $browser = 'Safari'; 
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iphone') || strpos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {  
                $browser = 'iphone';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {  
                $browser = 'iphone';
        } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {  
                $browser = 'android';
        } else {  
                $browser = 'other';  
        }
        return $browser;
}


//创建目录
function  CreateFolder( $path ){
      if  (! file_exists ( $path )){
             CreateFolder(dirname( $path ));
             mkdir ( $path , 0777)  or   die ( '目录权限不够， 无法建立文件夹' );
      }
} 
/**
* 转换字节数为其他单位0
* @param	string	$filesize	字节大小
* @return	string	返回大小
*/
 function sizecount($filesize) {
        if ($filesize >= 1073741824) {
                $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
        } elseif ($filesize >= 1048576) {
                $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
        } elseif($filesize >= 1024) {
                $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
        } else {
                $filesize = $filesize.' Bytes';
        }
        return $filesize;
}


/*
 * 把数组按，给出的索引排序
 */
function ArrData($array,$id) {
        if(is_array($array)){
            foreach($array as $k=>$v){
                $data[$v[$id]] = $v;
            }
            return $data;
        }else{
            return false;
        }
}

//生成一个缩略图
function gethumb($pic="",$x=0,$y=0,$name="sm_"){

        $paths = WXWEB_URL.$pic;//图片路径
        
        $picinfo = getimagesize($paths);//图片详情
        
        $picpath = explode(".",$pic);//分割图片路径
        
        $type = $picpath[count($picpath)-1];//获得最后的名称
        if($type != 'jpg'){
            return "";
        }
        $lastname = $name . time() . rand(1000,9999);
        $savepath = WXWEB_PATH . WXWEB_UP . "/thumb/" . $lastname . '.' . $type;//保存的缩略图路径
        $datapath = WXWEB_UP . "/thumb/" . $lastname . '.' . $type;//保存的缩略图路径
        //缩略图1
        $imgsrc = imagecreatefromjpeg($paths);
        $image1 = imagecreatetruecolor($x, $y);  //创建一个彩色的底图
        if($picinfo[0]<$x||$picinfo[1]<$y){
            imagecopyresampled($image1, $imgsrc, 0, 0, 0, 0,$picinfo[0],$picinfo[1],$picinfo[0],$picinfo[1]);
        }else{
            imagecopyresampled($image1, $imgsrc, 0, 0, 0, 0,$x,$y,$picinfo[0],$picinfo[1]);
        }
        imagejpeg($image1,$savepath);
        imagedestroy($image1);  
        return $datapath;
}


/**
 * 缓存管理
 * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function S($name,$value='',$options=null) {
    static $cache   =   '';
    if(is_array($options)){
        // 缓存操作的同时初始化
        $type       =   isset($options['type'])?$options['type']:UC_CACHE_TYPE;
        require_once UC_ROOT.'command/cache/driver/'.$type.'.class.php';
        $cache      =   Cache::getInstance($type,$options);
        $cache->select('10');
    }elseif(is_array($name)) { // 缓存初始化
        $type       =   isset($name['type'])?$name['type']:UC_CACHE_TYPE;
        require_once UC_ROOT.'command/cache/driver/'.$type.'.class.php';
        $cache      =   Cache::getInstance($type,$name);
        $cache->select('10');
        return $cache;
    }elseif(empty($cache)) { // 自动初始化
        $type       =   isset($options['type'])?$options['type']:UC_CACHE_TYPE;
        require_once UC_ROOT.'command/cache/driver/'.$type.'.class.php';
        $cache      =   Cache::getInstance();
        $cache->select('10');
    }
    if(''=== $value){ // 获取缓存
        return $cache->get($name);
    }elseif(is_null($value)) { // 删除缓存
        return $cache->rm($name);
    }else { // 缓存数据
        if(is_array($options)) {
            $expire     =   isset($options['expire'])?$options['expire']:NULL;
        }else{
            $expire     =   is_numeric($options)?$options:NULL;
        }
        return $cache->set($name, $value, $expire);
    }
}

/**
 * 设置和获取统计数据
 * 使用方法:
 * <code>
 * N('db',1); // 记录数据库操作次数
 * N('read',1); // 记录读取次数
 * echo N('db'); // 获取当前页面数据库的所有操作次数
 * echo N('read'); // 获取当前页面读取次数
 * </code>
 * @param string $key 标识位置
 * @param integer $step 步进值
 * @param boolean $save 是否保存结果
 * @return mixed
 */
function N($key, $step=0,$save=false) {
    static $_num    = array();
    if (!isset($_num[$key])) {
        $_num[$key] = (false !== $save)? S('N_'.$key) :  0;
    }
    if (empty($step)){
        return $_num[$key];
    }else{
        $_num[$key] = $_num[$key] + (int)$step;
    }
    if(false !== $save){ // 保存结果
        S('N_'.$key,$_num[$key],$save);
    }
    return null;
}
/**
 * 根据PHP各种类型变量生成唯一标识号
 * @param mixed $mix 变量
 * @return string
 */
function to_guid_string($mix) {
    if (is_object($mix)) {
        return spl_object_hash($mix);
    } elseif (is_resource($mix)) {
        $mix = get_resource_type($mix) . strval($mix);
    } else {
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * 优化的require_once
 * @param string $filename 文件地址
 * @return boolean
 */
function require_cache($filename) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if (file_exists_case($filename)) {
            require $filename;
            $_importFiles[$filename] = true;
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}


/**
 * 区分大小写的文件存在判断
 * @param string $filename 文件地址
 * @return boolean
 */
function file_exists_case($filename) {
    if (is_file($filename)) {
        if (IS_WIN) {
            if (basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

/**
 * 二维数组排序
 */
function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){  
	if(is_array($multi_array)){  
            foreach ($multi_array as $row_array){  
                if(is_array($row_array)){  
                    $key_array[] = $row_array[$sort_key];  
                }else{  
                    return -1;  
                }  
            }  
	}else{  
            return -1;  
	}  
	array_multisort($key_array,$sort,$multi_array);  
	return $multi_array;  
} 
?>