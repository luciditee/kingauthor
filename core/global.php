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
require('log.php');

log_append("KingAuthor booting up");

require('./conf/config.php');
require('./core/db.driver.php');
require('./core/template.driver.php');

?>