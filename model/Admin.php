<?php


    class Admin {
        private $conn;
        private $table = "users";

        public $username;
        public $password;
        public $admin_logged_in = "false";
        
        public function __construct($db) {
            $this->conn = $db;
        }

        public function AdminLogin() {
            $query = "SELECT CASE WHEN EXISTS (
                SELECT *
                FROM ".$this->table."
                WHERE username = '".$this->username."' AND
                      password = '".$this->password."' AND
                      admin = 1
            )
            THEN CAST('true' AS CHARACTER)
            ELSE CAST('false' AS CHARACTER) END";

           $stmt = $this->conn->prepare($query);
           $stmt->execute();
           return $stmt;
        }
    }