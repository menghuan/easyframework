<?php include $this->defaulttpldir.'/layouts/header.php';?>
<?php  include $this->defaulttpldir.'/layouts/left.php';?>
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php  echo PUBLIC_URL; ?>public/js/kcfind.js"></script>
<link rel="stylesheet" type="text/css" href="<?php  echo PUBLIC_URL; ?>/public/js/jpicker/css/jpicker-1.1.6.min.css" />
<link rel="stylesheet" type="text/css" href="<?php  echo PUBLIC_URL; ?>/public/js/jpicker/jPicker.css" />
<script src="<?php  echo PUBLIC_URL; ?>/public/js/jpicker/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="<?php  echo PUBLIC_URL; ?>/public/js/jpicker/jpicker-1.1.6.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    $(function()
      {
        $.fn.jPicker.defaults.images.clientPath='/public/js/jpicker/images/';
        var LiveCallbackElement = $('#Live'),
            LiveCallbackButton = $('#LiveButton');
     
        $('#Binded').jPicker({window:{title:'Binded Example'},color:{active:new $.jPicker.Color({ahex:'993300ff'})}});
       
        $('#Callbacks').jPicker(
          {window:{title:'Callback Example'}},
          function(color, context)
          {
            var all = color.val('all');
            alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
            $('#Commit').css({ backgroundColor: all && '#' + all.hex || 'transparent' });
          },
          function(color, context)
          {
            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
            var hex = color.val('hex');
            LiveCallbackElement.css({ backgroundColor: hex && '#' + hex || 'transparent' });
          },
          function(color, context)
          {
            alert('"Cancel" Button Clicked');
          });
      
      
      });
  </script>

<script type="text/javascript">        
         $(document).ready(
                   function()
                   {
                       
                       $('#Binded').jPicker();
                    
                   });
               </script>
         
<form method="post" action="<?php echo $this->url('doadd','','position'); ?>" id="info_form" >
<!--right-->
 <div class="right">
 <div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('index','','position'); ?>"   >广告位管理</a></li>
            <li><a href="<?php echo $this->url('add','','poaition'); ?>" class="on">广告添加</a></li>
        </ul>
        <div class="tk_main2">
           <div class="1107-add">          
                
                   <table border="0" cellpadding="1" cellspacing="0" class="1107-table-jbxx">
                       
                 <tr>
                    <td width="10%" class="o-table-l"><strong>分类:</strong></td>
                        <td>
                            <select  class='-in-select' name="column">
								<option value=''>请选择</option>
								<?php foreach($column as $k=>$v){ ?>
									<option value="<?php echo $v['id']; ?>"><?php echo $v['title']; ?></option>
								<?php } ?>
                            </select>
                        </td>
		</tr> 
				<tr>
                    <td width="10%" class="o-table-l"><strong>序号:</strong></td>
                        <td>
                            <input type="text" name="number"  class="-input-text" size="18"/>
                        </td>
		</tr> 
                       
		</tr>
  
                <tr>
                      <td width="10%" class="o-table-l"><strong>标题:</strong></td>
			<td><input type="text" name="title"  class="-input-text" size="18"/></td>
		</tr>
				<tr>
                      <td width="10%" class="o-table-l"><strong>副标题:</strong></td>
			<td><input type="text" name="subtitle"  class="-input-text" size="18"/></td>
		</tr>
  
   
                <tr>  
                        <td width="10%" class="o-table-l"><strong>图片:</strong></td>
			<td><input type="text" class="-input-text" id='pic1' name="image" value="" size=30/><a href="#" class="selectpic" to='pic1'>上传文件</a></td>
		</tr>  
		
		<tr>     <td width="10%" class="o-table-l"><strong>链接:</strong></td>
			<td><input type="text" name="link" class="-input-text" value="" size="18"/></td>
		</tr>
    
                
                <tr> 
                    <td width="10%" class="o-table-l"><strong>背景色:</td>
                <td>
                    
                 <input  class="-input-text" id="Binded" type="text" value="e2ddcf"  name="background"/>
                
		</tr>  
                
                
                <tr>   
                    <td width="10%" class="o-table-l"><strong>课程:</strong></td>
			<td><input type="text" name="course_ids" class="-input-text"  size="18"/>(课程id ','分开)</td>
		</tr>
                <tr>   
                    <td width="10%" class="o-table-l"><strong>顺序:</strong></td>
			<td><input type="text" name="seq" class="-input-text" size="18"/><font>备注：0-9</font></td>
		</tr>
     
            <tr> 
                <td width="10%" class="o-table-l"><strong>状态:</strong></td>
                <td> <label class="-in-label -td-mr40"><input type="radio" name="status" id="status0" value="0"/>禁用
                 <label class="-in-label -td-mr40"><input type="radio" name="status" id="status1" value="1" checked/>开启</td>
            </tr>            
        </table>
               <p class="-z-submit">
                    <input type="submit" class="-c-submit" value="提交" />
                    <input type="button" class="-c-back" value="返回"  onclick="reback()"/>
               </p>
        </div>
    </div>
    </div>
    </div>
    <!--//right_over-->
<?php  include $this->defaulttpldir.'/index/footer.php';?>