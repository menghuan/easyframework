<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: user.php 1078 2011-03-30 02:00:29Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');
/*
 * 地区管理
 */
class areamodel {
        private $tablename = 'easyframework_area';
        
        function insert($paper){
                init_db()->createCommand()->insert($this->tablename,$paper);
                return init_db()->getLastInsertID();            
        }
        
        function edit($paper,$areaid){
                
                return init_db()->createCommand()->update($this->tablename,$paper,'areaid=:areaid',array(':areaid'=>$areaid));            
        }  

        //根据id获取地区信息
        function getareainfo($areaid){
            $row = init_db()->createCommand()->select('*')
                    ->from($this->tablename)
                    ->where("areaid =:areaid",array(':areaid'=>$areaid))
                    ->limit(1)
                    ->queryRow();
            return $row;
        }


        function deletes($areaid){
           return init_db()->createCommand()->delete($this->tablename,'areaid=:areaid',array(':areaid'=>$areaid));
        }   
        
         //生成缓存
        function generate(){
            $con = ''; $conarr = array();
            $con .= "1=1";
            $con .=" and disabled < :disabled";
            $conarr[":disabled"] = 2;
            $row = init_db()->createCommand()->select('*')
                    ->from($this->tablename)
                    ->where($con,  $conarr)
                    //->order('sort asc,areaid asc')
                    ->limit(1000,0)
                    ->queryAll();
           
            //格式化数组
            $array = array();
            $parent = array();
            $zonelist = array();
            foreach($row as $key => $val){
                if($val['parentid']){
                    $parent[$val['parentid']][$val['areaid']] = $val;
                }else{
                    $array[$val['areaid']] = $val;
                }
                $zonelist[$val['areaid']] = $val;
            }
            foreach($array as $k => $v){
                $array[$k]['parent'] = $parent[$v['areaid']];
            }
            
            $caches['type'] = UC_CACHE_TYPE;
            $caches['expire'] = CACHE_SAVE_MAX_TIME;
            $rtn = S('areacache',$zonelist,$caches);
            S('areatree',$array,$caches);
            //生成地区、省市缓存
            $arealist = $provincelist = array();
            foreach ($zonelist as $zk => $zv){
                if($zv["disabled"]==1){
                    $arealist[] = $zv;
                }
                if($zv['parentid'] == 0 && $zv['code']){
                    $provincelist[] = $zv;
                }
            }
            //排序
            $arealist = multi_array_sort($arealist, 'sort');
            $provincelist = multi_array_sort($provincelist, 'sort');
            foreach ($arealist as $zk => $zv){
                $area[$zv['code']][$zv['areaid']] = $zv['name'];
            }
            foreach ($provincelist as $pk => $pv){
                $province[$pv['code']][$pv['areaid']] = $pv;
            }
            S('arealistcache',$area,$caches);
            S('provincelistcache',$province,$caches);
            if(false != $rtn){
                return true;
            }else{
                return false;
            }
        }
        
        //地区列表
        function getlist(){
            //判断是否存在缓存信息
            $rtn = S('areacache');
			$rtn = multi_array_sort($rtn, 'sort');//排序
            if($rtn){
                return $rtn;
            }else{
                //拼接where条件
                $con = ''; $conarr = array();
                $con .= "1=1";
                $con .=" and disabled < :disabled";
                $conarr[":disabled"] = 2;
                $row = init_db()->createCommand()->select('*')
                        ->from($this->tablename)
                        ->where($con,  $conarr)
                        ->order('sort asc,areaid asc')
                        ->limit(1000,0)
                        ->queryAll();
                $zonelist = array();
                foreach($row as $ka => $va){
                    $zonelist[$va['areaid']] = $va;
                }
                $caches['type'] = UC_CACHE_TYPE;
                $caches['expire'] = CACHE_SAVE_MAX_TIME;
                S('areacache',$zonelist,$caches);
				$zonelist = multi_array_sort($zonelist, 'sort');//排序
                return $zonelist;
            }
        }
}

?>