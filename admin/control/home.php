<?php	!defined('IN_UC') && exit('Access Denied');

/*
 * 后台首页管理
 */

class home extends base {

    function __construct() {
        parent::__construct();
    }


    function actionindex() {
        if (!$this->session('userid')) {
            $this->error("请登录", $this->url('login', null, 'index'));
        } else {
            $this->render('index', array('role_id' => (int) $this->session('role_id')));
        }
    }

    //课程管理左侧导航
    function actionCourseleft() {
        $this->render('left_course');
    }

    //课件管理左侧导航
    function actionLessonleft() {
        
    }

    function actioncreate_index() {
		$exam_type = $this->session('exam_type');
		$exam_arr = array(1=>'kaoyan',2=>'yikao',3=>'kuaiji',4=>'jinrong',5=>'it');
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
	
	//生成直播课页_张伟强
	function actioncreate_live(){
		$exam_type = $this->session('exam_type');
		$exam_arr = array(1=>'kaoyan',2=>'yikao',3=>'kuaiji');
		$exam_tag = $exam_arr[$exam_type];
		$str = file_get_contents(WXWEB_URL . "/".$exam_tag."/home/live/");
		$file = WXWEB_PATH . "/".$exam_tag."/zhibo/index.html"; //直播页
		$dir = dirname($file);
		if(!is_dir($dir)) {
			mkdir($dir, 0777,1);
		}
        if (file_exists($file)) {
            unlink($file);
        }
        $flag = file_put_contents($file, $str);
        if ($flag) {
            $this->success("直播课页更新成功", $this->url('index', null, 'index'));
        } else {
            $this->error("直播课页更新失败", $this->url('index', null, 'index'));
        }
    }

	//生成服务的静态页
	function actioncreate_fuwu() {
		$exam_type = $this->session('exam_type');
		$exam_arr = array(1=>'kaoyan',2=>'yikao',3=>'kuaiji');
		$exam_tag = $exam_arr[$exam_type];
        $str = file_get_contents(WXWEB_URL . "/".$exam_tag."/home/ServiceCenter/");
        $file = WXWEB_PATH . "/".$exam_tag."/fuwu/index.html"; //服务页
		$dir = dirname($file);
		if(!is_dir($dir)) {
			mkdir($dir, 0777,1);
		}
        if (file_exists($file)) {
            unlink($file);
        }
        $flag = file_put_contents($file, $str);
        if ($flag) {
            $this->success("服务页更新成功", $this->url('index', null, 'index'));
        } else {
            $this->error("服务页更新失败", $this->url('index', null, 'index'));
        }
    }

	//生成名师堂页_张伟强
	function actioncreate_mingshi(){
		$exam_type = $this->session('exam_type');
		$exam_arr = array(1=>'kaoyan',2=>'yikao',3=>'kuaiji');
		$exam_tag = $exam_arr[$exam_type];
		$str = file_get_contents(WXWEB_URL . "/".$exam_tag."/home/ServiceMingshi/");
		$file = WXWEB_PATH . "/".$exam_tag."/mingshi/index.html"; //名师堂页
		$dir = dirname($file);
		if(!is_dir($dir)) {
			mkdir($dir, 0777,1);
		}
        if (file_exists($file)) {
            unlink($file);
        }
        $flag = file_put_contents($file, $str);
        if ($flag) {
            $this->success("名师堂页更新成功", $this->url('index', null, 'index'));
        } else {
            $this->error("名师堂页更新失败", $this->url('index', null, 'index'));
        }
    }
	
	
	
	function actioncreate_select() {

		$exam_type = $this->session('exam_type');
		$exam_arr = array(1=>'kaoyan',2=>'yikao',3=>'kuaiji');
		$exam_tag = $exam_arr[$exam_type];
        $str_s = file_get_contents(WXWEB_URL . "/".$exam_tag."/home/SelectedCourse/flag/create");
        $file_s = WXWEB_PATH . "/".$exam_tag."/xkzx/index.html"; //选课中心
		$dir = dirname($file);
		if(!is_dir($dir)) {
			mkdir($dir, 0777,1);
		}
        if (file_exists($file_s)) {
            unlink($file_s);

        }
        $flag_s = file_put_contents($file_s, $str_s);
        if ($flag_s) {
            $this->success("选课中心更新成功", $this->url('index', null, 'index'));
        } else {
            $this->error("选课中心更新失败", $this->url('index', null, 'index'));
        }

    }

}

?>