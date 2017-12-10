<?php
/**
 * 进程锁
 * User: junxxx
 * Mail: hprjunxxx@gmail.com
 * Date: 2017/10/24
 * FILE: Process.lock.class.php
 *
 */
class ProcessLock
{
    public static $LOCK;

    /**
     * 进程加锁   独占非阻塞
     * @param string $lfile 文件名
     * @return bool
    */
    public function getLock($lfile)
    {
        $lock = CONSOLEPATH;
        $lock = rtrim($lock, '/');

        if (!is_dir("$lock/flock"))
        {
            mkdir("$lock/flock", 0777, true);
        }

        $fileName = "$lock/flock/$lfile";
        self::$LOCK = fopen($fileName, 'w+');
        return flock(self::$LOCK, LOCK_EX | LOCK_NB);
    }

    /**
     * 解锁
     * @return null
    */
    public static function unLock()
    {
        fclose(self::$LOCK);
        self::$LOCK = null;
    }

    /**
     * __destruct
    */
    public function __destruct()
    {
        if (self::$LOCK)
        {
            self::unLock();
        }
    }

}