<?php
$filename = '/home/test/logs/filetest.log';
$date = date("Y-m-d H:i:s",time());
file_put_contents($filename,$date."\n\r",8);
echo $date."\n";
