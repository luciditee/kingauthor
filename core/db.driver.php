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

class DBDriver {
    private $pdo; 
    
    public function connect() {
        log_append("Connecting. Results to follow...");
        if ($this->pdo == null) {
            try {
                $this->pdo = new PDO(KA_DB_DRIVER . ':' . KA_DB_PATH);
                log_append('Connection attempt successful');
            } catch (Exception $e) {
                log_append('Connection attempt failed: ' . $e->getMessage());
            }
        }
    }

    private function query($msg) {
        log_append("executing query: " . $msg);
        return $this->pdo->query($msg);
    }

    private function buildPost($row) {
        return array(
            "id" => $row['id'],
            "tags" => array_map('intval', explode(KA_LIST_DELIM, $row['tags'])),
            "author" => $row['author'],
            "content" => $row['content'],
            "title" => $row['title'],
            "date" => $row['date'],
            "slug" => $row['slug'],
            "hidden" => ($row['hidden'] == 0 ? false : true)
        );
    }

    public function getSpecificPost($id) {
        // run query
        $stmt = $this->query('SELECT * FROM ' . KA_TBL_BLOGPOST . ' WHERE id = ' . $id);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // return as associative array
        return $this->buildPost($row);
    }

    public function getLastNPosts($count) {
        // run query
        $stmt = $this->query('SELECT * FROM ' . KA_TBL_BLOGPOST . ' ORDER BY id DESC LIMIT ' . $count);

        // return as associative array
        $ret = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($ret, $this->buildPost($row));
        }

        return $ret;
    }

    public function getAllPosts() {
        // run query
        $stmt = $this->query('SELECT * FROM ' . KA_TBL_BLOGPOST . ' ORDER BY id DESC');

        // return as associative array
        $ret = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($ret, $this->buildPost($row));
        }

        return $ret;
    }
}

$db = new DBDriver();
$db->connect();

?>