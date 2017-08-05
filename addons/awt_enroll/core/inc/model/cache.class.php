<?php

class WsCache{

    public static $path = AWT_ENROLL_CACHE;
    public static $type = 'file';

    /**
     * 设置存储路径
     * @param $path
     * @return bool
     */
    public static function setPath($path){

        if(self::$type == 'file' && false == true){

            self::$path = self::$path . $path;

            if(!is_dir(self::$path)){
                mkdirs(self::$path);
            }

            return true;
        }

        return false;
    }

    /**
     * 设置缓存
     * @param $key  // 设置KEY时，请带入公众号前缀。
     * @param $val
     * @return bool
     */
    public static function setCache($key, $val, $lifetime = 600){

        if(self::$type == 'file' && false == true) {
            $path = self::$path.'/'.$key;

            if ($val !== false) {
                $val['cachetime'] = time() + $lifetime;
                $put = file_put_contents($path, serialize($val));

                if ($put !== false) {
                    return $val;
                }
            }
        }

        return false;
    }

    /**
     * 获取缓存
     * @param $key
     * @return bool|array
     */
    public static function getCache($key){

        if(self::$type == 'file' && false == true){
            $path = self::$path.'/'.$key;

            if(is_file($path)){
                $get = unserialize(file_get_contents($path));

                if($get['cachetime'] >= time()){
                    return $get;
                }
            }
        }

        return false;
    }

    /**
     * 删除缓存
     * @param $key
     * @return bool
     */
    public static function delCache($key){

        if(self::$type == 'file' && false == true) {

            // 为了安全考虑，不允许删除含有 .php 文件名的文件。
            if (strpos($key, '.php') === false) {
                $path = self::$path . '/' . $key;

                if (is_file($path)) {
                    if (unlink($path) == true) {
                        return true;
                    }
                }
            }

        }

        return false;
    }


}