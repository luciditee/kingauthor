<?php

/*
 * KingAuthor -- a shitty static template-based blog
 * system, written for no reason other than "why not."
 * 
 * This system is licensed under the MIT license.
 * 
 * Copyright 2019-2020 William Preston / Turnip Collective
 * 
 */

define('LOCKOUT', 1);
require('./core/global.php');

function build_slug($title) {
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxy-";
    $pattern = "/[^".preg_quote($chars, "/")."]/";
    return preg_replace($pattern, "-", $title);
}

$postData = array(
    "tags" => $_POST['cat-select'],
    "id" => (isset($_GET['update']) ? $_GET['update'] : -1),
    "author" => $_POST['post-author'],
    "content" => $_POST['post-content'],
    "title" => $_POST['post-title'],
    "date" => date(KA_DATESTAMP_FMT),
    "slug" => build_slug($_POST['post-title']),
    "hidden" => ($_POST['public-mode'] == "on" ? 0 : 1)
);

if ($postData['id'] != -1) {
    // Update a post
    $db->updatePost($postData);
} else {
    // Insert new post
    $db->insertPost($postData);
}

header('Location: index.php?msg=posted');

?>