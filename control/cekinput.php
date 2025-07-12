<?php
function input($data)
{
    $string_replace = array('\'', ';', '[', ']', '{', '}', '|', '^', '~', '<', '>', '+', '&', '$', '#', '!');
    $pesan = str_replace($string_replace, '', $data);
    $data = trim($pesan);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}