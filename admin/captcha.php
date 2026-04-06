<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
$captcha = substr(str_shuffle($chars), 0, 4);

$_SESSION['captcha'] = $captcha;

header('Content-Type: image/png');

$img = imagecreate(120, 40);
$bg = imagecolorallocate($img, 240, 240, 240);
$text = imagecolorallocate($img, 0, 0, 0);

imagestring($img, 5, 30, 10, $captcha, $text);

imagepng($img);
imagedestroy($img);
?>