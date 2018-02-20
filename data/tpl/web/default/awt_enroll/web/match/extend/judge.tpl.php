<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group notice">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">随场裁判</label>
    <div class="col-sm-4">
        <input type='hidden' id='noticeopenid' name='noticeopenid' value="<?php  echo $item['noticeopenid'];?>" />
        <div class='input-group'>
            <input type="text" name="judger" maxlength="30" value="<?php  if(!empty($judger)) { ?><?php  echo $judger['nickname'];?>/<?php  echo $judger['realname'];?>/<?php  echo $judger['mobile'];?><?php  } ?>" id="judger" class="form-control" readonly />
            <input type="hidden" name="judgeopenid" id="judgeopenid" value="<?php  if(!empty($judger)) { ?><?php  echo $judger['judgeopenid'];?><?php  } ?>">
            <div class='input-group-btn'>
                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-judge').modal();">选择驻场裁判</button>
                <button class="btn btn-danger" type="button" onclick="$('#noticeopenid').val('');$('#judger').val('');$('#judgeravatar').hide()">清除选择</button>
            </div>
        </div>
        <span id="judgeravatar" class='help-block' <?php  if(empty($judger)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $judger['avatar'];?>"/></span>
        <span class="help-block">驻场裁判才有权限对当场比赛比分进行录入</span>
        <div id="modal-module-menus-judge"  class="modal fade" tabindex="-1">
            <div class="modal-dialog" style='width: 920px;'>
                <div class="modal-content">
                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择驻场裁判</h3></div>
                    <div class="modal-body" >
                        <div class="row">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd-judge" placeholder="请输入粉丝昵称/姓名/手机号" />
                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_judgers();">搜索</button></span>
                            </div>
                        </div>
                        <div id="module-menus-judge" style="padding-top:5px;"></div>
                    </div>
                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                </div>

            </div>
        </div>
    </div>
</div>
<script language='javascript'>
    function search_judgers() {
        if( $.trim($('#search-kwd-judge').val())==''){
            Tip.focus('#search-kwd-judge','请输入关键词');
            return;
        }
        $("#module-menus-judge").html("正在搜索....")
        $.get('<?php  echo $this->createWebUrl('member/search')?>', {
            keyword: $.trim($('#search-kwd-judge').val())
        }, function(dat){
            $('#module-menus-judge').html(dat);
        });
    }
    function select_judgers(o) {
        $("#judgeopenid").val(o.openid);
        $("#judgeravatar").show();
        $("#judgeravatar").find('img').attr('src',o.avatar);
        $("#judger").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
        $("#modal-module-menus-judge .close").click();
    }

</script>
