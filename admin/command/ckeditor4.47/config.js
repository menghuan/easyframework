/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
		// Define changes to default configuration here. For example:
		// config.language = 'fr';
		// config.uiColor = '#AADC6E';
		// Simplify the dialog windows.
		config.removeDialogTabs = 'image:advanced;link:advanced';
		config.disableNativeSpellChecker = false ;//提速，禁用拼写检查
		config.scayt_autoStartup = false;//提速，禁用拼写检查    
		//下面的配置是调用CKFinder插件实现文件上传管理的
		config.filebrowserBrowseUrl      = '/assets/e/kcfinder/browse.php';
		config.filebrowserImageBrowseUrl = '/assets/e/kcfinder/browse.php?Type=Images';
		config.filebrowserFlashBrowseUrl = '/assets/e/kcfinder/browse.php?Type=Flash';
		config.filebrowserUploadUrl      = '/assets/e/kcfinder/upload.php?command=QuickUpload&type=Files';
		config.filebrowserImageUploadUrl = '/assets/e/kcfinder/upload.php?command=QuickUpload&type=Images';
		config.filebrowserFlashUploadUrl = '/assets/e/kcfinder/upload.php?command=QuickUpload&type=Flash';
		config.enterMode = CKEDITOR.ENTER_BR;
		config.shiftEnterMode = CKEDITOR.ENTER_P;
		config.image_removeLinkByEmptyURL = true;
		if(source_block == 'blockedit'){
			config.startupMode = 'source';
		}

};
