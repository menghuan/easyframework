<?php

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: seccode.php 1059 2011-03-01 07:25:09Z monkey $
*/

!defined('IN_UC') && exit('Access Denied');

class seccodec extends base {

	function __construct() {
		$this->control();
	}

	function control() {
            $seccode = $this->authcode();
            
            @header("Expires: -1");
            @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
            @header("Pragma: no-cache");
            include_once UC_ROOT.'lib/seccode.class.php'; 
            $code = new seccode();
            $code->display($seccode);
	}

}

?>