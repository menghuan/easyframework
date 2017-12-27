<?php
!defined('IN_UC') && exit('Access Denied');
/*
*  站点类型
*/
class role extends base
{
	function __construct() {
            parent::__construct();
            if(!$this->session('userid')){
                 $this->error("请登录",$this->url('login',null,'index'));
            } 
	}
        
	/**
	 * 站点列表
	*/
	public function actionlist(){
                $request = new grequest();
                
                $page = $request->getParam('page')?htmlspecialchars(trim($request->getParam('page'))) : 1;
                $limit = 15;
		$model = $this->load("role");
                $total = $model->getotal();
		$list = $model->getList();
                $pager = $this->page($total,$page,$limit);
               
		$this->render('list',array('list'=>$list,'total'=>$total,'pags'=>$pager,'userinfo'=>$this->iuser));
	}

	/**
	 * 显示 添加 角色页面
	*/
	public function actionAdd(){
            $perm = array();
            $files = UC_ROOT . "control/";
            $op = $this->getSubDirs($files);
            foreach($op as $key=>$val){
                $info = $this->permissions($val);
                if($info){
                    $perm[$val] = $info;
                }
            }            
            $this->render('add',array('userinfo'=>$this->iuser,'perm'=>$perm));
	}
	/**
	 * 显示 添加 角色功能
	*/
	public function actionDoAdd(){
            $request = new grequest();
            $seit['name'] = $request->getParam('name')?htmlspecialchars(trim($request->getParam('name'))):'';
            $seit['description'] = $request->getParam('description')?htmlspecialchars(trim($request->getParam('description'))):'';
            $seit['status'] = $request->getParam('status')?htmlspecialchars(trim($request->getParam('status'))):0;
            $perm = $request->getParam('perm')?$request->getParam('perm'):'';
            if(!$seit['name']){
                $this->error('角色名称不能为空'); exit;
            }
            $seit['rid'] = rand(100000,999999);
            $ppem['rid'] = $seit['rid'];
            $ppem['perm'] = serialize($perm);
            $model= $this->load("role");
            $bool = $model->addData($seit,$ppem);
            if($bool){
		$this->Success("操作成功",$this->url('list',null,'role'));
            }else{
		$this->error("操作失败");
            }
	}
	/**
	 * 显示 添加 地区页面
	*/
	public function actionEdit(){
                $request = new grequest();
		$id = $request->getParam('id');
		if(!$id){
			$this->error('缺少地区ID'); exit;
		}
                $perm = array();
                $files = UC_ROOT . "control/";
                $op = $this->getSubDirs($files);
                foreach($op as $key=>$val){
                    $info = $this->permissions($val);
                    if($info){
                        $perm[$val] = $info;
                    }
                }
		$model = $this->load("role");
		$data = $model->getOne($id ,'*');
                $perms = $model->getOnepe($data['rid'],'perm');
		$this->render('edit',array('data'=>$data,'userinfo'=>$this->iuser,'perm'=>$perm,'perms'=>$perms));
	}


	/**
	 * 显示 编辑/添加 地区页面
	*/
	public function actionDoEdit(){
		$request = new grequest();
                $seit['name'] = $request->getParam('name')?htmlspecialchars(trim($request->getParam('name'))):'';
                $seit['description'] = $request->getParam('description')?htmlspecialchars(trim($request->getParam('description'))):'';
                $seit['status'] = $request->getParam('status')?htmlspecialchars(trim($request->getParam('status'))):0;
                $perm = $request->getParam('perm')?$request->getParam('perm'):'';
                $rid = $request->getParam('rid')?(int)$request->getParam('rid'):'';
                if(!$seit['name']){
                    $this->error('请输入角色名称'); exit;
                }
                if(!$perm){
                    $this->error('请选择权限'); exit;
                }
		$model= $this->load("role");
		$id = (int)$request->getParam('id',0);
                $ppem['perm'] = serialize($perm);
		$bool = $model->edit($id,$seit,$rid,$ppem);
		if($bool){
			$this->Success("操作成功",$this->url('list',null,'role'));
		}else{
			$this->error("操作失败");
		}
	}




	/**
	 * 更新缓存
	*/

	public function actionUpdateCache(){
                $request = new grequest();
		$model= $this->load("role");
		if(IS_AJAX){
			$model->updateCache();
			IS_AJAX && ajaxReturns(0,'更新成功',1);
		}else{
			$model->updateCache();	
			$this->Success('更新成功！');
		}
	}
        function actionDelete() {
            $request = new grequest();
            $id = $request->getParam('id',0);
            $rid = $request->getParam('rid',0);
            $model = $this->load("role");
            if($model->deletes($id,$rid)){
                    IS_AJAX && ajaxReturns(1,'操作成功',0);
            }else{
                    IS_AJAX && ajaxReturns(0,'操作失败',0);
            }            
        }
        function getSubDirs($dir) 
        {
                $subdirs = array();
                if(!$dh = opendir($dir)) 
                        return $subdirs;
                $i = 0;
                while ($f = readdir($dh)) 
                {
                if($f =='.' || $f =='..') 
                                continue;
                        //如果只要子目录名, path = $f;
                        //$path = $dir.'/'.$f;  
                        $path = $f;
                        $name = explode('.', $path);
                        $subdirs[$i] = $name[0];
                        $i++;
                }
                return $subdirs;
        }
        function permissions($param) {
            $psn = array(
                'admin' => '管理员管理',
                'area' => '地区管理',
                'liveactive' => '直播互动',
                'typejob' => '职位类别',
                'mecate' => '资讯栏目分类',
                'role' => '角色管理',
                'seccodec' => '验证码管理',
                'type' => '属性管理',
                'user' => '用户管理',
                'resume' => '简历管理',
                'basicdata' => '基础数据',
                'article' => '文章管理',
                'category' => '栏目管理',
                'jobs' => '职位管理',
                'adboard' => '广告位管理',
                'advert' => '广告管理',
                'resumetemplate' => '简历模板',
                'certificate'=>'企业认证审核',
                'company' => '公司管理',
                'stationresume'=>'全站简历文件夹管理'
            );
            return $psn[$param];
        }
}
