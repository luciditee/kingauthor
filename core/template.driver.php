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
    private function processToken($currentParseBuff, $key, $token, $data, $searchable=array()) {
        if (array_key_exists($key, $data)) {
            if (is_array($data[$key])) {
                if (count($searchable) == 0) 
                    return $this->tokenReplace($currentParseBuff, $token, KA_SEARCH_NONE);

                $retArr = array();
                for ($i = 0; $i < count($data[$key]); $i++) {
                    $data[$key][$i] = intval($data[$key][$i]);

                    if ($data[$key][$i] < 0 || $data[$key][$i] > count($searchable))
                        continue;
                    
                    $data[$key][$i] = $searchable[$data[$key][$i]];
                }
                return $this->tokenReplace($currentParseBuff, $token, implode(KA_ARRAY_DELIM, $data[$key]));
            } else {
                $d = $data[$key];
                if ($token == KA_TOKEN_CONTENT) {
                    $d = $this->markdownToHtml($data[$key]);
                }

                return $this->tokenReplace($currentParseBuff, $token, $d);
            }
        } else {
            return $currentParseBuff;
        }
    }

    // the meat and potatoes of the entire application
    // I might have written this drunk so I apologize for any jank
    public function parseUniversal($data, $template) {
        global $_KA_CATEGORIES; // ouch
        $ret = $template;

        $ret = $this->processToken($ret, "title", KA_TOKEN_TITLE, $data);
        $ret = $this->processToken($ret, "tags", KA_TOKEN_CATEGORY, $data, $_KA_CATEGORIES);
        $ret = $this->processToken($ret, "author", KA_TOKEN_AUTHOR, $data);
        $ret = $this->processToken($ret, "date", KA_TOKEN_DATETIME, $data);
        $ret = $this->processToken($ret, "content", KA_TOKEN_CONTENT, $data);
        

        return $ret;
    }
}

$templ = new TemplateDriver();

?>