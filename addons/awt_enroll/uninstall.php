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
  ";

pdo_query($sql);