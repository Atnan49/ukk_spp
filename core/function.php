<?php
include '../core/url.php';
include '../core/Controller.php';
$url = new Url();

function getTitle()
{
    global $url ; 
    $titel = $url->getUrl();
    if (count($titel) === 3) {
        $titel = $titel[2].' '.$titel[0];
    } elseif (count($titel) === 2) {
        $titel = $titel[1].' '.$titel[0];
    } else {
        $titel = ucfirst($titel[0]);
    }

    return ucfirst($titel);
}
