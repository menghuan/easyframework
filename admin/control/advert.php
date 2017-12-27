<?php
!defined('IN_UC') && exit('Access Denied');
require_once UC_ROOT.'lib/Trees.php';
/**
 * 广告管理
 */
class advert extends base{
    public $path = '';
    function __construct() {
        parent::__construct();
        $this->path = "/home/web/easyframework/web/";
        if (!$this->session('userid')) {
            $this->error("请登录", $this->url('login', null, 'index'));
        }
    }
    
    /**
     * 广告列表
     */
    function actionlist(){
        $request = new grequest();
        $page = $request->getParam('page') ? htmlspecialchars(trim($request->getParam('page'))) : 1;
        $limit = 15;
        $list = array();
        $model = $this->load("advert");
        $total = $model->getadverttotal();
        $list = $model->getadvertlist($limit, ($page - 1) * $limit,"ordid ASC,add_time DESC");
        $pager = $this->page($total, $page, $limit);
        $adbcount = $model->getadboardtotal();
        $adboardlist = $model->getadboardlist($adbcount);
        foreach ($adboardlist as $abk => $abv){
            $adboard[$abv["id"]] = $abv;
        }
        $this->render('list', array(
            'list' => $list,
            'total' => $total,
            'pags' => $pager,
            'adboard'=>$adboard
        ));
    }
    
    /**
     * 添加广告
     */
    function actionadd(){
        $model = $this->load("advert");
        $adboardtotal = $model->getadboardtotal();
        $adboardlist = $model->getadboardlist($adboardtotal);
        $this->render('add',array(
            'adboardlist'=>$adboardlist
        ));
    }
    
    /**
     * 修改广告
     */
    function actionedit(){
        $request = new grequest();
        $id = $request->getParam("id") ? (int) $request->getParam("id") : 0;
        if(0 == $id){
            $this->error("请选择一项后进行操作");exit;
        }
        $model = $this->load("advert");
        $adboardtotal = $model->getadboardtotal();
        $adboardlist = $model->getadboardlist($adboardtotal);//获取广告位信息
        $info = $model->getadvertinfobyid(array("id"=>$id));//获取广告信息
        $this->render("add", array(
            "info"=>$info,
            "adboardlist"=>$adboardlist
        ));
    }
    
    /**
     * 保存广告
     */
    function actionsave(){
        $request = new grequest();
        $id = $request->getParam("id") ? (int) $request->getParam("id") : 0;//广告id
        $name = $request->getParam("name") ? htmlspecialchars($request->getParam("name")) : "";//广告名称
        $url = $request->getParam("url") ? htmlspecialchars($request->getParam("url")) : "";//广告链接
        $content = $request->getParam("content") ? htmlspecialchars($request->getParam("content")) : "";//广告图片地址
        $boardid = $request->getParam("boardid") ? (int) $request->getParam("boardid") : 0;//广告位id
        $type = $request->getParam("type") ? (int) $request->getParam("type") : 0;//广告类型
        $desc = $request->getParam("desc") ? htmlspecialchars($request->getParam("desc")) : "";//广告描述
        $ordid = $request->getParam("ordid") ? (int) $request->getParam("ordid") : 0;//排序
        $status = $request->getParam("status") ? (int) $request->getParam("status") : 0;//状态
        if(empty($name)){
            $this->error("广告名称不能为空，请填写广告名称");exit;
        }
        if($boardid==0){
            $this->error("广告位不能为空，请选择一项");exit;
        }
        $data = array();
        //广告表基础数据
        $data["name"] = $name;
        $data["board_id"] = $boardid;
        $data["type"] = $type;
        $data["url"] = $url;
        if(!empty($content)){
            $data["content"] = $content; 
        }
        $data["desc"] = $desc;
        $data["add_time"] = time();
        $data["ordid"] = $ordid;
        $data["status"] = $status;
        $model = $this->load("advert");
        //创建事务处理
        init_db()->createCommand()->query("START TRANSACTION");
        if(0 == $id){
            $ret = $model->addadvert($data);
        }else{
            $info = $model->getadvertinfobyid(array("id"=>$id));
            $ret = $model->editadvert($data,$id);
        }
        if(false == $ret){
            init_db()->createCommand()->query("ROLLBACK"); //失败回滚
            $this->error("操作失败");
        }
        if($id > 0){
            //修改成功后 把之前图片删除掉 
            if($content != $info['content']){
                $path = $this->path.$info['content'];
                unlink($path);
            }
        }
        init_db()->createCommand()->query("COMMIT"); //成功提交
        $this->success("操作成功",$this->url('list',null,'advert'));
    }
    
    /**
     * 删除广告
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
//        $info = $model->getadvertinfobyid(array("id"=>$id));
//        if(empty($info)){
//            init_db()->createCommand()->query("ROLLBACK"); //失败回滚
//            $this->error("删除的信息不存在");
//        }
//        $ret = $model->deleteadvert($id);
//        if(false == $ret){
//            init_db()->createCommand()->query("ROLLBACK"); //失败回滚
//            $this->error("操作失败");
//        }
//        //成功后删除图片 
//        $path = $this->path.$info['content'];
//        unlink($path);
//        init_db()->createCommand()->query("COMMIT"); //成功提交
//        IS_AJAX && ajaxReturns(1,'操作成功',0);
    }
    
    /**
     * 批量删除
     */
    function actionbatchdelete(){
//        $request = new grequest();
//        $ids = $request->getParam("ids") ? htmlspecialchars($request->getParam("ids")) : "";
//        if(empty($ids)){
//            $this->error("请选择要操作的项目");exit;
//        }
//        $idsArr = explode(',', rtrim($ids,','));
//        $model = $this->load("advert");
//        foreach ($idsArr as $k => $v){
//            $path = '';
//            //创建事务处理
//            init_db()->createCommand()->query("START TRANSACTION");
//            $info = $model->getadvertinfobyid(array("id"=>$v));
//            $ret = $model->deleteadvert($v);
//            if(false == $ret){
//                init_db()->createCommand()->query("ROLLBACK"); //失败回滚
//                $this->error("操作失败");
//            }
//            //成功后删除图片 
//            $path = $this->path.$info['content'];
//            unlink($path);
//            init_db()->createCommand()->query("COMMIT"); //成功提交
//        }
//        IS_AJAX && ajaxReturns(1,'操作成功',0);
    }
    
    /**
     * 文件上传
     */
    function actionuploadfile(){
        if(empty($_FILES)){
            IS_AJAX && ajaxReturns(0, '没有文件了', 0);
        }
        $imguse = "advert/";//图片用途
        $type = array("jpg", "jpeg", "png", "gif"); //设置允许上传文件的类型
        $file = $_FILES["img"];
        $pinfo = pathinfo($file["name"]);
        if(!in_array(strtolower($pinfo["extension"]), $type)) {//判断文件类型   
            $text = implode(",", $type);
            $error = "您只能上传以下类型文件: " . $text;
            IS_AJAX && ajaxReturns(0, $error, 0);
        }
        $picname = $file['name'];
        $picsize = $file['size'];
        if ($picname != "") {
            if ($picsize > 10240000) {
                IS_AJAX && ajaxReturns(0, '图片大小不能超过10M', 0);
            }
            $rand = $this->randomkeys(13);
            $pics = $rand .'.'.$pinfo["extension"];//生成文件名称
            list($y, $m, $d) = explode('-', date('Y-m-d'));//上传路径
            $imgpath = 'uploads/'.$imguse.$y."/".$m."/".$d;
            //$file_folder = str_replace("\admin","\web",UC_ROOT).$imgpath;
            $file_folder = $this->path.$imgpath;
            if(!file_exists($file_folder)){
                $this->dir_create($file_folder);
            }
            $fullname = $file_folder."/".$pics;
            copy($file['tmp_name'],$fullname);
            IS_AJAX && ajaxReturns(1, '图片上传成功',  array("path"=>$imgpath."/".$pics), 0);
        }else{
            IS_AJAX && ajaxReturns(0, '图片上传失败', 0);
        }
    }
    
    
    function actionGenerate(){
        $model = $this->load("advert");
        $return = $model->generate();
        if($return){
             IS_AJAX && ajaxReturns(1,'生成成功',2);
        }else{
             IS_AJAX && ajaxReturns(0,'生成失败',0);
        }
    }
    
    /**
     * 创建目录
     * 
     * @param	string	$path	路径
     * @param	string	$mode	属性
     * @return	string	如果已经存在则返回true，否则为flase
     */
    protected function dir_create($path, $mode = 0777) {
        if (is_dir($path))
            return TRUE;
        $ftp_enable = 0;
        $path = $this->dir_path($path);
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for ($i = 0; $i < $max; $i++) {
            $cur_dir .= $temp[$i] . '/';
            if (@is_dir($cur_dir))
                continue;
            @mkdir($cur_dir, 0777, true);
            @chmod($cur_dir, 0777);
        }
        return is_dir($path);
    }
     /**
     * 转化 \ 为 /
     * 
     * @param	string	$path	路径
     * @return	string	路径
     */
    protected function dir_path($path) {
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/')
            $path = $path . '/';
        return $path;
    }
    /**
     * 生成随机字符串
     * @param type $length
     * @return string
     */
    private function randomkeys($length) {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
        for($i=0; $i<$length; $i++){   
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数   
        }   
        return $key;   
    }  
}
?>
