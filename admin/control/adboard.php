<?php
!defined('IN_UC') && exit('Access Denied');
require_once UC_ROOT.'lib/Trees.php';
/**
 * 广告位管理
 */
class adboard extends base{
    function __construct() {
        parent::__construct();
        if (!$this->session('userid')) {
            $this->error("请登录", $this->url('login', null, 'index'));
        }
    }
    
    /**
     * 广告位列表
     */
    function actionlist(){
        $request = new grequest();
        $page = $request->getParam('page') ? htmlspecialchars(trim($request->getParam('page'))) : 1;
        $limit = 15;
        $list = array();
        $model = $this->load("advert");
        $total = $model->getadboardtotal();
        $list = $model->getadboardlist($limit, ($page - 1) * $limit);
        $pager = $this->page($total, $page, $limit);
        $this->render('list', array(
            'list' => $list,
            'total' => $total,
            'pags' => $pager
        ));
    }
    
    /**
     * 添加广告位
     */
    function actionadd(){
         $this->render('add');
    }
    
    /**
     * 修改广告位
     */
    function actionedit(){
        $request = new grequest();
        $id = $request->getParam("id") ? (int) $request->getParam("id") : 0;
        if(0 == $id){
            $this->error("请选择一项后进行操作");exit;
        }
        $model = $this->load("advert");
        $info = $model->getadboardinfobyid(array("id"=>$id));
        $this->render("add", array(
            "info"=>$info
        ));
    }
    
    /**
     * 保存广告位
     */
    function actionsave(){
        $request = new grequest();
        $id = $request->getParam("id") ? (int) $request->getParam("id") : 0;
        $name = $request->getParam("name") ? htmlspecialchars($request->getParam("name")) : "";
        $width = $request->getParam("width") ? (int) $request->getParam("width") : 0;
        $height = $request->getParam("height") ? (int) $request->getParam("height") : 0;
        $description = $request->getParam("description") ? htmlspecialchars($request->getParam("description")) : "";
        $status = $request->getParam("status") ? (int) $request->getParam("status") : 0;
        if(empty($name)){
            $this->error("广告位名称不能为空");exit;
        }
        $data = array();
        //广告位表基础数据
        $data["name"] = $name;
        $data["width"] = $width;
        $data["height"] = $height;
        $data["description"] = $description;
        $data["status"] = $status;
        $model = $this->load("advert");
        //创建事务处理
        init_db()->createCommand()->query("START TRANSACTION");
        if(0 == $id){
            $ret = $model->addadboard($data);
        }else{
            $ret = $model->editadboard($data,$id);
        }
        if(false == $ret){
            init_db()->createCommand()->query("ROLLBACK"); //失败回滚
            $this->error("操作失败");
        }
        init_db()->createCommand()->query("COMMIT"); //成功提交
        $this->success("操作成功",$this->url('list',null,'adboard'));
    }
    
    /**
     * 删除广告位
     */
    function actiondelete(){
//        $request = new grequest();
//        $id = $request->getParam("id") ? (int) $request->getParam("id") : 0;
//        if(0 == $id){
//            $this->error("请选择一项后进行操作");exit;
//        }
//        $model = $this->load("advert");
//        //创建事务处理
//        init_db()->createCommand()->query("START TRANSACTION");
//        $ret = $model->deleteadboard($id);
//        if(false == $ret){
//            init_db()->createCommand()->query("ROLLBACK"); //失败回滚
//            $this->error("操作失败");
//        }
//        init_db()->createCommand()->query("COMMIT"); //成功提交
//        IS_AJAX && ajaxReturns(1,'操作成功',0);
    }
}
?>
