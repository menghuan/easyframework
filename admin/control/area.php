<?php
!defined('IN_UC') && exit('Access Denied');
require_once UC_ROOT.'lib/Trees.php';
/*
 * 地区管理
 */
class area extends base {
        public $arer;
        function __construct() {
            parent::__construct();
            if(!$this->session('userid')){
                 $this->error("请登录",$this->url('login',null,'index'));
            }
	}
        
        //地区列表
	function actionindex() {
            header("Content-type: text/html; charset=utf-8");
            $request = new grequest();
            $area = $this->load('area');
            $array = array();
            $list = $area->getlist();
            $tree = new trees();   
            $tree->icon = array('&nbsp;&nbsp;│ ','&nbsp;&nbsp;├─ ','&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            foreach($list as $val){
                $val['pid'] = $val['parentid'];
                $val['id'] = $val['areaid'];
                $val['codes'] = $val['code'] == 5 ? '西部、西北地区' : ($val['code'] == 4 ? '西南、东南地区' : ($val['code'] == 3 ? '东北、华北地区' :($val['code'] == 2 ? '华东、华中地区' :($val['code'] == 1 ? '求职最热门地区':''))));             
                $val['str_manage'] = 
                '<a href="javascript:;" class="J_showdialog" data-acttype="ajax" data-uri="'.$this->url('addarea',array('id'=>$val['areaid']),'area').'" data-type="2" data-title="添加子地区" data-id="add" data-width="700" data-height="250">添加子地区</a> |
                <a href="javascript:;" class="J_showdialog" data-acttype="ajax" data-uri="'.$this->url('editarea',array('areaid'=>$val['areaid']),'area').'" data-type="2" data-title="修改 - '. $val['name'] .'" data-id="edit" data-width="700" data-height="250">修改</a> |
                <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="'.$this->url('delarea',array('areaid'=>$val['areaid']),'area').'" data-true=1 data-msg="确定要删除吗？">删除</a>';
                $array[] = $val;
            }
            $str  = "<tr id='tr_\$id'>
                    <td align='center'  height='36'>\$areaid</td>
                    <td align='left' height='36'>\$spacer<span data-tdtype='edit' data-field='name' data-id='\$id' class='tdedit'>\$name</span></td>
                    <td align='center'  height='36'>\$parentid</td>
                    <td align='center'  height='36'>\$codes</td>
                    <td align='center'  height='36'>\$disabled</td>
                    <td align='center'  height='36'>\$sort</td>
                    <td align='center'  height='36'>\$str_manage</td>
                    </tr>";//<input type='checkbox' value='\$areaid' class='J_checkitem'>
            $tree->init($array);
            $menu_list = $tree->get_tree(0, $str);
            $this->render('list',array('list'=>$menu_list,'total'=>count($list)));
        }

        function actionGenerate(){
            $area = $this->load('area');
            $return = $area->generate();
            if($return){
                 IS_AJAX && ajaxReturns(1,'生成成功',2);
            }else{
                 IS_AJAX && ajaxReturns(0,'生成失败',0);
            }
        }
        
        //添加地区
        function actionaddarea(){
            header("Content-type: text/html; charset=utf-8");
            $request = new grequest();
            $sid = $request->getParam('id') ? (int)$request->getParam('id') : '0';
            $tree = new trees();
            $tree->icon = array('&nbsp;│ ','&nbsp;├─ ','&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $area = $this->load('area');
            $result = $area->getlist();
            
            $array = array();
            foreach($result as $r) {
                $r['pid'] = $r['parentid'];
                $r['id'] = $r['areaid'];
                $r['selected'] = $r['id'] == $sid ? 'selected' : '';
                $array[] = $r;
            }
            $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $select_menus = $tree->get_tree(0, $str);
            $response = $this->renderPartial('add',array('select_menus'=>$select_menus,'sid'=>$sid));
            IS_AJAX && ajaxReturns(1, '', $response);
        }
    
        //添加地区
        function actiondoadd(){
            $request = new grequest();
            $area = $this->load('area');
            $parentid = $request->getParam('parentid') ? (int)$request->getParam('parentid') : '0';
            $name = $request->getParam('name') ? trim($request->getParam('name')) : '';
            $disabled = $request->getParam('disabled') ? (int)$request->getParam('disabled') : '0';
            $code = $request->getParam('code') ? (int)$request->getParam('code') : '0';
            $sort = $request->getParam('sort') ? (int)$request->getParam('sort') : '0';
            if (!$name) {
               IS_AJAX && ajaxReturns(0,'地区名称不能为空',0);
            }
            $data = array();
            $data['parentid'] = $parentid;
            $data['name'] = $name;
            $data['disabled'] = $disabled;
            $data['code'] = $code;
            $data['sort'] = $sort;
            if ($lastid = $area->insert($data)) {
                    IS_AJAX && ajaxReturns(1,'添加成功',1);
            } else {
                    IS_AJAX && ajaxReturns(0,'添加失败',0);
            }
            
        }
        
        //编辑地区
        function actioneditarea(){
            header("Content-type: text/html; charset=utf-8");
            $request = new grequest();
            $areaid = $request->getParam('areaid') ? (int)$request->getParam('areaid') : '0';
            $tree = new trees();
            $tree->icon = array('&nbsp;│ ','&nbsp;├─ ','&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $area = $this->load('area');
            $result = $area->getlist();
            $areainfo = $area->getareainfo($areaid);
            $sid = $areainfo['parentid'];
            $array = array();
            foreach($result as $r) {
                $r['pid'] = $r['parentid'];
                $r['id'] = $r['areaid'];
                $r['selected'] = $r['id'] == $sid ? 'selected' : '';
                $array[] = $r;
            }
            $str  = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $select_menus = $tree->get_tree(0, $str);
            $response = $this->renderPartial('edit',array('select_menus'=>$select_menus,'areainfo'=>$areainfo,'sid'=>$sid));
            IS_AJAX && ajaxReturns(1, '', $response);
        }
        
        //修改地区
        function actiondoedit(){
            $request = new grequest();
            $area = $this->load('area');
            $areaid = $request->getParam('areaid') ? (int)$request->getParam('areaid') : '0';
            $parentid = $request->getParam('parentid') ? (int)$request->getParam('parentid') : '0';
            $name = $request->getParam('name') ? trim($request->getParam('name')) : '';
            $disabled = $request->getParam('disabled') ? (int)$request->getParam('disabled') : '0';
            $code = $request->getParam('code') ? (int)$request->getParam('code') : '0';
            $sort = $request->getParam('sort') ? (int)$request->getParam('sort') : '0';
            if (!$name) {
               IS_AJAX && ajaxReturns(0,'地区名称不能为空',0);
            }
            if(!$areaid){
                IS_AJAX && ajaxReturns(0,'修改失败，请重试',0);
            }
            $data = array();
            $data['parentid'] = $parentid;
            $data['name'] = $name;
            $data['disabled'] = $disabled;
            $data['code'] = $code;
            $data['sort'] = $sort;
            if ($lastid = $area->edit($data,$areaid)) {
                    IS_AJAX && ajaxReturns(1,'编辑成功',1);
            } else {
                    IS_AJAX && ajaxReturns(0,'编辑失败',0);
            }
            
        }


        function actiondelarea(){
            $request = new grequest();
            $areaid = $request->getParam('areaid') ? (int)$request->getParam('areaid') : '0';
            $area = $this->load('area');
            $areainfo = $area->getareainfo($areaid);
            if(empty($areainfo)){
                    IS_AJAX && ajaxReturns(0, '信息获取失败');
            }
            $lastid = $area->deletes($areaid);
            if($lastid){
                     IS_AJAX && ajaxReturns(1, '删除成功');
            }else{
                     IS_AJAX && ajaxReturns(0, '删除失败');
            }
        }
}
?>