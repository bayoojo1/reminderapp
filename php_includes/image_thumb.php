<?php
function img_thumb($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $src_x = ($w_orig / 2) - ($w / 2);
    $src_y = ($h_orig / 2) - ($h / 2);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){
    $img = imagecreatefrompng($target);
    } else {
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
    if ($ext == "gif"){
        imagegif($tci, $newcopy);
    } else if($ext =="png"){
        imagepng($tci, $newcopy);
    } else {
        imagejpeg($tci, $newcopy, 84);
    }
}
