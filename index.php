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

define('LOCKOUT', 1);
require('./core/global.php');

$post = $db->getLastNPosts(1);
log_append(print_r($post,true));
log_spew();

echo $templ->parseUniversal($post[0], $templ->blogtemp);

?>