<?php

include 'config.php';
global $config;

// get the path and folder
$CUR_DIR = "";
if(array_key_exists('p', $_GET)) $CUR_DIR = $_GET['p'];
else return 0;
$CUR_FILE = "";
if(array_key_exists('f', $_GET)) $CUR_FILE = $_GET['f'];
else return 0;

// Go and parse
$nfo = $config['root_dir'] . $config['home_dir'] . $CUR_DIR . $CUR_FILE;
$xml = simplexml_load_file($nfo);
$fields = array("title","year","plot","thumb");
$text = '<br><table class="styled-table">';
$text = $text . "\n<thead class='middle'>\n";
$text = $text . '<th colspan="2">*title* (*year*)</th></thead>';
$text = $text . "\n<tr><td height='24px'>Résumé";
$text = $text . '<td rowspan="2" class="middle"><img src="*thumb*" class="nfo-poster">';
$text = $text . "\n<tr><td>*plot*\n";
$text = $text . "\n</table>";
$conv = array();

// echo "<!DOCTYPE html>\n";
// echo '<html><head><link rel="stylesheet" href="' . $config['css'] . '"></head><body>';
// echo "\n";

foreach ($xml->children() as $child)
{
    $child_name = $child->getName();
    if(in_array($child_name, $fields)) //echo "$child<br>\n";
       {
       $conv['*'.$child_name.'*'] = $child;
       }
    $output = strtr($text, $conv);
}

echo "$output\n";
// echo "</body></html>";

?>
