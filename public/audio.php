<?php
    $file = "../data/mp3/".$_GET['name'].".mp3";
    $mime_type = mime_content_type($file);
    header('Content-Type: '.$mime_type);
    readfile($file);