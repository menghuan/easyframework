<?php include $this->defaulttpldir . '/layouts/header.php'; ?>
<?php include $this->defaulttpldir . '/layouts/left.php'; ?>
<div class="right">
    <div class="right_box">
        <ul class="tk_tit">
            <li><a href="<?php echo $this->url('list', '', 'adboard'); ?>" >广告位管理</a></li>
            <li><a href="<?php echo $this->url('add', '', 'adboard'); ?>" class="on"><?php echo (empty($info)) ? "添加" : "编辑";?>广告位</a></li>
        </ul>
        <div class="tk_main2">
            <p class="1107-tab-add"><span class="hover">广告位信息</span></p>
            <div class="1107-add">
                <form action="<?php echo $this->url('save', '', 'adboard'); ?>" method="post">
                    <input type="hidden" id="id" name="id" value="<?php echo $info["id"];?>">
                    <table border="0" cellpadding="0" cellspacing="0" class="1107-table-jbxx">
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告位名称：</strong></td>
                            <td>
                                <input type="text" name="name" id="name" value="<?php echo $info["name"];?>" class="-input-text" /><span  class="-btx">*</span>
                            </td>
                        </tr> 
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告位尺寸：</strong></td>
                            <td>
                                <div style="margin-left: 10px;">
                                    宽 : <input type="text" name="width" id="width" class="input-text" size="6" value="<?php echo $info["width"];?>"> px&nbsp;&nbsp;&nbsp;&nbsp;
                                    高 : <input type="text" name="height" id="height" class="input-text" size="6" value="<?php echo $info["height"];?>"> px
                                </div>
                            </td>
                        </tr> 
                        <tr>
                            <td width="15%" class="o-table-l"><strong>广告位说明：</strong></td>
                            <td>
                                <textarea rows="4" cols="45" id="description" name="description" style="margin-left: 10px;width: 350px;height: 100px;"><?php echo $info["description"];?></textarea>
                            </td>
                        </tr> 
                        <tr>
                            <td width="15%" class="o-table-l"><strong>是否启用 :</strong></td>
                            <td>
                                <div style="margin-left: 10px;">
                                    <label><input type="radio" name="status" value="1" <?php echo (empty($info) ? "checked='checked'" : ($info['status']==1) ? "checked='checked'" : "");?>> 是</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="status" value="0" <?php echo (empty($info) ? "" : ($info['status']==0) ? "checked='checked'" : "");?>> 否</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <p class="-z-submit">
                        <input type="submit" class="-c-submit" value="提交" />
                    </p>   
                </form>
            </div>
        </div>
    </div>
</div>
<?php include $this->defaulttpldir . '/index/footer.php'; ?>
