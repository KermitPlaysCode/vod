<?php

$ROOT_DIR = $config['root_dir'];
$HOME_DIR = $config['home_dir'];
$CUR_DIR = "";
$CUR_FILE = "";

if(array_key_exists('p', $_GET)) $CUR_DIR = $_GET['p'];
else return 0;
if(array_key_exists('f', $_GET)) $CUR_FILE = $_GET['f'];
else return 0;

$uri = "https://".$_SERVER['SERVER_NAME']."/".$HOME_DIR.$CUR_DIR.$CUR_FILE;

header('Content-type: application/x-mpegurl');
header('Content-Disposition: attachment; filename="movie.m3u"');
echo "#EXTM3U\n";
echo "#EXTINF:-1,$CUR_FILE\n";
echo $uri;

?>
