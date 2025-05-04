<?php
    class Utente {
        private $id;

        public function __construct($id) {
            $this->id = $id;
        }

        public function getId() {
            return $this->id;
        }

        public function getUsername() {
            require_once "class/Database.php";
            $conn = Database::getInstance()->getConn();
            $query = "SELECT username FROM utenti WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            //ritornare lo username preso dalla query
            return $result->fetch_assoc()["username"];            
        }

        public function getEmail() {
            require_once "class/Database.php";
            $conn = Database::getInstance()->getConn();
            $query = "SELECT email FROM utenti WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            //ritornare lo username preso dalla query
            return $result->fetch_assoc()["email"];            
        }

        public function getGenere() {
            require_once "class/Database.php";
            $conn = Database::getInstance()->getConn();
            $query = "SELECT nome FROM generi_musicali WHERE id = (SELECT id_musica FROM utenti WHERE id = ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row ? $row["nome"] : null;
        }

        public function getStile() {
            require_once "class/Database.php";
            $conn = Database::getInstance()->getConn();
            $query = "SELECT nome FROM stili WHERE id = (SELECT id_stile FROM utenti WHERE id = ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row ? $row["nome"] : null;
        }
        

        public function getBio() {
            require_once "class/Database.php";
            $conn = Database::getInstance()->getConn();
            $query = "SELECT biografia FROM utenti WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $this->id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc()["biografia"];
        }
    }
?>