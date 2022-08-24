<?php

include 'config.php';
include 'functions.php';
global $config;

$CUR_DIR = "";

if(array_key_exists('where', $_GET))
   {
       $CUR_DIR = $_GET['where'];
   }


// Go and display
page_header();

// list dir
$dirlist = getFileList($config['root_dir'].$config['home_dir'].$CUR_DIR);

// Display nicely the content
html_dir_header($CUR_DIR);
if ($CUR_DIR != "") html_dir_back($CUR_DIR);
html_dir_entries($dirlist, $CUR_DIR);
html_dir_footer();

// Now the NFO viewer
html_nfo_viewer();

// Close the display
page_footer();
?>
