<?php
error_reporting(0);
$waarde = file_get_contents('/var/www/html/HitData/live_watt.txt');
echo ($waarde === false || empty($waarde)) ? "0" : trim($waarde);
?>
