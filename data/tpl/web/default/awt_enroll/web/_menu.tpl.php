<?php defined('IN_IA') or exit('Access Denied');?><style type='text/css'>
    .topmenu { position: relative; width:100%;float:left;background:#efefef;margin-bottom:20px;}
    .topmenu ul li a { color:#000}
    .topmenu .dropdown-menu li a { color:#666}
    .topmenu ul li { content:'|'}
</style>
<div class='topmenu'>
    <ul class="nav navbar-nav">
        <li>
            <a href="<?php  echo url('home/welcome/ext',array('m'=>'awt_enroll'))?>"><i class='fa fa-home'></i> <?php  echo $this->module['title']?></a>
        </li>
        <li class="dropdown">
            <a href='javascript:;' class="dropdown-toggle" data-toggle="dropdown"><i class='fa fa-futbol-o'></i> 活动管理 <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createWebUrl('match/match')?>">活动管理</a></li>
                <li><a href="<?php  echo $this->createWebUrl('match/category')?>">活动分类管理</a></li>
                <li><a href="<?php  echo $this->createWebUrl('match/type')?>">活动类型管理</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href='javascript:;' class="dropdown-toggle" data-toggle="dropdown"><i class='fa fa-users'></i> 会员管理 <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createWebUrl('member/list')?>">会员管理</a></li>
                <li><a href="<?php  echo $this->createWebUrl('member/group')?>">分组管理</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href='javascript:;' class="dropdown-toggle" data-toggle="dropdown"><i class='fa fa-bars'></i> 活动报名管理 <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createWebUrl('enroll/list')?>">报名管理</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href='javascript:;' class="dropdown-toggle" data-toggle="dropdown"><i class='fa fa-file-word-o'></i> 文章管理 <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createWebUrl('article/list')?>">文章管理</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href='javascript:;' class="dropdown-toggle" data-toggle="dropdown"><i class='fa fa-cog'></i> 系统设置 <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="<?php  echo $this->createWebUrl('setting/index')?>">系统设置</a></li>
            </ul>
        </li>
    </ul>
</div>