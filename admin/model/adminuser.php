<?php

/*
  [UCenter] (C)2001-2099 Comsenz Inc.
  This is NOT a freeware, use is subject to license terms

  $Id: user.php 1078 2011-03-30 02:00:29Z monkey $
 */
!defined('IN_UC') && exit('Access Denied');
/*
 * 后台用户管理
 */

class adminusermodel {

    private $tablename = 'admin_user';

    //后台登录
    function getUser($where) {
        $row = init_db()->createCommand()->select('*')
                ->from($this->tablename)
                ->where('username = :username and password =:password', array(':username' => $where['username'], ':password' => md5($where['password'])))
                ->queryRow();
        return $row;
    }

    //设置帐号登录信息
    function setUser($where, $id) {
        $row = init_db()->createCommand()->update($this->tablename, $where, 'id=:id', array(':id' => $id));
        return $row;
    }

    function insert($paper) {
        init_db()->createCommand()->insert($this->tablename, $paper);
        return init_db()->getLastInsertID();
    }

    function edit($id, $paper) {
        return init_db()->createCommand()->update($this->tablename, $paper, 'id=:id', array(':id' => $id));
    }

    function getcount($where) {
        //拼接where条件
        $con = '';
        $conarr = array();
        $con .= "1=1";
        if ($where['username']) {
            $con .= " and username like :username";
            $conarr[":username"] = "%" . $where['username'];
        }
        if ($where['areaid']) {
            $con .= " and areaid = :areaid ";
            $conarr[":areaid"] = $where['areaid'];
        }
        if ($where['jobid']) {
            $con .= " and typejobid = :jobid ";
            $conarr[":jobid"] = $where['jobid'];
        }
        if ($where['status']) {
            $con .= " and status = :status ";
            $conarr[":status"] = $where['status'];
        }
        if ($where['role_id']) {
            $con .= " and role_id = :role_id ";
            $conarr[":role_id"] = $where['role_id'];
        }
        $row = init_db()->createCommand()->select('count(*) as total')
                ->from($this->tablename)
                ->where($con, $conarr)
                ->queryRow();
        return $row['total'];
    }

    function getlist($where, $limit = 10, $offset = 0) {
        //拼接where条件
        $con = '';
        $conarr = array();
        $con .= "1=1";
        if ($where['username']) {
            $con .= " and username like :username";
            $conarr[":username"] = "%" . $where['username'];
        }
        if ($where['areaid']) {
            $con .= " and areaid = :areaid ";
            $conarr[":areaid"] = $where['areaid'];
        }
        if ($where['jobid']) {
            $con .= " and typejobid = :jobid ";
            $conarr[":jobid"] = $where['jobid'];
        }
        if ($where['status']) {
            $con .= " and status = :status ";
            $conarr[":status"] = $where['status'];
        }
        if ($where['role_id']) {
            $con .= " and role_id = :role_id ";
            $conarr[":role_id"] = $where['role_id'];
        }
        $row = init_db()->createCommand()->select('*')
                ->from($this->tablename)
                ->where($con, $conarr)
                ->limit($limit, $offset)
                ->order('id desc')
                ->queryAll();
        return $row;
    }

    function getOne($id) {
        $row = init_db()->createCommand()->select('*')
                ->from($this->tablename)
                ->where("id = :id", array(':id' => $id))
                ->queryRow();
        return $row;
    }

    function del($uc) {
        return init_db()->createCommand()->delete($this->tablename, 'id=:id', array(':id' => $uc));
    }

    //获取管理简历用户
    function getadminuser($limit = 5, $offset = 0, $where = array(), $filed = "*") {
        //拼接where条件
        $con = '';
        $conarr = array();
        $con .= "1=1";
        if ($where['username']) {
            $con .= " and username like :username";
            $conarr[":username"] = "%" . $where['username'];
        }
        if ($where['realname']) {
            $con .= " and realname like :realname";
            $conarr[":realname"] = "%" . $where['realname'];
        }
        if ($where['phone']) {
            $con .= " and phone = :phone ";
            $conarr[":phone"] = $where['phone'];
        }
        $con .= " and role_id = :role_id ";
        $conarr[":role_id"] = 162869;
        $count = init_db()->createCommand()->select('count(*) as total')
                ->from($this->tablename)
                ->where($con, $conarr)
                ->queryRow();

        $result = init_db()->createCommand()->select('*')
                ->from($this->tablename)
                ->where($con, $conarr)
                ->limit($limit, $offset)
                ->order('id desc')
                ->queryAll();
        $data['list'] = $result;
        $data['count'] = $count['total'];
        return $data;
    }

}

?>