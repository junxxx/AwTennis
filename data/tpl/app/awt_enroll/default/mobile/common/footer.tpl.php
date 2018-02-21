<?php defined('IN_IA') or exit('Access Denied');?><nav class="mui-bar mui-bar-tab">
    <a href="<?php  echo $this->createMobileUrl('enroll/list')?>" class="mui-tab-item " id="activity">
        <span class="mui-icon mui-icon-home"></span>
        <span class="mui-tab-label">活动</span>
    </a>
    <a href="<?php  echo $this->createMobileUrl('ranking/ranking')?>" class="mui-tab-item" id="ranking">
        <span class="mui-icon mui-icon-email"></span>
        <span class="mui-tab-label">排行榜</span>
    </a>
    <a href="<?php  echo $this->createMobileUrl('member/center')?>" class="mui-tab-item mui-active" id="me">
        <span class="mui-icon mui-icon-contact mui-icon-icon-contact-filled"></span>
        <span class="mui-tab-label">我</span>
    </a>
</nav>
<script language="javascript">
    mui('body').on('tap','a',function(){document.location.href=this.href;});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer_base', TEMPLATE_INCLUDEPATH)) : (include template('common/footer_base', TEMPLATE_INCLUDEPATH));?>