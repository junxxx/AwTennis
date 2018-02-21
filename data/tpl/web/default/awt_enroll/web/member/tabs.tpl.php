<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
    <li <?php  if(($_GPC['p'] == 'list' || empty($_GPC['p']))) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('member')?>">会员管理</a></li>
    <li <?php  if(($_GPC['p'] == 'group')) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('member/group')?>">分组管理</a></li>
</ul>
