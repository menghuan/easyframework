<?php

/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 2.21
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */
  ini_set('display_errors',0);
ini_set('log_errors',0); 
//if($_GET['xhprofanjianliang']==1)
//{
//    xhprof_enable();
//}
require "core/autoload.php";
session_start();
if(!$_SESSION['userid'])
    exit;
$browser = new browser();
$browser->action();
//if($_GET['xhprofanjianliang']==1)
//{
//         
//$xhprof_data = xhprof_disable();
//include_once "xhprof/xhprof_lib/utils/xhprof_lib.php";
//include_once "xhprof/xhprof_lib/utils/xhprof_runs.php";
//$xhprof_runs = new XHProfRuns_Default();
//
////// save the run under a namespace "xhprof_foo"
//$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
//echo "<script>window.open('/xhprof/xhprof_html/index.php?run=".$run_id."&source=xhprof_foo')</script>";
//}
?>