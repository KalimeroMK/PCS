<?php

include_once(__DIR__ . '/../common.php');

$title = isset($_REQUEST['title']) ? urlencode(str_replace('\"', '"', $_REQUEST['title'])) : '';
$short_url = isset($_REQUEST['longurl']) ? googl_short_url($_REQUEST['longurl']) : '';
$sns = $_REQUEST['sns'] ?? '';

if (!$short_url) {
    $short_url = isset($_REQUEST['longurl']) ? urlencode($_REQUEST['longurl']) : '';
}

$title_url = $title.' : '.$short_url;

switch ($sns) {
    case 'facebook' :
        header("Location:http://www.facebook.com/sharer/sharer.php?s=100&u=".$short_url."&p=".$title);
        break;
    case 'twitter' :
        header("Location:https://twitter.com/intent/tweet?text=".$title_url);
        break;
    case 'gplus' :
        header("Location:https://plus.google.com/share?url=".$short_url);
        break;
    default :
        echo 'Error';
}