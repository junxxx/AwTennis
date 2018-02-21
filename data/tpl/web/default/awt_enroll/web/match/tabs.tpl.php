<?php defined('IN_IA') or exit('Access Denied');?><ul class="nav nav-tabs">
    <li <?php  if(($_GPC['p'] == 'match' || empty($_GPC['p']))) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('match')?>">活动管理</a></li>
    <li <?php  if(($_GPC['p'] == 'category')) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('match/category')?>">活动分类管理</a></li>
    <li <?php  if(($_GPC['p'] == 'type')) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('match/type')?>">活动类型管理</a></li>
</ul>
