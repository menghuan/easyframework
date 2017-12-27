<?php

!defined('IN_UC') && exit('Access Denied');

/**
 * 广告位、广告管理
 */
class advertmodel {

    private $tablename_ad = "easyframework_ad";
    private $tablename_adbo = "easyframework_adboard";

    /**
     * 获取广告位列表数量
     * @return array 
     */
    function getadboardtotal() {
        $command = init_db()->createCommand()
                ->select("count(*) as total")
                ->from($this->tablename_adbo)
                ->where('1 = 1')
                ->limit(1)
                ->queryRow();
        return $command['total'];
    }

    /**
     * 根据id获取广告位信息
     * @param type $where
     * @param type $fields
     * @return type
     */
    function getadboardinfobyid($where, $fields = "*") {
        $command = init_db()->createCommand()
                ->select($fields)
                ->from($this->tablename_adbo)
                ->where("id=:id", array(":id" => $where["id"]))
                ->limit(1)
                ->queryRow();
        return $command;
    }

    /**
     * 获取广告位列表
     * @return array 
     */
    function getadboardlist($limit = 10, $offset = 0, $where = '1=1', $field = '*') {
        $command = init_db()->createCommand()
                ->select($field)
                ->from($this->tablename_adbo)
                ->where($where)
                ->limit($limit, $offset)
                ->queryAll();
        return $command;
    }

    /**
     * 添加广告位
     * @param type $data
     * @return type
     */
    function addadboard($data) {
        init_db()->createCommand()->insert($this->tablename_adbo, $data);
        return init_db()->getLastInsertID();
    }

    /**
     * 修改广告位
     * @param type $data
     * @param type $id
     * @return type
     */
    function editadboard($data, $id) {
        return init_db()->createCommand()->update($this->tablename_adbo, $data, 'id=:id', array(':id' => $id));
    }

    /**
     * 删除广告位
     * @param type $id
     * @return type
     */
    function deleteadboard($id) {
        return init_db()->createCommand()->delete($this->tablename_adbo, 'id=:id', array(':id' => $id));
    }

    /* -------------------------------------------------------------------------------------------------- */

    /**
     * 获取广告列表数量
     * @return array 
     */
    function getadverttotal($where = '') {
        $command = init_db()->createCommand()
                ->select("count(*) as total")
                ->from($this->tablename_ad)
                ->where('1 = 1')
                ->limit(1)
                ->queryRow();
        return $command['total'];
    }

    /**
     * 根据id获取广告信息
     * @param type $where
     * @param type $fields
     * @return type
     */
    function getadvertinfobyid($where, $fields = "*") {
        $command = init_db()->createCommand()
                ->select($fields)
                ->from($this->tablename_ad)
                ->where("id=:id", array(":id" => $where["id"]))
                ->limit(1)
                ->queryRow();
        return $command;
    }

    /**
     * 获取广告列表
     * @return array 
     */
    function getadvertlist($limit = 10, $offset = 0, $order = "id", $field = '*') {
        $command = init_db()->createCommand()
                ->select($field)
                ->from($this->tablename_ad)
                ->where('1 = 1')
                ->limit($limit, $offset)
                ->order($order)
                ->queryAll();
        return $command;
    }

    /**
     * 添加广告
     * @param type $data
     * @return type
     */
    function addadvert($data) {
        init_db()->createCommand()->insert($this->tablename_ad, $data);
        return init_db()->getLastInsertID();
    }

    /**
     * 修改广告
     * @param type $data
     * @param type $id
     * @return type
     */
    function editadvert($data, $id) {
        return init_db()->createCommand()->update($this->tablename_ad, $data, 'id=:id', array(':id' => $id));
    }

    /**
     * 删除广告位
     * @param type $id
     * @return type
     */
    function deleteadvert($id) {
        return init_db()->createCommand()->delete($this->tablename_ad, 'id=:id', array(':id' => $id));
    }

    //生成缓存
    function generate() {
        $con = '';
        $conarr = array();
        $con .= "1=1";
        $con .=" and status = :status";
        $conarr[":status"] = 1;
        $row = init_db()->createCommand()->select('*')
                ->from($this->tablename_ad)
                ->where($con, $conarr)
                ->queryAll();
        $adborardArr = init_db()->createCommand()->select('id,width,height')
                ->from($this->tablename_adbo)
                ->where("status=:status",array(":status"=>1))
                ->queryAll();
        foreach ($adborardArr as $adbk => $adbv){
            $adboard[$adbv['id']] = $adbv;
        }
        //格式化数组
        $zonelist = array();
        foreach ($row as $key => $val) {
            $zonelist[$val['board_id']][] = $val;
        }
        foreach ($zonelist as $zk =>$zv){
            foreach ($zv as $zck => $zcv){
                $zv[$zck]['width'] = $adboard[$zk]['width'];
                $zv[$zck]['height'] = $adboard[$zk]['height'];
            }
            $zonelist[$zk] = $zv;
        }
        $caches['type'] = UC_CACHE_TYPE;
        $caches['expire'] = CACHE_SAVE_MAX_TIME;
        $rtn = S('advertcache', $zonelist, $caches);
        if (false != $rtn) {
            return true;
        } else {
            return false;
        }
    }

}

?>
