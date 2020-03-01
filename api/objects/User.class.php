<?php

/**
 * A basic user.
 */
class User {

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password";

        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":email", $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        return $stmt->execute();
    }

    public function emailExists() {
        $query = "SELECT id, firstname, lastname, password
                FROM {$this->table_name}
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if($rows_count > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];

            return true;
        }
    }

    public function toArray() {
        return array(
            "id" => $this->id,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "email" => $this->email,
       );
    }
}

?>
