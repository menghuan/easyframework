<?php
/**
 * 上传文件MODEL(图片、简历附件等)
 */
!defined('IN_UC') && exit('Access Denied');
class uploadfilemodel{
    private $tablename_img = 'easyframework_images';
    private $tablename_attach = 'easyframework_attachment';
    
    /**
     * 根据id获取图片信息
     * @param type $id
     * @param type $field
     * @return type
     */
    function getimagebyid($where,$field = "*"){
        //拼接where条件
        $con = '';
        $conarr = array();
        $con .= "1=1";
        if (!empty($where['id']) && $where['id'] != 0) {
            $con .= " AND id=:id";
            $conarr[":id"] = $where['id'];
        }
        $row = init_db()->createCommand()->select($field)
                ->from($this->tablename_img)
                ->where($con,$conarr)
                ->limit(1)
                ->queryRow();
        return $row;
    }

    /**
     * 根据ids获取对应的图片
     * @param type $ids
     * @return type
     */
    function getimagebyids($idArr,$fields = "*") {
        $rows = init_db()->createCommand()->select($fields)
                ->from($this->tablename_img)
                ->where(array('in', 'id', $idArr))
                ->queryAll();
        return $rows;
    }
    
    /**
     * 根据id获取附件信息
     * @param type $data
     * @param type $field
     * @return type
     */
    function getfileinfo($data, $field="*") {
        $row = init_db()->createCommand()->select($field)
                ->from($this->tablename_attach)
                ->where(array("in", "id", $data))
                ->queryAll();
        return $row;
    }
    
    function getfileinfobyid($where,$field = "*"){
        //拼接where条件
        $con = '';
        $conarr = array();
        $con .= "1=1";
        if (!empty($where['id']) && $where['id'] != 0) {
            $con .= " AND id=:id";
            $conarr[":id"] = $where['id'];
        }
        $row = init_db()->createCommand()->select($field)
                ->from($this->tablename_attach)
                ->where($con,$conarr)
                ->queryRow();
        return $row;
    }
}
?>
