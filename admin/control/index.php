<?php	!defined('IN_UC') && exit('Access Denied');


/*
 * 后台首页管理
 */

class index extends base {

    function __construct() {
        parent::__construct();
    }

    //登录页面
    function actionlogin() {
        if (!$this->session('userid')) {
            $this->render('login');
        } else {
            $this->redirect($this->url('index', '', 'index'));
        }
		
    }

    function actionDoLogins() {
        
        header('Content-Type:text/html; charset=utf-8');
        $request = new grequest();
        $name = trim($request->getParam('username'));
        $password = htmlspecialchars(trim($request->getParam('password')));
        $passcode = trim($request->getParam('passcode'));
        $secode = trim($request->getParam('secode'));
        if ((!$secode) || ($secode != $this->session('seccode'))) {
            $this->error("对不起验证码错误", $this->url('login', null, 'index'), 1);
        }
        if (!$passcode || !$this->verifypasscode($passcode)) {
            $this->error("对不起口令码错误",$this->url('login',null,'index'),1);
        }
        $where = array();
        $where['username'] = $name;
        $where['password'] = $password;
        $adminuser = $this->load('adminuser');
        $role = $this->load('role');
        $user = $adminuser->getUser($where);
        $roleinfo = $role->getRole($user['role_id'],'name');

        if (!$user) {
            $this->error("对不起没有该帐号", $this->url('login', null, 'index'));
        } else {
            $set = array();
            $set['loginviews'] = $user['loginviews'] + 1;
            $set['changetime'] = time();
            $s = 1; //$adminuser->setUser($set,$user['userid']);
            if (!$s) {
                $this->error("登录失败", $this->url('login', null, 'index'));
            } else {
                $perm = $role->getOnepe($user['role_id']);
                //cookie赋值
                //$time = 3600 * 24 * 30 * 12; //1年时间 //2015-5-4 09:51:15 zpf edit
                $time = 1800; //半个小时时间 //2015-5-4 09:50:57 zpf plus
                $this->setcookie('userid', $user['id'], $time);
                $this->setcookie('username', $user['username'], $time);
                $this->setcookie('role_id', $user['role_id'], $time);
                $this->setcookie('areaid', $user['areaid'], $time);
                $this->setcookie('typejobid', $user['typejobid'], $time);
                $this->setcookie('typejobs', $user['typejobs'], $time);
                //session赋值
                $this->setsession('userid', $user['id']);
                $this->setsession('username', $user['username']);
                $this->setsession('rolename', $roleinfo['name']);
                $this->setsession('perm', $perm);
                $this->setsession('areaid', $user['areaid']);
                $this->setsession('typejobid', $user['typejobid']);
                $this->setsession('typejobs', $user['typejobs']);
                $this->setsession('exam_type', $user['exam_type']);
                //$this->success("登录成功",$this->url('index',null,'index'));
                $this->redirect($this->url('index', '', 'index'));
            }
        }
    }

    function verifypasscode($passcode) {
        if (trim($passcode)) {
            $newpw = date('Y-m-d/H') . 'B9.S3,Y*2';
            $salt = 'U*9i.T3,P';
            $kouling = md5(md5($newpw) . $salt);
            if ($passcode == $kouling) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function actionindex() {
        if (!$this->session('userid')) {
            $this->error("请登录", $this->url('login', null, 'index'));
        } else {
            $this->render('index', array('role_id' => (int) $this->session('role_id')));
        }
    }

    function actionLogout() {
        //cookie赋值
        $time = 3600 * 24 * 30 * 12; //1年时间
        $this->setcookie('userid', null, time() - $time);
        $this->setcookie('username', null, time() - $time);
        $this->setcookie('realname', null, time() - $time);
        $this->setcookie('phone', null, time() - $time);
        $this->setcookie('areaid', null, time() - $time);
        $this->setcookie('typejobid', null, time() - $time);
        //session赋值
        $_SESSION['userid'] = null;
        $_SESSION['username'] = null;
        $_SESSION['realname'] = null;
        $_SESSION['phone'] = null;
        $_SESSION['areaid'] = null;
        $_SESSION['typejobid'] = null;
        $_SESSION['rolename'] = null;
        $_SESSION['typejobs'] = null;
        $this->redirect($this->url('login', null, 'index'));
    }


    function actionLessonleft() {
        
    }

    function actioncreate_index() {
		$exam_type = $this->session('exam_type');
		$exam_arr = array(1=>'111',2=>'222',3=>'333');
		$exam_tag = $exam_arr[$exam_type];
        $str = file_get_contents(WXWEB_URL . "/".$exam_tag."/home/index/");
        $file = WXWEB_PATH . "/".$exam_tag."/index.html"; //首页
		$dir = dirname($file);
		if(!is_dir($dir)) {
			mkdir($dir, 0777,1);
		}
        if (file_exists($file)) {
            unlink($file);
        }
        $flag = file_put_contents($file, $str);
        if ($flag) {
            $this->success("首页更新成功", $this->url('index', null, 'index'));
        } else {
            $this->error("首页更新失败", $this->url('index', null, 'index'));
        }
    }
}

?>