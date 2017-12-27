<?php
!defined('IN_UC') && exit('Access Denied');
/*
 * 后台角色管理
 */
class rolemodel
{
	
	private $tablename="admin_roles";
        private $tablename_pe="admin_permissions";
        /**
	 * 获取列表数量
	 * @return array 
	 */
	 function getotal($where=''){
		$connection=init_db(); 
		$command=$connection->createCommand()
                        ->select("count(*) as total")
                        ->from($this->tablename)
                        ->where('1 = 1')
                        ->queryRow();
		return $command['total']; 
	}
        
        
	/**
	 * 获取列表
	 * @return array 
	 */
	 function getList($where='',$field='*'){
		$connection=init_db(); 
		$command=$connection->createCommand()
                        ->select($field)
                        ->from($this->tablename)
                        ->where('1 = 1')
                        ->queryAll();
		return $command; 
	}

	/**
	 * 获取列表
	 * @return array 
	 */
	 function getOne($id='',$field='*'){
		$connection=init_db(); 
		$command=$connection->createCommand()
                        ->select($field)
                        ->from($this->tablename)
                        ->where("id = :id",array(":id"=>$id))
                        ->queryRow();
		return $command; 
	}
        
        /**
	 * 获取角色信息
	 * @return array 
	 */
	 function getRole($rid='',$field='*'){
		$connection=init_db(); 
		$command=$connection->createCommand()
                        ->select($field)
                        ->from($this->tablename)
                        ->where("rid = :rid",array(":rid"=>$rid))
                        ->queryRow();
		return $command; 
	}

	/**
	 * 获取列表
	 * @return array 
	 */
	 function getOnepe($id='',$field='perm'){
		$connection = init_db(); 
		$command = $connection->createCommand()
                        ->select($field)
                        ->from($this->tablename_pe)
                        ->where("rid = :rid",array(":rid"=>$id))
                        ->queryRow();
		$perm = unserialize($command['perm']); 
                foreach($perm as $v){
                    $perms[$v] = true;
                }
                return $perms;
	}
	 function addData($data,$perm){
		$connection=init_db(); 
		$connection->createCommand()->insert($this->tablename,$data);
                $connection->createCommand()->insert($this->tablename_pe,$perm);
                return init_db()->getLastInsertID();  
	 }

	/**
	 * 编辑
	 * @return boolen  
	 */
         
	 function edit($id,$data,$rid,$perm){
                $bool = init_db()->createCommand()->update($this->tablename,$data,'id=:id',array(':id'=>$id));
                init_db()->createCommand()->update($this->tablename_pe,$perm,'rid=:rid',array(':rid'=>$rid));
		return $bool;
	}
        
        function deletes($id,$rid) {
                init_db()->createCommand()->delete($this->tablename,'id=:id',array(':id'=>$id));
                init_db()->createCommand()->delete($this->tablename_pe,'rid=:rid',array(':rid'=>$rid));
                return 1;
        }
        
        function SaveData($data,$where) {
                $connection=init_db();
                $command=$connection->createCommand()->update($this->tablename,$data,$where);
                return $command;
       }
}