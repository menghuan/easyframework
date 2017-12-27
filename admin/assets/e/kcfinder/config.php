<?php

/** This file is part of KCFinder project
  *
  *      @desc Base configuration file
  *   @package KCFinder
  *   @version 2.21
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

// IMPORTANT!!! Do not remove uncommented settings in this file even if
// you are using session configuration.
// See http://kcfinder.sunhater.com/install for setting descriptions
session_start();

$_CONFIG = array(
    'disabled' => false,
    'readonly' => false,
    'denyZipDownload' => true,
    'theme' => "oxygen",
    'uploadURL' => '/upload',
    //'uploadDir' => 'D:\web2\www.easyframework.com\web\upload',
	'uploadDir' => '/home/web/easyframework/web/upload',
    'dirPerms' => 0755,
    'filePerms' => 0644,
    'deniedExts' => "exe com msi bat php cgi pl",
    'types' => array(
        'files'   =>  "",
        'flash'   =>  "swf",
        'images'  =>  "*img",
        // TinyMCE types
        'file'    =>  "",
        'media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm mp4",
        'image'   =>  "*img",
    ),

    'mime_magic' => "",

    'maxImageWidth' => 0,
    'maxImageHeight' => 0,

    'thumbWidth' => 180,
    'thumbHeight' => 102,


    'thumbsDir' => ".thumbs",


    'jpegQuality' => 90,

    'cookieDomain' => "",
    'cookiePath' => "",
    'cookiePrefix' => 'KCFINDER_',

    // THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION CONFIGURATION

    '_check4htaccess' => true,
    //'_tinyMCEPath' => "/tiny_mce",

    '_sessionVar' => false,
    //'_sessionLifetime' => 30,
    //'_sessionDir' => "/full/directory/path",

    //'_sessionDomain' => ".mysite.com",
    //'_sessionPath' => "/my/path",
);

?>
