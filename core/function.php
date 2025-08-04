<?php
include '../core/url.php';
include '../core/Controller.php';
include '../core/BaseModel.php';
$url = new Url();
$db = new BaseModel();

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

function urlTo($to)
{
    return 'http://localhost/ukk_spp'.$to;
}

function checkIsNotLogin()
{
    if (!isset($_SESSION['LOGIN']) || $_SESSION['LOGIN'] !== true) {
        header("location: http://localhost/ukk_spp/login");
        exit();
    }
}
function createCookie($username, $data)
{
    global $db;
    $remember = hash('sha256', $username);
    $db->mysqli->query("UPDATE users SET remember_token = '$remember' WHERE username = '$username'");
    setcookie('key', $remember, time() + 36000 * 24, '/');
}