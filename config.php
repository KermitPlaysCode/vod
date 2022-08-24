<?php

$config = [
    'root_dir' => '/var/www-secure/',
    'home_dir' => 'files/',
    'title' => 'VoD',
    'css' => 'css/style.css',
    'js' => 'js/scripts.js',
    'video_files' => 'mkv,avi,mp4',
    'info_file' => 'nfo',
    # Interface UI language
    'ui_directory' => 'Dossier',
    'ui_file' => 'Fichier',
    'ui_movie' => 'Vidéo',
    'ui_bytes' => 'octets',
    'ui_kilobytes' => 'Ko',
    'ui_megabytes' => 'Mo',
    'ui_gigabytes' => 'Go',
    'ui_table_fname' => 'Fichier / [Dossier]',
    'ui_table_fsize' => 'Taille',
    'ui_table_ftype' => 'Type',
    'ui_table_finfo' => 'NFO',
    'ui_folder_back' => 'Retour au dossier précedent',
    'ui_iframe_nfo_name' => 'nfoviewer'
    ];

$config['video_extensions'] = explode(',', $config['video_files']);

?>
