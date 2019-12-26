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

session_start();
$_SESSION['log'] = "Beginning log...\n";

define('LOCKOUT', 1);
require('./core/global.php');

function append_session($text) {
    $_SESSION['log'] .= $text;
}

// pass 1: do posts
append_session("Building blog posts first:\n");
$allPosts = $db->getAllPosts();
foreach ($allPosts as $post) {
    $builtPost = $templ->parseUniversal($post, $templ->blogtemp);
    $file = "./output/" . $post['slug'] . ".html";
    
    append_session("*** Building " . $file);
    if(isset($_POST['rebuild-all'])) {
        if (is_file($file)) {
            append_session("...file exists, deleting as requested...");
            unlink($file);
        }
    } else {
        if (is_file($file)) {
            append_session("...file exists, skipping.\n");
            continue;
        }
    }

    append_session("...done\n");

    $fileHandle = fopen($file, "w");
    fwrite($fileHandle, $builtPost);
    fclose($fileHandle);
}

append_session("\n"); // line break for separation

// pass 2: do pages
// TODO: the whole fucking page system

append_session("Process completed!");

header('Location: index.php?msg=buildresult');

?>
