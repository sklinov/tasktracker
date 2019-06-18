<?php


    class Task {
        private $conn;
        private $table = "tasks";

        public $id;
        public $username;
        public $email;
        public $text;
        public $completed;

        public function __construct($db) {
            $this->conn = $db;
        }
        public function getAllTasks() {

           $query = 'SELECT * FROM '.$this->table;
           $stmt = $this->conn->prepare($query);
           $stmt->execute();
           return $stmt;
        }

        public function addTask() {
            $query = 'INSERT INTO '.$this->table.'
            SET
                username =:username,
                email =:email,
                text =:text,
                completed = :completed';             
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':username',$this->username);
            $stmt->bindParam(':email',$this->email);
            $stmt->bindParam(':text',$this->text);
            $stmt->bindParam(':completed',$this->completed);

            if($stmt->execute()) {
                return true;
            }
            echo "Error: %s".$stmt->error;
            return false; 
        }
        
        public function editTask() {
            $query = 'UPDATE '.$this->table.'
            SET
                username =:username,
                email =:email,
                text =:text,
                completed = :completed
            WHERE id=:id';             
 
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id',$this->id);
            $stmt->bindParam(':username',$this->username);
            $stmt->bindParam(':email',$this->email);
            $stmt->bindParam(':text',$this->text);
            $stmt->bindParam(':completed',$this->completed);

            if($stmt->execute()) {
                return true;
            }
            echo "Error: %s".$stmt->error;
            return false; 
        }
    }