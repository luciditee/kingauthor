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
require('log.php');

log_append("KingAuthor booting up");

require('./conf/config.php');
require('./core/db.driver.php');
require('./core/template.driver.php');

?>