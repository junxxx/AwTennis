<?php

if (!defined('IN_IA')) {
    die('Access Denied');
}
define('AWT_ENROLL_DEBUG', false);
!defined('AWT_ENROLL_PATH') && define('AWT_ENROLL_PATH', IA_ROOT . '/addons/awt_enroll/');
!defined('AWT_ENROLL_CORE') && define('AWT_ENROLL_CORE', AWT_ENROLL_PATH . 'core/');
!defined('AWT_ENROLL_PLUGIN') && define('AWT_ENROLL_PLUGIN', AWT_ENROLL_PATH . 'plugin/');
!defined('AWT_ENROLL_INC') && define('AWT_ENROLL_INC', AWT_ENROLL_CORE . 'inc/');
!defined('AWT_ENROLL_INC_MODEL') && define('AWT_ENROLL_INC_MODEL', AWT_ENROLL_INC . 'model/');
!defined('AWT_ENROLL_URL') && define('AWT_ENROLL_URL', $_W['siteroot'] . 'addons/awt_enroll/');
!defined('AWT_ENROLL_STATIC') && define('AWT_ENROLL_STATIC', AWT_ENROLL_URL . 'static/');
!defined('AWT_ENROLL_CACHE') && define('AWT_ENROLL_CACHE', AWT_ENROLL_CACHE . 'data/cache/');
!defined('AWT_ENROLL_PREFIX') && define('AWT_ENROLL_PREFIX', 'awt_enroll_');
