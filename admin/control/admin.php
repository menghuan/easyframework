<?php  !defined('IN_UC') && exit('Access Denied');
/*
*  后台管理员类型
*/
class admin extends base{
    
	function __construct() {
            parent::__construct();         
	}
	
	function actionlist(){
		$request = new grequest();
		$search=array();
		$search['username']=$request->getParam('username','');
		$search['role_id']=$request->getParam('role_id','');
		$search['areaid']=$request->getParam('areaid','');
		$search['jobid']=$request->getParam('jobid','');
		$search['status']=$request->getParam('status',0);
		$search['exam_type']=$this->session('exam_type');;
		$page = $request->getParam('page')?$request->getParam('page'):1;
		$adminuser = $this->load('adminuser');
		$limit = 15;
		$total = $adminuser->getcount($search);
		$list = $adminuser->getlist($search,$limit,($page-1)*$limit);

		
		//角色
		$rolemodel = $this->load("role");
		$RoleCache = $rolemodel->getList();
		$RoleCaches = ArrData($RoleCache,'rid');
		$pager=$this->page($total,$page,$limit);

		$this->render('list',array('list'=>$list,'pages'=>$pager,'RoleCaches'=>$RoleCaches,'search'=>$search));
	}


	public function actionDoAdd(){
		$request = new grequest();
		$add_data['username']   = $request->getParam('username')?htmlspecialchars(trim($request->getParam('username'))):'';
		$add_data['password']   = $request->getParam('password')?htmlspecialchars(trim($request->getParam('password'))):'';
		$add_data['realname']   = $request->getParam('realname')?htmlspecialchars(trim($request->getParam('realname'))):'';
		$add_data['email']      = $request->getParam('email')?htmlspecialchars(trim($request->getParam('email'))):'';
		$add_data['phone']      = $request->getParam('phone')?htmlspecialchars(trim($request->getParam('phone'))):'';
		$add_data['role_id']     = $request->getParam('role_id')?htmlspecialchars(trim($request->getParam('role_id'))):'';
		$add_data['status']     = $request->getParam('status')?htmlspecialchars(trim($request->getParam('status'))):'';
		$add_data['times']      = time();
		$add_data['ip']         = $_SERVER["REMOTE_ADDR"];
		$add_data['password'] = md5($add_data['password']);
		$adminuser =  $this->load('adminuser');
		$bool = $adminuser->insert($add_data);
		if($bool===false){
			$this->error('添加用户信息失败');
		}else{
			$this->Success('操作成功',$this->url('list',null,'admin'));
		}

	}


	/**
	 * 显示 添加 地区页面
	*/
	public function actionAdd(){
		$rolemodel = $this->load("role");
		$RoleCache = $rolemodel->getList();
             
		$this->render('add',array(
			'RoleCache'=>$RoleCache,
		));
	}

	/**
	 * 显示 修改 用户页面permissions
	*/
	public function actionEdit(){
                $request = new grequest();  
		$id=$request->getParam('id');
		if(!$id){
			$this->error('管理员不存在');
		}
		$adminuser = $this->load("adminuser");
		$data = $adminuser->getOne($id);
		$rolemodel = $this->load("role");
		$RoleCache = $rolemodel->getList();
		$RoleCaches = ArrData($RoleCache,'rid');

		$this->render('edit',array('data'=>$data,
		'RoleCache'=>$RoleCaches,
		));
	}


	/**
	 * 显示 编辑/添加 地区页面
	*/
	public function actionDoEdit(){
        $request = new grequest();
		$id=$request->getParam('id',0);
		if(!$id){
			$this->error('管理员不存在');
		}
		$data['password']   = $request->getParam('password')?htmlspecialchars(trim($request->getParam('password'))):'';
		$add_data['realname']   = $request->getParam('realname')?htmlspecialchars(trim($request->getParam('realname'))):'';
		$add_data['email']      = $request->getParam('email')?htmlspecialchars(trim($request->getParam('email'))):'';
		$add_data['phone']      = $request->getParam('phone')?htmlspecialchars(trim($request->getParam('phone'))):'';
		$add_data['role_id']     = $request->getParam('role_id')?htmlspecialchars(trim($request->getParam('role_id'))):'';
		$add_data['status']     = $request->getParam('status')?htmlspecialchars(trim($request->getParam('status'))):'';
		if(trim($data['password'])){
		    $add_data['password']=md5($data['password']);
		}
		$adminuser=  $this->load('adminuser');
		$bool = $adminuser->edit($id,$add_data);
		if($bool===false){
			$this->error('修改用户信息失败');
		}else{
			$this->Success('操作成功',$this->url('list',null,'admin'));
		}
	}

	/**
	 * 删除
	*/
	public function actionDelete(){
		$request = new grequest();
		$id = $request->getParam('id');
		$adminuser= $this->load("adminuser");
		if($adminuser->del($id)){
            IS_AJAX && ajaxReturns(1,'操作成功',0);
		}else{
            IS_AJAX && ajaxReturns(0,'操作失败',0);
		}
	}
        
        
	public function actionexistence() {
		$request = new grequest();
		$name = $request->getParam('param');
		$adminuser= $this->load("adminuser");
		$where['username'] = $name;
		$total = $adminuser->getcount($where);
		if($total){
			$result['status'] = 'n';
			$result['info'] = '账号已被注册';
		}else{
			$result['status'] = 'y';
			$result['info'] = '账号可以注册';
		} 
		echo json_encode($result);
	}
	
}








