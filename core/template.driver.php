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

require('./core/deps/Parsedown.php');

class TemplateDriver {
    private $parsedown = null;
    public $blogtemp = null;

    function __construct() {
        log_append("Initializing template driver");
        $this->parsedown = new Parsedown();
        try {
            $this->blogtemp = file_get_contents(KA_DIR_TEMPLATES . KA_TEMPL_BLOGPOST, TRUE);
        } catch (Exception $e) {
            log_append("Template driver failed to initialize: " . $e->getMessage());
            return;
        }

        log_append("... init success");
    }

    public function markdownToHtml($content) {
        return $this->parsedown->text($content);
    }

    private function tokenReplace($data, $token, $repl) {
        $bToken = "{" . KA_TOKEN_PREFIX . KA_TOKEN_DELIM . $token . "}";
        return str_replace($bToken, $repl, $data);
    }

    // currentParseBuff -- what is currently processed so far
    // key -- the key you're searching the input associative array for
    // token -- the KA_ token being searched for
    // data -- the associative array containing the post/page/whatever data
    private function processToken($currentParseBuff, $key, $token, $data) {
        if (array_key_exists($key, $data)) {
            return $this->tokenReplace($currentParseBuff, $token, $data[$key]);
        } else {
            return $currentParseBuff;
        }
    }

    // the meat and potatoes of the entire application
    // I might have written this drunk so I apologize for any jank
    public function parseUniversal($data, $template) {
        $ret = $template;

        $ret = $this->processToken($ret, "title", KA_TOKEN_TITLE, $data);

        return $ret;
    }
}

$templ = new TemplateDriver();

?>