<?php

// Retrieves the list of directoires and files from $dir
// returns an array, sorted in alphabetical order
function getFileList($dir)
    {
        // array to hold return value
        $retval = [];

        // add trailing slash if missing
        if(substr($dir, -1) != "/") {
            $dir .= "/";
        }

        // open pointer to directory and read list of files
        $d = @dir($dir) or die("getFileList: Failed opening directory {$dir} for reading");
        while(FALSE !== ($entry = $d->read())) {
            // skip hidden files
            if($entry{0} == ".") continue;
            if(is_dir("{$dir}{$entry}")) {
                $retval[] = [
                'fullname' => "{$dir}{$entry}/",
                'name' => "{$entry}/",
                'type' => filetype("{$dir}{$entry}"),
                'size' => 0,
                'lastmod' => filemtime("{$dir}{$entry}"),
                'oliv' => "d"
                ];
            } elseif(is_readable("{$dir}{$entry}")) {
                $retval[] = [
                'fullname' => "{$dir}{$entry}",
                'name' => "{$entry}",
                'type' => mime_content_type("{$dir}{$entry}"),
                'size' => filesize("{$dir}{$entry}"),
                'lastmod' => filemtime("{$dir}{$entry}"),
                'oliv' => "f"
                ];
            }
        }
        $d->close();
        sort($retval);
        return $retval;
    }

function is_video_supported($fname)
{
    global $config;
    $ext = substr($fname, -4);
    foreach ($config['video_extensions'] as &$sext)
    {
        if ($ext == '.'.$sext) return TRUE;
    }
    return FALSE;
}

function human_size($size)
{
    global $config;
    if ($size < 10000)
        return strval($size) . ' ' . $config['ui_bytes'];
    elseif ($size < 1000000)
        return strval((int)($size/1000)) . ' ' . $config['ui_kilobytes'];
    elseif ($size < 10000000000)
        return strval((int)($size/1000000)) . ' ' . $config['ui_megabytes'];
    else
        return strval((int)($size/1000000000)) . ' ' . $config['ui_gigabytes'];
}

function html_dir_header($curdir)
    {
    global $config;
    $c1 = $config['ui_table_fname'];
    $c2 = $config['ui_table_fsize'];
    $c3 = $config['ui_table_ftype'];
    $c4 = $config['ui_table_finfo'];
    echo "<div class='list-films'>";
    echo "<b>$curdir</b>\n";
    echo '<table class="styled-table">';
    echo "<thead><tr><th>$c1<th>$c2<th>$c3<th>$c4</tr></thead>\n<tbody>";
    return 0;
    }

function html_dir_footer()
    {
    echo "</tbody></table>\n</div>\n";
    return 0;
    }

function html_dir_entry_f($fpath, $fname, $fsize)
    {
    global $config;
    $uri_m3u = "make-m3u.php?p=".urlencode($fpath)."&f=".urlencode($fname);
    $link_s = "";
    $link_e = "";
    $description = $config['ui_file'];
    $sz = human_size($fsize);
    $link_nfo = "";
    $uri_nfo = "";
    if (is_video_supported($fname))
    {
        $nfo_fname = detect_nfo($fpath, $fname);
        if ($nfo_fname != "")
        {
           $nfo_data = prepare_nfo_data($nfo_fname, $fpath);
           $link_nfo = '<img src="images/nfo.png" alt="NFO" class="img-icon" onclick="update_nfo_viewer(\'_URI_\', \'nfo-viewer\')" />' . "\n";
           $uri = "make-nfo.php?p=".urlencode($fpath)."&f=".urlencode($nfo_fname);
           $link_nfo = strtr($link_nfo, array("_URI_" => $uri));
        }
        $link_s = "<a href='".$uri_m3u."'><b>";
        $link_e = "</b></a>";
        $description = "<b>".$config['ui_movie']."</b>";
        echo "<tr><td>$link_s$fname$link_e\n<td>$sz\n<td>$description\n<td>$link_nfo</tr>\n";
    }
    return 0;
    }

function html_dir_entry_d($reldir)
   {
       global $config;
       $uri_dir = $_SERVER['PHP_SELF']."?where=".urlencode($reldir);
       echo '<tr><td><a href="' . $uri_dir . '">' . basename($reldir) . '</a>';
       echo '<td>-<td>[ '.$config['ui_directory']." ]<td></tr>\n";
   }

function html_dir_back($reldir)
    {
        global $config;
        // remove ending '/' if exists
        $reldir = trim($reldir, '/');
        // Find LAST occurence
        $prevsubdir = substr($reldir, 0, strrpos($reldir, '/'));
        $uri_dir = $_SERVER['PHP_SELF']."?where=".urlencode($prevsubdir);
        echo "<tr><td><a href='".$uri_dir."'>".$config['ui_folder_back']."</a><td><td>[ ".$config['ui_directory']." ]</tr>\n";
    }


// Display entries from a $list of items (directories and files), in a given path $fpath
function html_dir_entries($list, $fpath)
    {
    global $config;
    $i_max = count($list);
    for ($i=0; $i<$i_max; $i++)
        {
        if ($list[$i]['oliv'] == "d")
            {
            $full_fname = append_path_file($fpath, $list[$i]['name']);
            html_dir_entry_d($full_fname);
            }
        elseif ($list[$i]['oliv'] == "f")
            {
            html_dir_entry_f($fpath, $list[$i]['name'], $list[$i]['size']);
            }
        else
            {
            echo "oops ".$list[$i];
            }
    }
    return 0;
    }


// Prepare div for nfo
function html_nfo_viewer() {
    echo "<div class='nfo-viewer' id='nfo-viewer'><br>(vide)</nfo>";
}


// Update div "nfo-viewer", with $data (no processing in there)
function prepare_nfo_data($nfo_fname, $nfo_fpath)
{
    global $config;
    $base_dir = $config['root_dir'] . $config['home_dir'];
    $nfo_full_name = append_path_file($base_dir . $nfo_fpath, $nfo_fname);
    $xml = simplexml_load_file($nfo_full_name);
    $fields = array("title","year","plot","thumb");
    $text = '<table class="styled-table">';
    $text = $text . "\n<thead class='middle'>\n";
    $text = $text . '<th colspan="2">*title* (*year*)</th></thead>';
    $text = $text . "\n<tr><td height='24px'>Résumé";
    $text = $text . '<td rowspan="2" class="middle"><img src="*thumb*" class="nfo-poster">';
    $text = $text . "\n<tr><td>*plot*\n";
    foreach ($xml->children() as $child)
    {
        $child_name = $child->getName();
        if(in_array($child_name, $fields)) $conv['*'.$child_name.'*'] = $child;
    }
    $output = strtr($text, $conv);
    return $output;
}

// display HTML headers
function page_header()
{
    global $config;
    $title = $config['title'];
    $css = $config['css'];
    $js = $config['js'];
    echo "<!DOCTYPE html>\n<HTML>\n<HEAD>\n<TITLE>$title</TITLE>";
    echo "\n<script type='text/javascript' src='$js'></script>";
    echo "\n<link rel='stylesheet' href='$css'>";
    echo "\n</HEAD><BODY>\n";
    echo "<div class='pgstruct'>\n";
    echo "<div class='titre'><h2 class='middle'>$title</h2></div>\n";
}

function page_footer()
{
    echo "</div>\n</BODY></HTML>";
}

// Get file extension (after last '.')
function get_fname_extension($fname)
{
    $dot_ext = strrchr($fname, '.');
    $ext = ltrim($dot_ext, '.');
    return $ext;
}

// Concatenate a path with a filename
// Checks and takes care of '/' between both
function append_path_file($fpath, $fname)
{
    $add_slash = "";
    if (substr($fpath, -1, 1) != '/') $add_slash = '/';
    return $fpath . $add_slash . $fname;
}

// Detects info file in same directory
// Same name as video, different extesion
// Returns nfo filename without path if it exists, empty string otherwise
function detect_nfo($fpath, $fname_video)
{
    global $config;
    $cur_ext = get_fname_extension($fname_video);
    $next_ext = $config['info_file'];
    $conv = array($cur_ext => $next_ext);
    $fname_nfo = strtr($fname_video, $conv);
    $fname_nfo_full = append_path_file($config['root_dir'].$config['home_dir'].$fpath, $fname_nfo);
    if (file_exists($fname_nfo_full)) return $fname_nfo;
    return "";
}

?>


