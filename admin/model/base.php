<?php
/*
[UCenter] (C)2001-2099 Comsenz Inc.
This is NOT a freeware, use is subject to license terms

$Id: base.php 1059 2011-03-01 07:25:09Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class base {
	var $time;
	var $onlineip;
	var $db;
	var $view;
	var $user = array();
	var $settings = array();
	var $cache = array();
	var $app = array();
	var $lang = array();
	var $input = array();
        var $defaulttpldir;
        var $basevar;
        var $_id;
        var $iuser;
	function __construct() {    
            
            $this->defaulttpldir = UC_ROOT.'./view/default';
            $this->base();
            $this->iuser = $_COOKIE;
	}
        function session($id){            
            if(!$id)
                return $_SESSION;
            return $_SESSION[$id];
        }
        
        function cookie($id){            
            if(!$id)
                return $_COOKIE;
            return $_COOKIE[$id];
        }
        
        function setsession($id,$val){
            $_SESSION[$id]=$val;
        }
        
        function init($id){
           $this->_id=$id;
           
           //读取头部   
           $this->basevar['title']="中公教育网校平台";
           if(!$this->session('userid')){
                if($this->iuser['userid']){
                    $role = $this->load('role');
                    $perm = $role->getOnepe($this->iuser['role_id']);
                    $this->setsession('userid', $this->iuser['userid']);
                    $this->setsession('username', $this->iuser['username']);
                    $this->setsession('perm', $perm);
                    $this->setsession('areaid', $this->iuser['areaid']);
                    $this->setsession('typejobid', $this->iuser['typejobid']);               
                }               
           }
           $perm = $this->session('perm');
           if($this->session('userid')){
                if(!$perm[$this->_id] && $this->_id != 'index'){
                    $this->error('没有权限',$this->url('index','','index'));
                }               
           }           
        }
        
        
        /**
        * 一键刷新职位信息
        * @param type $id
        * @return null
        */
       function sessionrefreshalljob($id) {
           if (!$id)
               return null;
           $r = $this->init_rediscaches();
           $r->select(1);
           return $r->get('refresh_' . $id);
       }

       /**
        * 设置一键刷新职位信息
        * @param type $id
        * @param type $val
        * @return type
        */
       function setsessionrefreshalljob($id, $val) {
           $r = $this->init_rediscaches();
           $r->select(1);
           return $r->setex('refresh_' . $id, 3600 * 24, $val);
       }

        
        
        /* 初始化redis服务 走类库*/
        function init_rediscaches() {
            static $rediscaches;
            if (!$rediscaches) {
                require_once UC_ROOT.'command/cache/driver/RedisX.class.php';
                $options['expire'] = -1;
                $rediscaches = Cache::getInstance('RedisX',$options);
            }
            return $rediscaches;
        }
    
    
        
        function getid(){
            return $this->_id;
        }
        
        function base() {
		$this->init_var();
	}

	function init_var() {
		$this->time = time();
		$cip = getenv('HTTP_CLIENT_IP');
		$xip = getenv('HTTP_X_FORWARDED_FOR');
		$rip = getenv('REMOTE_ADDR');
		$srip = $_SERVER['REMOTE_ADDR'];
		if($cip && strcasecmp($cip, 'unknown')) {
			$this->onlineip = $cip;
		} elseif($xip && strcasecmp($xip, 'unknown')) {
			$this->onlineip = $xip;
		} elseif($rip && strcasecmp($rip, 'unknown')) {
			$this->onlineip = $rip;
		} elseif($srip && strcasecmp($srip, 'unknown')) {
			$this->onlineip = $srip;
		}
		preg_match("/[\d\.]{7,15}/", $this->onlineip, $match);
		$this->onlineip = $match[0] ? $match[0] : 'unknown';

		define('FORMHASH', $this->formhash());
		$_GET['page'] =  max(1, intval(getgpc('page')));
		//include_once UC_ROOT.'./view/default/main.lang.php';
		$this->lang = &$lang;
	}

	function init_cache() {
		$this->settings = $this->cache('settings');
		$this->cache['apps'] = $this->cache('apps');
		if(PHP_VERSION > '5.1') {
			$timeoffset = intval($this->settings['timeoffset'] / 3600);
			@date_default_timezone_set('Etc/GMT'.($timeoffset > 0 ? '-' : '+').(abs($timeoffset)));
		}
	}

	function init_input($getagent = '') {
		$input = getgpc('input', 'R');
		if($input) {
			$input = $this->authcode($input, 'DECODE', $this->app['authkey']);
			parse_str($input, $this->input);
			$this->input = daddslashes($this->input, 1, TRUE);
			$agent = $getagent ? $getagent : $this->input['agent'];

			if(($getagent && $getagent != $this->input['agent']) || (!$getagent && md5($_SERVER['HTTP_USER_AGENT']) != $agent)) {
				exit('Access denied for agent changed');
			} elseif($this->time - $this->input('time') > 3600) {
				exit('Authorization has expired');
			}
		}
		if(empty($this->input)) {
			exit('Invalid input');
		}
	}
	function init_app() {
		$appid = intval(getgpc('appid'));
		$appid && $this->app = $this->cache['apps'][$appid];
	}

	function init_user() {
		if(isset($_COOKIE['uc_auth'])) {
			@list($uid, $username, $agent) = explode('|', $this->authcode($_COOKIE['uc_auth'], 'DECODE', ($this->input ? $this->app['appauthkey'] : UC_KEY)));
			if($agent != md5($_SERVER['HTTP_USER_AGENT'])) {
				$this->setcookie('uc_auth', '');
			} else {
				@$this->user['uid'] = $uid;
				@$this->user['username'] = $username;
			}
		}
	}

	function init_template() {
		$charset = UC_CHARSET;
		require_once UC_ROOT.'lib/template.class.php';
		$this->view = new template();
		$this->view->assign('dbhistories', init_db()->histories);
		$this->view->assign('charset', $charset);
		$this->view->assign('dbquerynum', init_db()->querynum);
		$this->view->assign('user', $this->user);
	}

	function init_note() {
		if($this->note_exists() && !getgpc('inajax')) {
			$this->load('note');
			$_ENV['note']->send();
		}
	}

	function init_mail() {
		if($this->mail_exists() && !getgpc('inajax')) {
			$this->load('mail');
			$_ENV['mail']->send();
		}
	}
        
        
        //要将数据导出到本地即下在，需要修改header信息，代码如下：
        function export_csv($filename,$data) {
                header("Content-type:text/csv");
                header("Content-Disposition:attachment;filename=".$filename);
                header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
                header('Expires:0');
                header('Pragma:public');
                echo $data;
        }

	function authcode() {
            $a='1234567890';
            for($i=0;$i<5;$i++){
                $l.=substr($a,rand(0, 9),1);
            }
            $this->setsession('seccode',$l);
            return $l;
        }
        function getauthcode(){
            return $this->session('seccode');
        }
        
        //开始时间转换 转换为时间戳
        function zh($val){
                $arr = explode('-',$val);
                $dd=mktime(0,0,0,$arr[1],$arr[2],$arr[0]);
                return $dd;
        }

        //精确时间 精确到秒
        function zh_jq($val){
                $arr = explode(' ',$val);
                $arr1 = explode('-',$arr[0]);
                $arr2 = explode(':',$arr[1]);
                $dd=mktime($arr2[0],$arr2[1],$arr2[2],$arr1[1],$arr1[2],$arr1[0]);
                return $dd;
        }

        // 结束时间 转换为时间戳
        function zh_end($val){
                $arr = explode('-',$val);
                $dd=mktime(23,59,59,$arr[1],$arr[2],$arr[0]);
                return $dd;
        }
        
        
        /**
         * 取得文件扩展
         *
         * @param $filename 文件名
         * @return 扩展名
         */
        function fileext($filename) {
                return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
        }
        
        /**
	 * 返回附件类型图标
	 * @param $file 附件名称
	 * @param $type png为大图标，gif为小图标
	 */
	 function file_icon($file,$type = 'png') {
		$ext_arr = array('doc','docx','ppt','xls','txt','pdf','mdb','jpg','gif','png','bmp','jpeg','rar','zip','swf','flv');
		$ext = $this->fileext($file);
		if($type == 'png') {
			if($ext == 'zip' || $ext == 'rar') $ext = 'rar';
			elseif($ext == 'doc' || $ext == 'docx') $ext = 'doc';
			elseif($ext == 'xls' || $ext == 'xlsx') $ext = 'xls';
			elseif($ext == 'ppt' || $ext == 'pptx') $ext = 'ppt';
			elseif ($ext == 'flv' || $ext == 'swf' || $ext == 'rm' || $ext == 'rmvb') $ext = 'flv';
			else $ext = 'do';
		}
		if(in_array($ext,$ext_arr)) return PUBLIC_URL.'public/images/ext/'.$ext.'.'.$type;
		else return PUBLIC_URL.'public/images/ext/blank.'.$type;
	}
        
        /**
         * 
         * @global type $globe_exam
         * @param type $action action
         * @param type $var 数组变量
         * @param type $con controllname
         * @param type $p //?=dddd,可通过p来追加字符串
         * @return type
         */
        function url($action,$var=array(),$con=false,$p=''){
            global $globe_exam;
            $type = '';
            $gets = getgpc();           
            $type =$globe_exam[$gets['type']][0];           
            $stra='';
            $action||$action='index';
            foreach ($var as $k=>$v){
                if(trim($v)&&trim($k))
                $stra.='/'.$k.'/'.$v;
            }       
            if(!$con)
                $con=$this->getid();
            if($type)
                 return TURLS.'/'.$type.'/'.$con.'/'.$action.$stra.$p;
            else
                 return TURLS.'/'.$con.'/'.$action.$stra.$p;    
        }
        /**
         * 
         * @param type $url 如果为数组 array(control,action,var)
         */
        function redirect($url){            
		if(is_array($url))
		{
			$url='/'.$this->url($url[1]?$url[1]:'index',$url[2],$url[0]);			
		}              
		header('Location: '.$url, true, 302);
        }
        /**
         * 
         * @param type $num
         * @param type $action//没用了
         * @param type $var//没用了
         * @param type $curpage
         * @param type $perpage
         * @return string
         */
	function page($num,$curpage,$perpage=PAGESIZE,$tnum=6,$v=array()) {
            $curpage=(int)$curpage;
            $curpage||$curpage=1;  
            $pagernum=(int)(($num+$perpage-1)/$perpage);      
            $gets=getgpc('','R'); //这块改成用request去获取 用get会丢数据
            $action=$gets['a'];
            $var=array_diff_key($gets,array('m'=>false,'a'=>false,'page'=>false,'type'=>false)); 
            $var = $v;
            $url=$this->url ($action, $var,'');
            if($curpage>1)
                $previous=$url.'/page/'.($curpage-1);
            if($curpage<$pagernum)
                $next=$url.'/page/'.($curpage+1);
            $index=$url.'/page/1';
            $end=$url.'/page/'.$pagernum;
            $pagenav = $this->sapne($tnum,$curpage,$pagernum,$url);
            if($num):
                $pagestr='<style>
                /**
                 * CSS styles for CLinkPager.
                 *
                 * @author Qiang Xue <qiang.xue@gmail.com>
                 * @link http://www.yiiframework.com/
                 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
                 * @license http://www.yiiframework.com/license/
                 * @since 1.0
                 */
                                ul.yiiPager
                                {
                                    border:0;
                                    margin:0;
                                    padding:0;
                                    line-height:100%;
                                    display:inline;
                                }
                                ul.yiiPager a{font-size:14px;}
                                ul.yiiPager li
                                {
                                    display:inline;
                                    margin-right:5px;
                                }
                                ul.yiiPager a:link,
                                ul.yiiPager a:visited
                                {
                                    color:#888888;
                                    text-decoration:none;
                                }
                                ul.yiiPager .page a
                                {
                                    font-weight:normal;
                                }
                                ul.yiiPager .selected a
                                {
                                    background:#6da1c9;
                                    color:#FFFFFF;
                                    font-weight:bold;
                                    padding:7px;
                                }
                                ul.yiiPager .hidden a
                                {
                                    color:#888888;
                                }
                                 ul.yiiPager li .page_page{ background:#eee; height:30px; line-height:30px; display:inline-block; padding:0 10px; color:#000;}
                                /**
                                 * Hide first and last buttons by default.
                                 */
                                ul.yiiPager .first,
                                ul.yiiPager .last
                                {
                                    display:none;
                                }  </style><ul id="yw0" class="yiiPager">';
                if($num)
                    $pagestr.='<font>共有<em>'.$num.'</em>条</font>&nbsp;';
                if($previous)
                    $pagestr.='<li><a href="'.$index.'" class="page_page">首页</a></li>&nbsp;<li><a href="'.$previous.'" class="page_page">上一页</a></li>&nbsp;';
                else
                    $pagestr.='<li class=hidden><a class="page_page">首页</a></li>&nbsp;<li class=hidden><a class="page_page">上一页</a></li>&nbsp;';  
                
                $pagestr.= $pagenav;

                if (isset($next)):
                    $pagestr.='<li><a href="'.$next.'" class="page_page">下一页</a></li>&nbsp;<li><a href="'.$end.'" class="page_page">尾页</a></li>';
                else: 
                    $pagestr.='<li class=hidden><a  class="page_page">下一页</a></li>&nbsp;<li  class=hidden><a  class="page_page">尾页</a></li>';
                endif; 
                    $pagestr.='</ul>';
                endif;
                
                for ($i=0; $i<$pagernum; $i++) {
                        $page_arr[] = $i+1;
                        $nu = $i+1;
                        $purl_arr[] = $url.'/page/'.($i+1);
                }
              
                //$urls = $url.'/page/'.$i+1;
                        //$purl_arr[] = $urls;
                if($pagestr != ''){
                    $pagestr .='<font>跳转</font><select name=""onchange="window.location=this.value;">';
                }
                foreach ($page_arr as $key => $page){
                    if ($page != $curpage){
                        $pagestr.='<option value="'.$purl_arr[$key].'">'.$page.'</option>';
                    }else{
                        $pagestr.='<option value="'.$purl_arr[$key].'" selected>'. $page.'</option>';
                    }
                }
                if($pagestr != ''){
                    $pagestr.='</select><font>页</font>';    
                }
                
		return $pagestr;
	}
        
        
        function page2($num,$curpage,$perpage=PAGESIZE,$tnum=6) {
            $curpage=(int)$curpage;
            $curpage||$curpage=1;  
            $pagernum=(int)(($num+$perpage-1)/$perpage);      
            $gets=getgpc('','R'); //这块改成用request去获取 用get会丢数据
            $action=$gets['a'];
            $var=array_diff_key($gets,array('m'=>false,'a'=>false,'page'=>false,'type'=>false)); 
            $url=$this->url ($action, $var,'');
            if($curpage>1)
                $previous=$url.'/page/'.($curpage-1);
            if($curpage<$pagernum)
                $next=$url.'/page/'.($curpage+1);
            $index=$url.'/page/1';
            $end=$url.'/page/'.$pagernum;
            $pagenav = $this->sapne($tnum,$curpage,$pagernum,$url);
            if($num):
                $pagestr='<style>
                /**
                 * CSS styles for CLinkPager.
                 *
                 * @author Qiang Xue <qiang.xue@gmail.com>
                 * @link http://www.yiiframework.com/
                 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
                 * @license http://www.yiiframework.com/license/
                 * @since 1.0
                 */
                                ul.yiiPager
                                {
                                    border:0;
                                    margin:0;
                                    padding:0;
                                    line-height:100%;
                                    display:inline;
                                }
                                ul.yiiPager a{font-size:14px;}
                                ul.yiiPager li
                                {
                                    display:inline;
                                    margin-right:5px;
                                }
                                ul.yiiPager a:link,
                                ul.yiiPager a:visited
                                {
                                    color:#888888;
                                    text-decoration:none;
                                }
                                ul.yiiPager .page a
                                {
                                    font-weight:normal;
                                }
                                ul.yiiPager .selected a
                                {
                                    background:#29bdfb;
                                    color:#FFFFFF;
                                    font-weight:bold;
                                    padding:7px;
                                }
                                ul.yiiPager .hidden a
                                {
                                    color:#888888;
                                }
                                 ul.yiiPager li .page_page{ background:#eee; height:30px; line-height:30px; display:inline-block; padding:0 10px; color:#000;}
                                /**
                                 * Hide first and last buttons by default.
                                 */
                                ul.yiiPager .first,
                                ul.yiiPager .last
                                {
                                    display:none;
                                }  </style><ul id="yw0" class="yiiPager">';
                if($previous)
                    $pagestr.='<li><a href="'.$index.'" class="page_page">首页</a></li>&nbsp;<li><a href="'.$previous.'" class="page_page">上一页</a></li>&nbsp;';
                else
                    $pagestr.='<li class=hidden><a class="page_page">首页</a></li>&nbsp;<li class=hidden><a class="page_page">上一页</a></li>&nbsp;';  
                
                $pagestr.= $pagenav;

                if (isset($next)):
                    $pagestr.='<li><a href="'.$next.'" class="page_page">下一页</a></li>&nbsp;<li><a href="'.$end.'" class="page_page">尾页</a></li>';
                else: 
                    $pagestr.='<li class=hidden><a  class="page_page">下一页</a></li>&nbsp;<li  class=hidden><a  class="page_page">尾页</a></li>';
                endif; 
                    $pagestr.='</ul>';
                endif;
                
                for ($i=0; $i<$pagernum; $i++) {
                        $page_arr[] = $i+1;
                        $nu = $i+1;
                        $purl_arr[] = $url.'/page/'.($i+1);
                }
              
                //$urls = $url.'/page/'.$i+1;
                        //$purl_arr[] = $urls;
                $pagestr .='<span class="tz">跳转<select name=""onchange="window.location=this.value;">';
                foreach ($page_arr as $key => $page){
                    if ($page != $curpage){
                        $pagestr.='<option value="'.$purl_arr[$key].'">'.$page.'</option>';
                    }else{
                        $pagestr.='<option value="'.$purl_arr[$key].'" selected>'. $page.'</option>';
                    }
                }

                $pagestr.='</select> 页</span>';                
                
		return $pagestr;
	}
       //计算中间的数据   
       public function sapne($showlvtao,$page,$lastpg,$url){
            $o=$showlvtao;//中间页码表总长度，为奇数
            $u=ceil($o/2);//根据$o计算单侧页码宽度$u
            $f=$page-$u;//根据当前页$currentPage和单侧宽度$u计算出第一页的起始数字
            //str_replace('{p}',,$fn)//替换格式
            if($f<0){$f=0;}//当第一页小于0时，赋值为0
            $n=$lastpg;//总页数,20页
            if($n<1){$n=1;}//当总数小于1时，赋值为1
            if($page==1){
                    $pagenav.='<li class="page selected"><a href="javascripr:;">1</a></li>&nbsp;';
            }else{
                    $pagenav.="<li class='page'><a href='".$url."/page/1'>1</a></li>&nbsp;";
            }
            ///////////////////////////////////////

            for($i=1;$i<=$o;$i++){
                    if($n<=1){break;}//当总页数为1时
                    $c=$f+$i;//从第$c开始累加计算
                    if($i==1 && $c>2){
                            $pagenav.='...';
                    }
                    if($c==1){continue;}
                    if($c==$n){break;}
                    if($c==$page){
                            $pagenav.='<li class="page selected"><a href="javascripr:;">'.$page.'</a></li>&nbsp;';
                    }else{
                            $pagenav.="<li class='page'><a href='".$url."/page/".$c."'>$c</a></li>&nbsp;";
                    }
                    if($i==$o && $c<$n-1){
                            $pagenav.='...';
                    }
                    if($i>$n){break;}//当总页数小于页码表长度时	
            }
            if($n!=1){
                if($page==$n && $n!=1){
                        $pagenav.='<li class="page selected"><a href="javascripr:;">'.$n.'</a></li>&nbsp;';
                }else{
                        $pagenav.="<li class='page'><a href='".$url."/page/".$n."'>$n</a></li>&nbsp;";
                }            
            }

            return $pagenav;
       } 
       
       
        /**
         *  ajax分页实现
         * @param type $ajax_func ajax分页方法
         * @param type $num
         * @param type $action//没用了
         * @param type $var//没用了
         * @param type $curpage
         * @param type $perpage
         * @return string
         */
	function pageAjax($ajax_func,$num,$curpage,$perpage=PAGESIZE,$tnum=5) {
            $curpage=(int)$curpage;
            $curpage||$curpage=1;          
            $pagernum=(int)(($num+$perpage-1)/$perpage);
            if($curpage>1)
                $previous = $curpage - 1;
            if($curpage<$pagernum)
                $next = $curpage + 1;
            $index = 1;
            $end = $pagernum;
            $pagenav = $this->sapneAjax($ajax_func,$tnum,$curpage,$pagernum);    
            
            if($num):
                $pagestr='<font>共有<em>'.$num.'</em>条</font>&nbsp;';
            
            if($previous)
                $pagestr.='<a href="javascript:'.$ajax_func.'('.$index.')" class="pag">首页</a><a href="javascript:'.$ajax_func.'('.$previous.')" class="pag">上一页</a>';
            else
                $pagestr.='<a class="pag">首页</a><a class="pag">上一页</a>';
      
            $pagestr.= $pagenav;

            if (isset($next)):
                $pagestr.='<a href="javascript:'.$ajax_func.'('.$next.')" class="pag">下一页</a><a href="javascript:'.$ajax_func.'('.$end.')" class="pag">尾页</a>';
            else: 
                $pagestr.='<a  class=pag>下一页</a><a  class=pag>尾页</a>';
            endif; 
                $pagestr.='';
            endif;
            
            for ($i=0; $i<$pagernum; $i++) {
                    $page_arr[] = $i+1;
                    $nu = $i+1;
                    $purl_arr[] = ($i+1);
            }            
            $pagestr .='<span class="pag">跳转<select name=""onchange="'.$ajax_func.'(this.value);">';
            foreach ($page_arr as $key => $page){
                if ($page != $curpage){
                    $pagestr.='<option value="'.$purl_arr[$key].'">'.$page.'</option>';
                }else{
                    $pagestr.='<option value="'.$purl_arr[$key].'" selected>'. $page.'</option>';
                }
            }

            $pagestr.='</select> 页</span>';                    
            
            return $pagestr;
	}
        
       //计算中间的数据   ajax专用
       public function sapneAjax($ajax_func,$showlvtao,$page,$lastpg){
            $o=$showlvtao;//中间页码表总长度，为奇数
            $u=ceil($o/2);//根据$o计算单侧页码宽度$u
            $f=$page-$u;//根据当前页$currentPage和单侧宽度$u计算出第一页的起始数字
            //str_replace('{p}',,$fn)//替换格式
            if($f<0){ $f=0; }//当第一页小于0时，赋值为0
            $n=$lastpg;//总页数,20页
            if($n<1){$n=1;}//当总数小于1时，赋值为1
            if($page==1){
                    $pagenav.='<span>1</span>';
            }else{
                    $pagenav.='<a href="javascript:'.$ajax_func.'(1)">1</a>';
            }
            ///////////////////////////////////////

            for($i=1;$i<=$o;$i++){
                    if($n<=1){break;}//当总页数为1时
                    $c=$f+$i;//从第$c开始累加计算
                    if($i==1 && $c>2){
                            $pagenav.='&nbsp;&nbsp;…';
                    }
                    if($c==1){continue;}
                    if($c==$n){break;}
                    if($c==$page){
                            $pagenav.='<span>'.$page.'</span>';
                    }else{
                            $pagenav.='<a href="javascript:'.$ajax_func.'('.$c.')">'.$c.'</a>';
                    }
                    if($i==$o && $c<$n-1){
                            $pagenav.='<font>&nbsp;&nbsp;…</font>';
                    }
                    if($i>$n){break;}//当总页数小于页码表长度时	
            }
            if($n!=1){
                if($page==$n && $n!=1){
                        $pagenav.='<span>'.$n.'</span>';
                }else{
                        $pagenav.='<a href="javascript:'.$ajax_func.'('.$n.')">'.$n.'</a>';
                }            
            }

            return $pagenav;
       }    
          
       
	function page_get_start($page, $ppp, $totalnum) {
		$totalpage = ceil($totalnum / $ppp);
		$page =  max(1, min($totalpage, intval($page)));
		return ($page - 1) * $ppp;
	}

	function load($model, $base = NULL, $release = '') {
		$base = $base ? $base : $this;
                static $_ENV;
		if(empty($_ENV[$model])) {
			$release = !$release ? RELEASE_ROOT : $release;
			if(file_exists(UC_ROOT.$release."model/$model.php")) {
				require_once UC_ROOT.$release."model/$model.php";
			} else {
				require_once UC_ROOT."model/$model.php";
			}
                        $c=$model.'model';
			$_ENV[$model] = new $c($base);                      
		}
		return $_ENV[$model];
	}

	function get_setting($k = array(), $decode = FALSE) {
            return 0;
	}

	function set_setting($k, $v, $encode = FALSE) {
            return 0;
	}
        
        /**
	* 操作成功跳转方法
	*/	
	function success($message='',$url='',$time='2'){
		$message = !empty($message) ? $message : '操作成功！'; 
		$url = !empty($url) ? $url : $_SERVER["HTTP_REFERER"]; 
		$this->RedirectMessage('success',$message,$url,$time);
	}

	/**
	* 操作失败跳转方法
	*/	
	function error($message='',$url='',$time='1'){
		$message = !empty($message) ? $message : '操作失败！'; 
		$url = !empty($url) ? $url : $_SERVER["HTTP_REFERER"]; 
		$this->RedirectMessage('error',$message,$url,$time);
	}

	/**
	* 公共跳转方法
	*/	
        function RedirectMessage($type,$message,$url,$time){
                require_once UC_ROOT.'lib/template.class.php';
		$this->view = new template();
                $this->view->assign('message', $message);
		$this->view->assign('redirect', $url);
                $this->view->assign('type', $type);
                $this->view->assign('waitSecond', $time);
                $this->view->assign('UrlShortcut', $this->UrlShortcut);
                $this->view->assign('css', PUBLIC_URL."public/css/style.css");
                if($type == 'success'){
                     $this->view->assign('img', PUBLIC_URL."public/images/zg_jz_06.jpg");
                     $this->view->display('showmessage');
                }else{
                     $this->view->assign('img', PUBLIC_URL."public/images/zg_jz_11.jpg");
                     $this->view->display('showmessage2');
                }
		//避免出错后继续执行
		exit;
	}
        

	function message($message, $redirect = '', $type = 0, $vars = array()) {
		include_once UC_ROOT.'view/default/messages.lang.php';
		if(isset($lang[$message])) {
                    $message = $lang[$message] ? str_replace(array_keys($vars), array_values($vars), $lang[$message]) : $message;
		}
		$this->view->assign('message', $message);
		$this->view->assign('redirect', $redirect);
		if($type == 0) {
                    $this->view->display('message');
		} elseif($type == 1) {
                    $this->view->display('message_client');
		}
		exit;
	}

	function formhash() {
		return substr(md5(substr($this->time, 0, -4).UC_KEY), 16);
	}

	function submitcheck() {
		return @getgpc('formhash', 'P') == FORMHASH ? true : false;
	}

	function date($time, $type = 3) {
		$format[] = $type & 2 ? (!empty($this->settings['dateformat']) ? $this->settings['dateformat'] : 'Y-n-j') : '';
		$format[] = $type & 1 ? (!empty($this->settings['timeformat']) ? $this->settings['timeformat'] : 'H:i') : '';
		return gmdate(implode(' ', $format), $time + $this->settings['timeoffset']);
	}

	function implode($arr) {
		return "'".implode("','", (array)$arr)."'";
	}

	function set_home($uid, $dir = '.') {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		!is_dir($dir.'/'.$dir1) && mkdir($dir.'/'.$dir1, 0777);
		!is_dir($dir.'/'.$dir1.'/'.$dir2) && mkdir($dir.'/'.$dir1.'/'.$dir2, 0777);
		!is_dir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3) && mkdir($dir.'/'.$dir1.'/'.$dir2.'/'.$dir3, 0777);
	}

	function get_home($uid) {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		return $dir1.'/'.$dir2.'/'.$dir3;
	}

	function get_avatar($uid, $size = 'big', $type = '') {
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$typeadd = $type == 'real' ? '_real' : '';
		return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	}

	function &cache($cachefile) {
		static $_CACHE = array();
		if(!isset($_CACHE[$cachefile])) {
                    $cachepath = UC_DATADIR.'./cache/'.$cachefile.'.php';
                    if(!file_exists($cachepath)) {
                            $this->load('cache');
                            $_ENV['cache']->updatedata($cachefile);
                    } else {
                            include_once $cachepath;
                    }
		}
		return $_CACHE[$cachefile];
	}

	function input($k) {
		return isset($this->input[$k]) ? (is_array($this->input[$k]) ? $this->input[$k] : trim($this->input[$k])) : NULL;
	}

	function serialize($s, $htmlon = 0) {
		if(file_exists(UC_ROOT.RELEASE_ROOT.'./lib/xml.class.php')) {
			include_once UC_ROOT.RELEASE_ROOT.'./lib/xml.class.php';
		} else {
			include_once UC_ROOT.'./lib/xml.class.php';
                }
		return xml_serialize($s, $htmlon);
	}

	function unserialize($s) {
		if(file_exists(UC_ROOT.RELEASE_ROOT.'./lib/xml.class.php')) {
			include_once UC_ROOT.RELEASE_ROOT.'./lib/xml.class.php';
		} else {
			include_once UC_ROOT.'./lib/xml.class.php';
		}
		return xml_unserialize($s);
	}

	function cutstr($string, $length, $dot = ' ...') {
		if(strlen($string) <= $length) {
			return $string;
		}
		$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
		$strcut = '';
		if(strtolower(UC_CHARSET) == 'utf-8') {
			$n = $tn = $noc = 0;
			while($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1; $n++; $noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2; $n += 2; $noc += 2;
				} elseif(224 <= $t && $t < 239) {
					$tn = 3; $n += 3; $noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4; $n += 4; $noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5; $n += 5; $noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6; $n += 6; $noc += 2;
				} else {
					$n++;
				}
				if($noc >= $length) {
					break;
				}
			}
			if($noc > $length) {
				$n -= $tn;
			}
			$strcut = substr($string, 0, $n);
		} else {
			for($i = 0; $i < $length; $i++) {
				$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			}
		}
		$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
		return $strcut.$dot;
	}

	function setcookie($key, $value, $life = 0, $httponly = false) {
		(!defined('UC_COOKIEPATH')) && define('UC_COOKIEPATH', '/');
		(!defined('UC_COOKIEDOMAIN')) && define('UC_COOKIEDOMAIN', '');
		if($value == '' || $life < 0) {
			$value = '';
			$life = -1;
		}
		$life = $life > 0 ? $this->time + $life : ($life < 0 ? $this->time - 31536000 : 0);
		$path = $httponly && PHP_VERSION < '5.2.0' ? UC_COOKIEPATH."; HttpOnly" : UC_COOKIEPATH;
		$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
		if(PHP_VERSION < '5.2.0') {
			setcookie($key, $value, $life, $path, UC_COOKIEDOMAIN, $secure);
		} else {
			setcookie($key, $value, $life, $path, UC_COOKIEDOMAIN, $secure, $httponly);
		}
	}

	function note_exists() {
            return 0;
	}

	function mail_exists() {
            return 0;
	}

	function dstripslashes($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = $this->dstripslashes($val);
			}
		} else {
			$string = stripslashes($string);
		}
		return $string;
	}
        function render($fname,$var){           
                extract($var, EXTR_SKIP);
                include $this->defaulttpldir.'/'.$this->_id.'/'.$fname.'.php';
        }
        
        function renderPartial($fname,$var){   
                // 页面缓存
                ob_start();
                ob_implicit_flush(0);
                extract($var, EXTR_SKIP);
                include $this->defaulttpldir.'/'.$this->_id.'/'.$fname.'.php';
                $content = ob_get_clean();
                return $content;
        }
        
        function getlogin(){
                return $this->session('userid');
        }
        function checklogin(){
                $uid=$this->getlogin();            
                if(!$uid){
                     $uri=$_SERVER['REQUEST_URI'];
                    $this->redirect($this->url('index',null,'error','?uri='.$uri));
                } 
        }
        
        //人数计算
        function getNums($num){
            if($num == 0)
                return 0;
            $num = (int)$num * 11 + 3;
            return $num;
        }
        function getrole($index){
            $role = array(
                '1'=>'所有人',
                '2'=>'游客',
                '3'=>'非学员未认证',
                '4'=>'非学员已认证',
                '5'=>'学员',
                '6'=>'讲师',
                '7'=>'管理员'                
            );
            if(!$index){
              return $role;  
            }else{
              return $role[$index];
            }
        }
	
    /**
     * 验证手机号码
     * @param type $phone
     * @return boolean
     */
    function checkphone($phone) {
        if (preg_match("/^1[0-9][0-9]{9}$/", $phone)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证邮箱地址
     * @param type $email
     * @return boolean
     */
    function checkemail($email) {
        if (preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z\\-_\\.]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $email)) {
            return true;
        } else {
            return false;
        }
    }
       
}

?>