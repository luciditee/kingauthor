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
                $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
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

        if (count($row) == 0) return null;

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

    public function getNumPosts() {
        $stmt = $this->query('SELECT COUNT(*) FROM ' . KA_TBL_BLOGPOST);
        $row = $stmt->fetchArray();
        return intval($row['count']);
    }

    public function deleteSpecificPost($whichOne) {
        log_append('Deleting post with ID #' . $whichOne);
        try {
            $stmt = $this->pdo->prepare("DELETE FROM ".KA_TBL_BLOGPOST." WHERE id = ?");
            if ($stmt) {
                $stmt->execute([$whichOne]);
            }
            
            // debug? maybe?
            log_append($this->pdo->errorInfo());
        } catch (PDOException $e) {
            log_append($e->getMessage());
        }
        return $stmt;
    }

    public function updatePost($post) {
        log_append('Updating post with ID #' . $post['id']);
        try {
            $stmt = $this->pdo->prepare("UPDATE ".KA_TBL_BLOGPOST." SET tags=?, author=?, content=?, title=?, date=?, slug=?, hidden=? WHERE id=?");
            if ($stmt) {
                $stmt->execute([$post['tags'], $post['author'], $post['content'], $post['title'], $post['date'], $post['slug'], $post['hidden'], $post['id']]);
            }

            // debug? maybe?
            log_append($this->pdo->errorInfo());
        } catch (PDOException $e) {
            log_append($e->getMessage());
        }

        return $stmt;
    }

    public function insertPost($post) {
        log_append('Inserting new post...');
        try {
            $stmt = $this->pdo->prepare("INSERT INTO ".KA_TBL_BLOGPOST." (tags, author, content, title, date, slug, hidden) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->execute([$post['tags'], $post['author'], $post['content'], $post['title'], $post['date'], $post['slug'], $post['hidden']]);
            }

            // debug? maybe?
            log_append($this->pdo->errorInfo());
        } catch (PDOException $e) {
            log_append($e->getMessage());
        }

        return $stmt;
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