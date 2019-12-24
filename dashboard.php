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

$post = $db->getLastNPosts(1);
log_append(print_r($post,true));
log_spew();

echo $templ->parseUniversal($post[0], $templ->blogtemp);

?>
