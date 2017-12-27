<?php
/**
 * 基础数据
 */
class basicdatamodel{
    private $tablename = 'easyframework_search_setting';
    /**
     * 新增基础数据
     * @return type
     */
    function insert($paper){
        init_db()->createCommand()->insert($this->tablename,$paper);
        return init_db()->getLastInsertID();            
    }
    /**
     * 修改基础数据
     * @return type
     */
    function edit($paper,$id){
        return init_db()->createCommand()->update($this->tablename,$paper,'id=:id',array(':id'=>$id));            
    }
    /**
     * 删除基础数据
     * @return type
     */
    function del($id){
        return init_db()->createCommand()->delete($this->tablename,'id=:id',array(':id'=>$id));
    }
    
    /**
     * 获取基础信息
     */
    function getlist(){
        $row = init_db()->createCommand()->select('*')
                ->from($this->tablename)
                ->order('id desc')
                ->queryAll();
            return $row;
    }
    
    /*
     * 获取单个基础信息
     */
    function getRows($where = array()){
        //拼接where条件
        $con = ''; $conarr = array();
        $con .= "1=1";
        //名称类型
        if(!empty($where['name'])){
            $con .= " and name = :name";
            $conarr[":name"] = $where['name'];
        }
        $row = init_db()->createCommand()->select('*')
                ->from($this->tablename)
                ->where($con,  $conarr)
                ->queryRow();
        return $row;
    }
    
}
?>
