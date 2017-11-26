<?php
/**
 * File  uninstall.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/13 12:07
 */
$sql = " 
 DROP TABLE IF EXISTS awt_enroll_articles;
 DROP TABLE IF EXISTS awt_enroll_activities;
 DROP TABLE IF EXISTS awt_enroll_member;
 DROP TABLE IF EXISTS awt_enroll_activities_logs;
 DROP TABLE IF EXISTS awt_enroll_activity_cate;
 DROP TABLE IF EXISTS awt_enroll_member_group;
 DROP TABLE IF EXISTS awt_enroll_sysset;
 DROP TABLE IF EXISTS awt_enroll_message_template;
 DROP TABLE IF EXISTS awt_enroll_activity_type;
  ";

pdo_query($sql);