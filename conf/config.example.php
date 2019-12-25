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

/*
 * CONFIG.EXAMPLE.PHP
 * 
 * Copy this file to 'config.php' to get KA working.
 * Point it to the templates directory and the db
 * directory, as well as the output directory.
 * 
 * Feel free to customize defaults as well. Should be
 * fairly self-explanatory.
 */

require('./core/lockout.php');

// CUSTOMIZABLE VALUES
$_KA_CATEGORIES = array(
    'Uncategorized', 'Miscategorized', 'Funcategorized',
    'Steve'
);
define('KA_DEFAULT_AUTHOR', 'Arthur McAuthorpants');
define('KA_DEFAULT_CATEGORY', 0);
define('KA_DATESTAMP_FMT', 'g:ia T \o\n j F Y');
define('KA_LINK_ROOT', '//somewebsite.com/blog/');


// YOU PROBABLY SHOULDN'T CUSTOMIZE THESE VALUES
// DO SO AT YOUR OWN PERIL, DIPSHIT
define('KA_DB_DRIVER', 'sqlite');
define('KA_DB_PATH', './conf/blog.db'); // as seen from index.php
define('KA_TBL_BLOGPOST', 'blogpost');
define('KA_VERSION', '1.0');
define('KA_LIST_DELIM', ',');
define('KA_ARRAY_DELIM', ', ');
define('KA_DIR_TEMPLATES', "./page-templates/");
define('KA_TEMPL_BLOGPOST', "blogpost.template.html");
define('KA_TOKEN_DELIM', ':');
define('KA_TOKEN_PREFIX', 'KA');
define('KA_TOKEN_TITLE', 'PostTitle');
define('KA_TOKEN_AUTHOR', 'PostAuthor');
define('KA_TOKEN_CONTENT', 'PostContent');
define('KA_TOKEN_DATETIME', 'PostTimestamp');
define('KA_TOKEN_CATEGORY', 'PostCategory');
define('KA_SEARCH_NONE', 'None')

?>