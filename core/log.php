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

require('lockout.php');

$log = "";

function log_append($msg) {
    global $log; // god why
    $log .= "* " . $msg . "\n";
}

function log_spew($nocomment = FALSE) {
    global $log; // just end me
    if (!$nocomment) echo "<!--\n";
    echo $log;
    if (!$nocomment) echo "-->\n";
}

?>