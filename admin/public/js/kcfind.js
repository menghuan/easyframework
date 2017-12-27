   $(document).ready(function() {
       window.KCFinder=function(){};
        window.KCFinder.callBackMultipleEditor =function(files) {
            var vvalue="";
             for (var i = 0; i < files.length; i++)
               // vvalue += '<img src="'+files[i]+'">' ;
				
          var oEditor = window.CKEDITOR.instances.content;
                            if ( oEditor.mode == 'wysiwyg' )
                                {
                                 oEditor.insertHtml(vvalue);
                                }
                            else{
                                 alert( 'You must be in WYSIWYG mode!' );
                                }
        };
      $(".selectpic").click(function(){
		  
           to= $(this).attr('to');        
            window.KCFinder.callBackMultiple=function(files) {        
				//textarea.value = "";
				 window.KCFinder.callBackMultiple=false; 
				$("#"+to).val('');
				vvalue='';
				if(files.length==1){
					vvalue=files[0];
				}
				else
				for (var i = 0; i < files.length; i++)
					vvalue += files[i] + "///";
				
				 $("#"+to).val(vvalue);
			};
		window.open('http://'+window.location.host+'/assets/e/kcfinder/browse.php?Type=Images&CKEditor='+to+'&CKEditorFuncNum=4&langCode=zh-cn',
			'kcfinder_multiple', 'status=0, toolbar=0, location=0, menubar=0, ' +
			'directories=0, resizable=1, scrollbars=0, width=800, height=600'
		);
       //  aa=window.showModalDialog('http://192.168.0.60:90/assets/e/kcfinder/browse.php?Type=Images&CKEditor=pic&CKEditorFuncNum=4&langCode=zh-cn');
          
      })
   })
   

