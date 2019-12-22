<?php

/*
 * KingAuthor -- a shitty static template-based blog
 * system, written for no reason other than "why not."
 * 
 * This system is proprietary and not really meant for
 * external use. If you've gained access to this code,
 * congratulations, let me know how so I can unfuck my
 * security schema.
 * 
 * Copyright 2019-2020 William Preston / Turnip Collective
 * 
 * Please don't redistribute this code.
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